<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM user WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM user WHERE nip = :nip AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':nip', $_POST['nip']);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data nip sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE user SET nama = :nama, nip = :nip, role_id = :role_id WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':nip', $_POST['nip']);
                $stmt->bindParam(':nama', $_POST['nama']);
                $stmt->bindParam(':role_id', $_POST['role_id']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=user'>";
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
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>">
                            <label for="nip">NIP</label>
                            <input type="text" name="nip" class="form-control" value="<?= $row['nip'] ?>">
                            <label for="role_id">Role</label>
                            <select name="role_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectRoleSQL = "SELECT * FROM role";
                                $stmtRole = $db->prepare($selectRoleSQL);
                                $stmtRole->execute();

                                while($rowRole = $stmtRole->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowRole['id'] == $row['role_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowRole['id'] . "\" $selected>" . $rowRole['role'] . "</option>";
                                }
                                ?>
                            </select>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=role" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=user'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=user'>";
}
?>
