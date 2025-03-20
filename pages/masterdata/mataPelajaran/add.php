<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM mata_pelajaran WHERE mata_pelajaran = :mata_pelajaran";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':mata_pelajaran', $_POST['mata_pelajaran']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data Mata Pelajaran sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO mata_pelajaran (mata_pelajaran) VALUES (:mata_pelajaran)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':mata_pelajaran', $_POST['mata_pelajaran']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=mata-pelajaran'>";
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
                    <label for="mata_pelajaran">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=mata-pelajaran" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>