<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM supplier WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM supplier WHERE no_mitra = :no_mitra AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':no_mitra', $_POST['no_mitra']);
            $stmtValidation->bindParam(':id', $_POST['id']);
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
                // Update Query
                $updateSql = "UPDATE supplier SET nama_supplier = :nama_supplier, no_telp = :no_telp, no_mitra = :no_mitra WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':nama_supplier', $_POST['nama_supplier']);
                $stmt->bindParam(':no_telp', $_POST['no_telp']);
                $stmt->bindParam(':no_mitra', $_POST['no_mitra']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=supplier'>";
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
                            <label for="nama_supplier">Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control" value="<?= $row['nama_supplier'] ?>">
                            <label for="no_telp">No Telepon</label>
                            <input type="text" name="no_telp" class="form-control" value="<?= $row['no_telp'] ?>">
                            <label for="no_mitra">No Mitra</label>
                            <input type="text" name="no_mitra" class="form-control" value="<?= $row['no_mitra'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=supplier" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=supplier'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=supplier'>";
}
?>
