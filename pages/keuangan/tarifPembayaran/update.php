<?php 
if (isset($_GET['jenjang_id'], $_GET['tahun_ajaran_id'], $_GET['tipe'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Ambil data yang akan diedit berdasarkan jenjang_id, tahun_ajaran_id, dan tipe
    $selectSql = "SELECT * FROM tarif_pembayaran WHERE jenjang_id = :jenjang_id AND tahun_ajaran_id = :tahun_ajaran_id AND tipe = :tipe";
    $stmt = $db->prepare($selectSql);
    $stmt->bindParam(':jenjang_id', $_GET['jenjang_id']);
    $stmt->bindParam(':tahun_ajaran_id', $_GET['tahun_ajaran_id']);
    $stmt->bindParam(':tipe', $_GET['tipe']);
    $stmt->execute();
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($data) > 0) {
        // Jika data ditemukan, tampilkan dalam form
        ?>
        <section class="content">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Edit Data</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="jenjang_id">Jenjang</label>
                            <select name="jenjang_id" class="form-select" disabled>
                                <option value="">- Pilih -</option>
                                <?php 
                                // Tampilkan data jenjang
                                $selectJenjangSQL = "SELECT * FROM jenjang";
                                $stmtJenjang = $db->prepare($selectJenjangSQL);
                                $stmtJenjang->execute();

                                while ($rowJenjang = $stmtJenjang->fetch(PDO::FETCH_ASSOC)){
                                    $selected = ($rowJenjang['id'] == $data[0]['jenjang_id']) ? "selected" : "";
                                    echo "<option value='{$rowJenjang['id']}' {$selected}>{$rowJenjang['jenjang']}</option>";
                                }
                                ?>
                            </select>
                            
                            <label for="tahun_ajaran_id">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="form-select" disabled>
                                <option value="">- Pilih -</option>
                                <?php 
                                // Tampilkan data tahun ajaran
                                $selectTahunAjaranSQL = "SELECT * FROM tahun_ajaran";
                                $stmtTahunAjaran = $db->prepare($selectTahunAjaranSQL);
                                $stmtTahunAjaran->execute();

                                while ($rowTahunAjaran = $stmtTahunAjaran->fetch(PDO::FETCH_ASSOC)){
                                    $selected = ($rowTahunAjaran['id'] == $data[0]['tahun_ajaran_id']) ? "selected" : "";
                                    echo "<option value='{$rowTahunAjaran['id']}' {$selected}>{$rowTahunAjaran['tahun_ajaran']}</option>";
                                }
                                ?>
                            </select>

                            <label for="nominal_uang_pangkal">Uang Pangkal</label>
                            <input type="text" name="nominal_uang_pangkal" class="form-control" value="<?php echo $data[0]['nominal']; ?>">
                            
                            <label for="nominal_daftar_ulang">Daftar Ulang</label>
                            <input type="text" name="nominal_daftar_ulang" class="form-control" value="<?php echo $data[1]['nominal']; ?>">
                            
                            <label for="nominal_spp">SPP</label>
                            <input type="text" name="nominal_spp" class="form-control" value="<?php echo $data[2]['nominal']; ?>">
                            
                            <label for="tipe">Tipe Pembayaran</label>
                            <input type="text" name="tipe" class="form-control" value="<?php echo $data[0]['tipe']; ?>" disabled>
                        </div>
                        <div class="mt-2">
                            <a href="?page=tarif-pembayaran" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "Data tidak ditemukan!";
    }
}

if (isset($_POST['button_update'])) {
    // Update data ke database
    $database = new Database();
    $db = $database->getConnection();

    $updateSql = "UPDATE tarif_pembayaran 
                  SET nominal = CASE jenis_pembayaran_id 
                                WHEN 1 THEN :nominal_uang_pangkal 
                                WHEN 2 THEN :nominal_daftar_ulang 
                                WHEN 3 THEN :nominal_spp 
                                END
                  WHERE jenjang_id = :jenjang_id AND tahun_ajaran_id = :tahun_ajaran_id AND tipe = :tipe";
    $stmtUpdate = $db->prepare($updateSql);
    $stmtUpdate->bindParam(':jenjang_id', $_GET['jenjang_id']);
    $stmtUpdate->bindParam(':tahun_ajaran_id', $_GET['tahun_ajaran_id']);
    $stmtUpdate->bindParam(':tipe', $_GET['tipe']);
    $stmtUpdate->bindParam(':nominal_uang_pangkal', $_POST['nominal_uang_pangkal']);
    $stmtUpdate->bindParam(':nominal_daftar_ulang', $_POST['nominal_daftar_ulang']);
    $stmtUpdate->bindParam(':nominal_spp', $_POST['nominal_spp']);
    
    if ($stmtUpdate->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil update data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal update data";
    }
    echo "<meta http-equiv='refresh' content='0;url=?page=tarif-pembayaran'>";
}
?>
