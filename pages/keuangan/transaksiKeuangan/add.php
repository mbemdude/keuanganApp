<?php 
if (isset($_POST['button_create'])) {

    // Menggunakan satu instance objek Database
    $database = new Database();
    $db = $database->getConnection();

    // Memulai transaksi
    $db->beginTransaction();

    // Validasi transaksi duplikat
    $validationSql = "SELECT * FROM transaksi_keuangan WHERE id = :id";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':id', $_POST['id']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Data transaksi sudah ada untuk tanggal ini";
    } else {
        $jumlahBayar = $_POST['jumlah'];
        $tagihanSiswaId = $_POST['tagihan_siswa_id'];

        // Ambil siswa_id dari tabel tagihan_siswa berdasarkan tagihan_siswa_id
        $selectSiswaSql = "SELECT siswa_id FROM tagihan_siswa WHERE id = :tagihan_siswa_id";
        $stmtSelectSiswa = $db->prepare($selectSiswaSql);
        $stmtSelectSiswa->bindParam(':tagihan_siswa_id', $tagihanSiswaId);
        $stmtSelectSiswa->execute();
        $siswa = $stmtSelectSiswa->fetch(PDO::FETCH_ASSOC);
        $siswaId = $siswa['siswa_id'];

        // Ambil semua tagihan siswa yang belum lunas berdasarkan siswa_id dan urutkan berdasarkan jenis_pembayaran_id
        $selectTagihanSql = "SELECT ts.id, ts.jumlah_tagihan, tp.jenis_pembayaran_id 
                             FROM tagihan_siswa ts 
                             JOIN tarif_pembayaran tp ON ts.tarif_pembayaran_id = tp.id 
                             WHERE ts.siswa_id = :siswa_id AND ts.jumlah_tagihan > 0 
                             ORDER BY tp.jenis_pembayaran_id ASC";
        $stmtSelectTagihan = $db->prepare($selectTagihanSql);
        $stmtSelectTagihan->bindParam(':siswa_id', $siswaId);
        $stmtSelectTagihan->execute();
        $tagihanSiswaRows = $stmtSelectTagihan->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tagihanSiswaRows as $row) {
            if ($jumlahBayar <= 0) {
                break; // Jika tidak ada lagi jumlah yang harus dibayar, keluar dari loop
            }

            $id = $row['id'];
            $jumlahTagihan = $row['jumlah_tagihan'];

            // Hitung pengurangan tagihan
            if ($jumlahBayar >= $jumlahTagihan) {
                $jumlahBayar -= $jumlahTagihan;
                $jumlahTagihan = 0;
            } else {
                $jumlahTagihan -= $jumlahBayar;
                $jumlahBayar = 0;
            }

            // Update tagihan siswa dengan jumlah tagihan yang baru
            $updateTagihanSql = "UPDATE tagihan_siswa SET jumlah_tagihan = :jumlah_tagihan WHERE id = :id AND siswa_id = :siswa_id";
            $stmtUpdateTagihan = $db->prepare($updateTagihanSql);
            $stmtUpdateTagihan->bindParam(':jumlah_tagihan', $jumlahTagihan);
            $stmtUpdateTagihan->bindParam(':id', $id);
            $stmtUpdateTagihan->bindParam(':siswa_id', $siswaId);

            if (!$stmtUpdateTagihan->execute()) {
                $db->rollBack(); // Rollback jika update gagal
                $_SESSION['hasil'] = false;
                $_SESSION['pesan'] = "Gagal mengurangi tagihan siswa";
                echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
                exit;
            }
        }

        // Jika ada sisa dari pembayaran setelah semua tagihan dibayar, masukkan ke saldo siswa
        if ($jumlahBayar > 0) {
            // Update saldo siswa
            $updateSaldoSql = "UPDATE uang_saku SET saldo = saldo + :jumlah_bayar WHERE siswa_id = :siswa_id";
            $stmtUpdateSaldo = $db->prepare($updateSaldoSql);
            $stmtUpdateSaldo->bindParam(':jumlah_bayar', $jumlahBayar);
            $stmtUpdateSaldo->bindParam(':siswa_id', $siswaId);

            if (!$stmtUpdateSaldo->execute()) {
                $db->rollBack(); // Rollback jika update saldo gagal
                $_SESSION['hasil'] = false;
                $_SESSION['pesan'] = "Gagal menambahkan sisa pembayaran ke saldo siswa";
                echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
                exit;
            }
        }

        // Insert data ke transaksi_keuangan jika semua update berhasil
        $insertSql = "INSERT INTO transaksi_keuangan (tagihan_siswa_id, jumlah, tanggal_transaksi) VALUES (:tagihan_siswa_id, :jumlah, :tanggal_transaksi)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':tagihan_siswa_id', $_POST['tagihan_siswa_id']);
        $stmt->bindParam(':jumlah', $_POST['jumlah']);
        $stmt->bindParam(':tanggal_transaksi', $_POST['tanggal_transaksi']);

        if ($stmt->execute()) {
            $db->commit(); // Commit jika semua operasi berhasil
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data dan mengurangi tagihan siswa";
        } else {
            $db->rollBack(); // Rollback jika insert transaksi gagal
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
    }
    
    // Redirect untuk menampilkan pesan di halaman yang sama
    echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
    exit;
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

                        $selectTagihanSiswaSQL = "SELECT ts.id, s.nama FROM tagihan_siswa ts JOIN siswa s ON ts.siswa_id = s.id GROUP BY s.nama";
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
                    <a href="?page=transaksi-keuangan" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>