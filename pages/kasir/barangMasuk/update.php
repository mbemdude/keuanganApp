<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM barang_masuk WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM barang_masuk id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data kdoe barang sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE barang_masuk SET barang_id = :barang_id, harga_beli = :harga_beli, jumlah = :jumlah, tanggal_transaksi = NOW() WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':barang_id', $_POST['barang_id']);
                $stmt->bindParam(':harga_beli', $_POST['harga_beli']);
                $stmt->bindParam(':jumlah', $_POST['jumlah']);
                $stmt->bindParam(':id', $_POST['id']);

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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Data</h3>
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
                                    $selected = ($rowBarang['id'] == $row['barang_id'] ? 'selected' : '');
                                    echo "<option value=\"" . $rowBarang['id'] . "\" $selected>" . $rowBarang['kode_barang'] | $rowBarang['nama_barang'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="harga_beli">Harga Beli</label>
                            <input type="text" name="harga_beli" class="form-control" value="<?= $row['harga_beli'] ?>">
                            <label for="jumlah">Jumlah / pckg</label>
                            <input type="text" name="jumlah" class="form-control" value="<?= $row['jumlah'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=barang" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=barang-masuk'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=barang-masuk'>";
}
?>
