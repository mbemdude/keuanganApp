<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM perbandingan_kriteria WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {
            // Validasi
            $validationSql = "SELECT * FROM perbandingan_kriteria WHERE kriteria_id_1 = :kriteria_id_1 AND kriteria_id_2 = :kriteria_id_2 AND id != :id";
            $stmtValidation = $db->prepare($validationSql);
            $stmtValidation->bindParam(':kriteria_id_1', $_POST['kriteria_id_1']);
            $stmtValidation->bindParam(':kriteria_id_2', $_POST['kriteria_id_2']);
            $stmtValidation->bindParam(':id', $_POST['id']);
            $stmtValidation->execute();

            if ($stmtValidation->rowCount() > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Data perbandingan kriteria sudah ada
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            } else {
                // Update Query
                $updateSql = "UPDATE perbandingan_kriteria SET kriteria_id_1 = :kriteria_id_1, kriteria_id_2 = :kriteria_id_2, nilai_perbandingan = :nilai_perbandingan WHERE id = :id";
                $stmt = $db->prepare($updateSql);
                $stmt->bindParam(':kriteria_id_1', $_POST['kriteria_id_1']);
                $stmt->bindParam(':kriteria_id_2', $_POST['kriteria_id_2']);
                $stmt->bindParam(':nilai_perbandingan', $_POST['nilai_perbandingan']);
                $stmt->bindParam(':id', $_POST['id']);

                if ($stmt->execute()) {
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
                echo "<meta http-equiv='refresh' content='0;url=?page=perbandingan-kriteria'>";
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
                            <label for="kriteria_id_1">Kriteria A</label>
                            <select name="kriteria_id_1" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectKriteriaSQL = "SELECT * FROM kriteria";
                                $stmtKriteria = $db->prepare($selectKriteriaSQL);
                                $stmtKriteria->execute();

                                while($rowKriteria = $stmtKriteria->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowKriteria['id'] == $row['kriteria_id_1']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowKriteria['id'] . "\" $selected>" . $rowKriteria['nama_kriteria'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="kriteria_id_2">Kriteria B</label>
                            <select name="kriteria_id_2" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php 
                                $database = new Database();
                                $db = $database->getConnection();

                                $selectKriteriaSQL = "SELECT * FROM kriteria";
                                $stmtKriteria = $db->prepare($selectKriteriaSQL);
                                $stmtKriteria->execute();

                                while($rowKriteria = $stmtKriteria->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($rowKriteria['id'] == $row['kriteria_id_2']) ? 'selected' : '';
                                    echo "<option value=\"" . $rowKriteria['id'] . "\" $selected>" . $rowKriteria['nama_kriteria'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="nilai_perbandingan">Nilai Perbandingan</label>
                            <input type="number" name="nilai_perbandingan" class="form-control" value="<?= $row['nilai_perbandingan'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        </div>
                        <div class="mt-2">
                            <a href="?page=perbandingan-kriteria" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=perbandingan-kriteria'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=perbandingan-kriteria'>";
}
?>
