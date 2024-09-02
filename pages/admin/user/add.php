<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM users WHERE nip = :nip";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':nip', $_POST['nip']);
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
        $insertSql = "INSERT INTO users (nama, nip, jenis_kelamin, username, password, role_id) VALUES (:nama, :nip, :jenis_kelamin, :username, :password, :role_id)";
        $hashedPassword = md5($_POST['password']);
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':nama', $_POST['nama']);
        $stmt->bindParam(':nip', $_POST['nip']);
        $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role_id', $_POST['role_id']);
        
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
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control">
                    <label for="nip">NIP</label>
                    <input type="text" name="nip" class="form-control">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value=""> - Pilih - </option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control">
                    <label for="password">Password</label>
                    <input type="text" name="password" class="form-control">
                    <label for="role_id">Role</label>
                    <select name="role_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectRoleSQL = "SELECT * FROM role";
                        $stmtRole = $db->prepare($selectRoleSQL);
                        $stmtRole->execute();

                        while ($rowRole = $stmtRole->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowRole['id']}'>{$rowRole['role']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-2">
                    <a href="?page=user" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>