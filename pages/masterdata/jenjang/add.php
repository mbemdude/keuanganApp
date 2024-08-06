<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM jenjang WHERE jenjang = :jenjang";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':jenjang', $_POST['jenjang']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data jenjang sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO jenjang (jenjang) VALUES (:jenjang)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':jenjang', $_POST['jenjang']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=jenjang'>";
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
                    <label for="jenjang">Jenjang</label>
                    <input type="text" name="jenjang" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=jenjang" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>