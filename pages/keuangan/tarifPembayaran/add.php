<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    // Loop through each payment type and insert them one by one
    for ($i = 0; $i < count($_POST['jenis_pembayaran']); $i++) {

        $validationSql = "SELECT * FROM tarif_pembayaran WHERE jenis_pembayaran_id = :jenis_pembayaran_id AND tipe = :tipe AND tahun_ajaran_id = :tahun_ajaran_id";
        $stmtValidation = $db->prepare($validationSql);
        $stmtValidation->bindParam(':tahun_ajaran_id', $_POST['tahun_ajaran_id']);
        $stmtValidation->bindParam(':jenis_pembayaran_id', $_POST['jenis_pembayaran'][$i]);
        $stmtValidation->bindParam(':tipe', $_POST['tipe']);
        $stmtValidation->execute();

        if ($stmtValidation->rowCount() > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5>Gagal</h5>
                Data tarif pembayaran sudah ada untuk tipe pembayaran <?php echo $_POST['jenis_pembayaran'][$i]; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        } else {
            $insertSql = "INSERT INTO tarif_pembayaran (jenjang_id, jenis_pembayaran_id, tahun_ajaran_id, tipe, nominal) VALUES (:jenjang_id, :jenis_pembayaran_id, :tahun_ajaran_id, :tipe, :nominal)";
            $stmt = $db->prepare($insertSql);
            $stmt->bindParam(':jenjang_id', $_POST['jenjang_id']);
            $stmt->bindParam(':jenis_pembayaran_id', $_POST['jenis_pembayaran'][$i]);
            $stmt->bindParam(':tahun_ajaran_id', $_POST['tahun_ajaran_id']);
            $stmt->bindParam(':tipe', $_POST['tipe']);
            $stmt->bindParam(':nominal', $_POST['nominal'][$i]);

            if ($stmt->execute()) {
                $_SESSION['hasil'] = true;
                $_SESSION['pesan'] = "Berhasil simpan data untuk tipe pembayaran " . $_POST['jenis_pembayaran'][$i];
            } else {
                $_SESSION['hasil'] = false;
                $_SESSION['pesan'] = "Gagal simpan data untuk tipe pembayaran " . $_POST['jenis_pembayaran'][$i];
            }
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=?page=tarif-pembayaran'>";
}
?>

<section class="content">
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="jenjang_id">Jenjang</label>
                    <select name="jenjang_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectJenjangSQL = "SELECT * FROM jenjang";
                        $stmtJenjang = $db->prepare($selectJenjangSQL);
                        $stmtJenjang->execute();

                        while ($rowJenjang = $stmtJenjang->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowJenjang['id']}'>{$rowJenjang['jenjang']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Loop through each payment type -->
                <?php 
                $tipePembayaran = [
                    ['id' => 1, 'label' => 'Uang Pangkal'],
                    ['id' => 2, 'label' => 'Daftar Ulang'],
                    ['id' => 3, 'label' => 'SPP']
                ];

                foreach ($tipePembayaran as $tipe) { ?>
                    <div class="form-group">
                        <label for="nominal"><?php echo $tipe['label']; ?></label>
                        <input type="hidden" name="jenis_pembayaran[]" class="form-control" value="<?php echo $tipe['id']; ?>">
                        <input type="text" name="nominal[]" class="form-control">
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="tahun_ajaran_id">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $selectTahunAjaranSQL = "SELECT * FROM tahun_ajaran";
                        $stmtTahunAjaran = $db->prepare($selectTahunAjaranSQL);
                        $stmtTahunAjaran->execute();

                        while ($rowTahunAjaran = $stmtTahunAjaran->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowTahunAjaran['id']}'>{$rowTahunAjaran['tahun_ajaran']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Tipe</label> <br>
                    <input class="form-check-input" type="radio" name="tipe" value="1">
                    <label for="tipe">Tipe 1</label>
                    <input class="form-check-input" type="radio" name="tipe" value="2">
                    <label for="tipe">Tipe 2</label>
                    <input class="form-check-input" type="radio" name="tipe" value="3">
                    <label for="tipe">Tipe 3</label>
                </div>

                <div class="mt-2">
                    <a href="?page=tarif-pembayaran" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
