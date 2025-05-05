<?php
// Menyimpan nilai ke database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_nilai'])) {
    $mata_pelajaran_id = $_POST['mata_pelajaran_id'];
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
    $semester = $_POST['semester'];

    foreach ($_POST['siswa_id'] as $key => $siswa_id) {
        $nilai_uas = $_POST['nilai_uas'][$key] ?? null;

        $query = "INSERT INTO nilai_rapor (siswa_id, mata_pelajaran_id, tahun_ajaran_id, semester, nilai_uas) 
                  VALUES (:siswa_id, :mata_pelajaran_id, :tahun_ajaran_id, :semester, :nilai_uas)
                  ON DUPLICATE KEY UPDATE nilai_uas = :nilai_uas";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id);
        $stmt->bindParam(':mata_pelajaran_id', $mata_pelajaran_id);
        $stmt->bindParam(':tahun_ajaran_id', $tahun_ajaran_id);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':nilai_uas', $nilai_uas);
        $stmt->execute();
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Nilai berhasil disimpan!";
    echo "<meta http-equiv='refresh' content='0;url=?page=nilai-rapor-uas'>";
    exit();
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Nilai Rapor</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Pilih Kelas:</label>
                            <select name="kelas_id" class="form-select" required onchange="this.form.submit()">
                                <option value="">-- Pilih Kelas --</option>
                                <?php
                                $kelas_result = $db->query("SELECT * FROM kelas");
                                while ($row = $kelas_result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = (isset($_POST['kelas_id']) && $_POST['kelas_id'] == $row['id']) ? "selected" : "";
                                    echo "<option value='{$row['id']}' $selected>{$row['kelas']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Pilih Mata Pelajaran:</label>
                            <select name="mata_pelajaran_id" class="form-select" required onchange="this.form.submit()">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php
                                $mapel_result = $db->query("SELECT * FROM mata_pelajaran");
                                while ($row = $mapel_result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = (isset($_POST['mata_pelajaran_id']) && $_POST['mata_pelajaran_id'] == $row['id']) ? "selected" : "";
                                    echo "<option value='{$row['id']}' $selected>{$row['mata_pelajaran']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Pilih Semester:</label>
                            <select name="semester" class="form-select" required onchange="this.form.submit()">
                                <option value="1" <?= (isset($_POST['semester']) && $_POST['semester'] == "1") ? "selected" : ""; ?>>Semester 1</option>
                                <option value="2" <?= (isset($_POST['semester']) && $_POST['semester'] == "2") ? "selected" : ""; ?>>Semester 2</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-3">
                            <label>Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="form-select" required onchange="this.form.submit()">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php 
                                $ta_result = $db->query("SELECT * FROM tahun_ajaran");
                                while ($row = $ta_result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = (isset($_POST['tahun_ajaran_id']) && $_POST['tahun_ajaran_id'] == $row['id']) ? "selected" : "";
                                    echo "<option value='{$row['id']}' $selected>{$row['tahun_ajaran']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <?php if (!empty($_POST['kelas_id']) && !empty($_POST['mata_pelajaran_id']) && !empty($_POST['semester']) && !empty($_POST['tahun_ajaran_id'])) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Nilai UAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $kelas_id = $_POST['kelas_id'];
                                $mata_pelajaran_id = $_POST['mata_pelajaran_id'];
                                $semester = $_POST['semester'];
                                $tahun_ajaran_id = $_POST['tahun_ajaran_id'];

                                $stmt = $db->prepare("SELECT s.id AS siswa_id, s.nama, nr.nilai_uas
                                                      FROM siswa s 
                                                      LEFT JOIN nilai_rapor nr 
                                                      ON s.id = nr.siswa_id 
                                                      AND nr.mata_pelajaran_id = :mata_pelajaran_id 
                                                      AND nr.semester = :semester 
                                                      AND nr.tahun_ajaran_id = :tahun_ajaran_id 
                                                      WHERE s.kelas_id = :kelas_id");
                                $stmt->bindParam(':kelas_id', $kelas_id);
                                $stmt->bindParam(':mata_pelajaran_id', $mata_pelajaran_id);
                                $stmt->bindParam(':semester', $semester);
                                $stmt->bindParam(':tahun_ajaran_id', $tahun_ajaran_id);
                                $stmt->execute();
                                $siswa_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($siswa_result as $row) {
                                ?>
                                    <tr>
                                        <td><?= $row['nama']; ?></td>
                                        <td>
                                            <input type="hidden" name="siswa_id[]" value="<?= $row['siswa_id']; ?>">
                                            <input type="number" name="nilai_uas[]" class="form-control" 
                                                   value="<?= $row['nilai_uas']; ?>" required>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <div class="mt-3">
                            <a href="?page=nilai-rapor-uas" class="btn btn-danger">Batal</a>
                            <button type="submit" name="simpan_nilai" class="btn btn-success">Simpan Nilai</button>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</section>
