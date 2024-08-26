<?php 
if (isset($_POST['button_create'])) {

    // Menggunakan satu instance objek Database
    $database = new Database();
    $db = $database->getConnection();

    // Mengubah validasi agar sesuai dengan logika untuk mencegah duplikat
    $validationSql = "SELECT * FROM transaksi_keuangan WHERE tagihan_siswa_id = :tagihan_siswa_id AND tanggal_transaksi = :tanggal_transaksi";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':tagihan_siswa_id', $_POST['tagihan_siswa_id']);
    $stmtValidation->bindParam(':tanggal_transaksi', $_POST['tanggal_transaksi']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Data transaksi sudah ada untuk tanggal ini";
    } else {
        // Insert data ke database
        $insertSql = "INSERT INTO transaksi_keuangan (tagihan_siswa_id, jumlah, tanggal_transaksi) VALUES (:tagihan_siswa_id, :jumlah, :tanggal_transaksi)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':tagihan_siswa_id', $_POST['tagihan_siswa_id']);
        $stmt->bindParam(':jumlah', $_POST['jumlah']);
        $stmt->bindParam(':tanggal_transaksi', $_POST['tanggal_transaksi']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
    }
    
    // Redirect untuk menampilkan pesan di halaman yang sama
    echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    exit; // Tambahkan exit agar kode di bawah tidak dieksekusi setelah redirect
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
                    <label for="tagihan_siswa_id">Nama Yang Dituju</label>
                    <select name="tagihan_siswa_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectTagihanSiswaSQL = "SELECT ts.id, s.nama FROM tagihan_siswa ts JOIN siswa s ON ts.siswa_id = s.id";
                        $stmtTagihanSiswa = $db->prepare($selectTagihanSiswaSQL);
                        $stmtTagihanSiswa->execute();

                        while ($rowTagihanSiswa = $stmtTagihanSiswa->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowTagihanSiswa['id']}'>{$rowTagihanSiswa['nama']}</option>";
                        }
                        ?>
                    </select>
                    <label for="jumlah">Nominal Pembayaran</label>
                    <input type="number" name="jumlah" class="form-control" required>
                    <label for="tanggal_transaksi">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal_transaksi" class="form-control" required>
                </div>
                <div class="mt-2">
                    <a href="?page=siswa" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
