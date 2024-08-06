<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM uang_saku WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM uang_saku WHERE siswa_id = :siswa_id  AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':siswa_id', $_POST['siswa_id']);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data siswa sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE uang_saku SET siswa_id = :siswa_id, saldo = :saldo WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':siswa_id', $_POST['siswa_id']);
                $stmt->bindParam(':saldo', $_POST['saldo']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=uang-saku'>";
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
                            <label for="siswa_id">Siswa</label>
                            <select name="siswa_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectSiswaSQL = "SELECT * FROM siswa";
                                $stmtSiswa = $db->prepare($selectSiswaSQL);
                                $stmtSiswa->execute();

                                while($rowSiswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowSiswa['id'] == $row['siswa_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowSiswa['id'] . "\" $selected>" . $rowSiswa['nama'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="saldo">Saldo</label>
                            <input type="text" name="saldo" class="form-control" value="<?= $row['saldo'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=uang-saku" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=uang-saku'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=uang-saku'>";
}
?>
