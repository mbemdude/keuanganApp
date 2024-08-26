<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM transaksi_keuangan WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM transaksi_keuangan WHERE nis = :nis AND nama = :nama AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':nis', $_POST['nis']);
            $stmtValidation->bindParam(':nama', $_POST['nama']);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data nis atau nama sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE transaksi_keuangan SET tagihan_siswa_id = :tagihan_siswa_id, jumlah = :jumlah, tanggal_transaksi = :tanggal_transaksi WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':tagihan_siswa_id', $_POST['tagihan_siswa_id']);
                $stmt->bindParam(':jumlah', $_POST['jumlah']);
                $stmt->bindParam(':tanggal_transaksi', $_POST['tanggal_transaksi']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
            }
        }
        ?>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Data</h3>
                </div>
                <div class="card-body">
                    <form method="POST">    
                        <div class="form-group">
                            <label for="tagihan_siswa_id">Nama Yang Dituju</label>
                            <select name="tagihan_siswa_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectJenjangSQL = "SELECT tk.*, s.nama FROM transaksi_keuangan tk JOIN tagihan_siswa ts ON tk.tagihan_siswa_id = ts.id JOIN siswa s ON tk.siswa_id = siswa.id";
                                $stmtJenjang = $db->prepare($selectJenjangSQL);
                                $stmtJenjang->execute();

                                while($rowJenjang = $stmtJenjang->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowJenjang['id'] == $row['tagihan_siswa_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowJenjang['id'] . "\" $selected>" . $rowJenjang['nama'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="jumlah">Nominal Pembayaran</label>
                            <input type="number" name="jumlah" class="form-control" value="<?= $row['jumlah'] ?>">
                            <label for="tanggal_transaksi">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" class="form-control" value="<?= $row['tanggal_transaksi'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=transaksi-keuangan" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
}
?>
