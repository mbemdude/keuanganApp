<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM uang_saku WHERE siswa_id = :siswa_id";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':siswa_id', $_POST['siswa_id']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data siswa sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO uang_saku (siswa_id, saldo) VALUES (:siswa_id, :saldo)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':siswa_id', $_POST['siswa_id']);
        $stmt->bindParam(':saldo', $_POST['saldo']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=uang-saku'>";
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
                    <label for="siswa_id">Nama</label>
                    <select name="siswa_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectSiswaSQL = "SELECT * FROM siswa";
                        $stmtSiswa = $db->prepare($selectSiswaSQL);
                        $stmtSiswa->execute();

                        while ($rowSiswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowSiswa['id']}'>{$rowSiswa['nama']}</option>";
                        }
                        ?>
                    </select>
                    <label for="saldo">Saldo</label>
                    <input type="text" name="saldo" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=uang-saku" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>