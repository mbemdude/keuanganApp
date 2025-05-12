<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validationSql = "SELECT * FROM kriteria WHERE kriteria = :kriteria";
    $stmtValidation = $db->prepare($validationSql);
    $stmtValidation->bindParam(':kriteria', $_POST['kriteria']);
    $stmtValidation->execute();

    if ($stmtValidation->rowCount() > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>Gagal</h5>
            Data kriteria sudah ada
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
    } else {
        $insertSql = "INSERT INTO kriteria (kriteria, bobot, tipe) VALUES (:kriteria, :bobot, :tipe)";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(':kriteria', $_POST['kriteria']);
        $stmt->bindParam(':bobot', $_POST['bobot']);
        $stmt->bindParam(':tipe', $_POST['tipe']);
        
        if ($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=kriteria'>";
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
                    <label for="kriteria">Kriteria</label>
                    <input type="text" name="kriteria" class="form-control">
                    <label for="bobot">Bobot</label>
                    <input type="text" name="bobot" class="form-control">
                    <label for="tipe">Tipe</label>
                    <select name="tipe" class="form-select">
                        <option value=""> - Pilih Tipe -</option>
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                </div>
                <div class="mt-2">
                    <a href="?page=kriteria" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>