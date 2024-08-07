<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM barang WHERE kode_barang = :kode_barang";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':kode_barang', $_POST['kode_barang']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data kode barang sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO barang (kode_barang, nama_barang, harga, stock) VALUES (:kode_barang, :nama_barang, :harga, :stock)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':kode_barang', $_POST['kode_barang']);
        $stmt->bindParam(':nama_barang', $_POST['nama_barang']);
        $stmt->bindParam(':harga', $_POST['harga']);
        $stmt->bindParam(':stock', $_POST['stock']);
        
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
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control">
                    <label for="harga">Harga</label>
                    <input type="text" name="harga" class="form-control">
                    <label for="stock">Stock</label>
                    <input type="text" name="stock" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=barang" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>