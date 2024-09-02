<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM users WHERE nip = :nip AND id != :id";
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
                $updateSql = "UPDATE users SET nama = :nama, nip = :nip, jenis_kelamin = :jenis_kelamin, username = :username, password = :password, role_id = :role_id WHERE id = :id";
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':nip', $_POST['nip']);
                $stmt->bindParam(':nama', $_POST['nama']);
                $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
                $stmt->bindParam(':username', $_POST['username']);
                $stmt->bindParam(':password', $$hashedPassword);
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
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value=""> - Pilih -</option>
                                <option value="L" <?= ($row['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="P" <?= ($row['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>">
                            <label for="password">Password</label>
                            <input type="text" name="password" class="form-control" value="">
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
                            <a href="?page=user" class="btn btn-danger">Batal</a>
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
