<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM perbandingan_kriteria WHERE kriteria_id_1 = :kriteria_id_1 AND kriteria_id_2 = :kriteria_id_2";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':kriteria_id_1', $_POST['kriteria_id_1']);
    $stmtValidation->bindParam(':kriteria_id_2', $_POST['kriteria_id_2']);
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
        $insertSql = "INSERT INTO perbandingan_kriteria (kriteria_id_1, kriteria_id_2, nilai_perbandingan) VALUES (:kriteria_id_1, :kriteria_id_2, :nilai_perbandingan)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':kriteria_id_1', $_POST['kriteria_id_1']);
        $stmt->bindParam(':kriteria_id_2', $_POST['kriteria_id_2']);
        $stmt->bindParam(':nilai_perbandingan', $_POST['nilai_perbandingan']);
        
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
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
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

                        while ($rowKriteria = $stmtKriteria->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowKriteria['id']}'>{$rowKriteria['nama_kriteria']}</option>";
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

                        while ($rowKriteria = $stmtKriteria->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowKriteria['id']}'>{$rowKriteria['nama_kriteria']}</option>";
                        }
                        ?>
                    </select>
                    <label for="nilai_perbandingan">Nilai Perbandingan</label>
                    <input type="text" name="nilai_perbandingan" class="form-control">
                </div>
                <div class="mt-2">
                    <a href="?page=perbandingan-kriteria" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>