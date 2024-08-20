<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM jenis_pembayaran WHERE jenis_pembayaran = :jenis_pembayaran";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':jenis_pembayaran', $_POST['jenis_pembayaran']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data jenis pembayaran sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO jenis_pembayaran (jenis_pembayaran) VALUES (:jenis_pembayaran)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':jenis_pembayaran', $_POST['jenis_pembayaran']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=jenis-pembayaran'>";
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
                    <label for="jenis_pembayaran">Jenis Pembayaran</label>
                    <input type="text" name="jenis_pembayaran" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=jenis-pembayaran" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>