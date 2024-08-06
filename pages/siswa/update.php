<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM siswa WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM siswa WHERE nis = :nis AND nama = :nama AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':nis', $_POST['nis']);
            $stmtValidation->bindParam(':nama', $_POST['nama']);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data nis atau nama sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE siswa SET kode = :kode, nis = :nis, nama = :nama, jenjang_id = :jenjang_id, kelas_id = :kelas_id, status_id = :status_id WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':kode', $_POST['kode']);
                $stmt->bindParam(':nis', $_POST['nis']);
                $stmt->bindParam(':nama', $_POST['nama']);
                $stmt->bindParam(':jenjang_id', $_POST['jenjang_id']);
                $stmt->bindParam(':kelas_id', $_POST['kelas_id']);
                $stmt->bindParam(':status_id', $_POST['status_id']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
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
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" class="form-control" value="<?= $row['kode'] ?>">
                            <label for="nis">NIS</label>
                            <input type="text" name="nis" class="form-control" value="<?= $row['nis'] ?>">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>">
                            <label for="jenjang_id">Jenjang</label>
                            <select name="jenjang_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectJenjangSQL = "SELECT * FROM jenjang";
                                $stmtJenjang = $db->prepare($selectJenjangSQL);
                                $stmtJenjang->execute();

                                while($rowJenjang = $stmtJenjang->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowJenjang['id'] == $row['jenjang_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowJenjang['id'] . "\" $selected>" . $rowJenjang['jenjang'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="kelas_id">Kelas</label>
                            <select name="kelas_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectKelasSQL = "SELECT * FROM kelas";
                                $stmtKelas = $db->prepare($selectKelasSQL);
                                $stmtKelas->execute();

                                while($rowKelas = $stmtKelas->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowKelas['id'] == $row['kelas_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowKelas['id'] . "\" $selected>" . $rowKelas['kelas'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="status_id">Status</label>
                            <select name="status_id" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectStatusSQL = "SELECT * FROM status";
                                $stmtStatus = $db->prepare($selectStatusSQL);
                                $stmtStatus->execute();

                                while($rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowStatus['id'] == $row['status_id']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowStatus['id'] . "\" $selected>" . $rowStatus['status'] . "</option>";
                                }
                                ?>
                            </select>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=siswa" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
}
?>
