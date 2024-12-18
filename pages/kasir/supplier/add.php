<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM supplier WHERE no_mitra = :no_mitra";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':no_mitra', $_POST['no_mitra']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data supplier sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO supplier (nama_supplier, no_telp, no_mitra) VALUES (:nama_supplier, :no_telp, :no_mitra)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':nama_supplier', $_POST['nama_supplier']);
        $stmt->bindParam(':no_telp', $_POST['no_telp']);
        $stmt->bindParam(':no_mitra', $_POST['no_mitra']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=barang'>";
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
                    <label for="nama_supplier">Nama</label>
                    <input type="text" name="nama_supplier" class="form-control">
                    <label for="no_telp">No Telepon</label>
                    <input type="text" name="no_telp" class="form-control">
                    <label for="no_mitra">No Mitra</label>
                    <input type="text" name="no_mitra" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=barang" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>