<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM barang_masuk WHERE id = :id";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':id', $_POST['id']);
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
        $insertSql = "INSERT INTO barang_masuk (harga_beli, jumlah, tanggal_transaksi, barang_id, supplier_id) 
                      VALUES (:harga_beli, :jumlah, NOW(), :barang_id, :supplier_id)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':harga_beli', $_POST['harga_beli']);
        $stmt->bindParam(':jumlah', $_POST['jumlah']);
        $stmt->bindParam(':barang_id', $_POST['barang_id']);
        $stmt->bindParam(':supplier', $_POST['supplier']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=barang-masuk'>";
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
                    <label for="barang_id">Barang</label>
                    <select name="barang_id" class="form-select">
                        <option value=""> - Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectBarang = "SELECT * barang";
                        $stmtBarang = $db->prepare($selectBarang);
                        $stmtBarang->execute();

                        while ($rowBarang = $stmtBarang->fetch(PDO::FETCH_ASSOC)) {
                            "<option value='{$rowBarang['id']}'>{$rowBarang['kode_barang']} | {$rowBarang['nama_barang']}</option>";
                        }
                        ?>
                    </select>
                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value=""> - Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectSupplier = "SELECT * supplier";
                        $stmtSupplier = $db->prepare($selectSupplier);
                        $stmtSupplier->execute();

                        while ($rowSupplier = $stmtSupplier->fetch(PDO::FETCH_ASSOC)) {
                            "<option value='{$rowSupplier['id']}'>{$rowSupplier['supplier']}</option>";
                        }
                        ?>
                    </select>
                    <label for="harga_beli">Harga Beli/pckg</label>
                    <input type="text" name="harga_beli" class="form-control">
                    <label for="jumlah">Jumlah (pckg)</label>
                    <input type="text" name="jumlah" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=barang-masuk" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>