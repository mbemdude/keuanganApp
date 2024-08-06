<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM siswa WHERE nama = :nama AND nis = :nis";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':nama', $_POST['nama']);
    $stmtValidation->bindParam(':nis', $_POST['nis']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data nama atau nis sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO siswa (kode, nama, nis, jenjang_id, kelas_id, status_id) VALUES (:kode, :nama, :nis, :jenjang_id, :kelas_id, :status_id)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':kode', $_POST['kode']);
        $stmt->bindParam(':nama', $_POST['nama']);
        $stmt->bindParam(':nis', $_POST['nis']);
        $stmt->bindParam(':jenjang_id', $_POST['jenjang_id']);
        $stmt->bindParam(':kelas_id', $_POST['kelas_id']);
        $stmt->bindParam(':status_id', $_POST['status_id']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    }
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
                    <label for="kode">Kode</label>
                    <input type="text" name="kode" class="form-control">
                    <label for="nis">NIS</label>
                    <input type="text" name="nis" class="form-control">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control">
                    <label for="Jenjang_id">Jenjang</label>
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
                    <label for="kelas_id">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectKelasSQL = "SELECT * FROM kelas";
                        $stmtKelas = $db->prepare($selectKelasSQL);
                        $stmtKelas->execute();

                        while ($rowKelas = $stmtKelas->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowKelas['id']}'>{$rowKelas['kelas']}</option>";
                        }
                        ?>
                    </select>
                    <label for="status_id">Status</label>
                    <select name="status_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectStatusSQL = "SELECT * FROM status";
                        $stmtStatus = $db->prepare($selectStatusSQL);
                        $stmtStatus->execute();

                        while ($rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowStatus['id']}'>{$rowStatus['status']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-2">
                    <a href="?page=siswa" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>