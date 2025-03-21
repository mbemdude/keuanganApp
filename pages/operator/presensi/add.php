<?php
$database = new Database();
$db = $database->getConnection();

$kelas_terpilih = "";
$siswa_result = [];

// Jika memilih kelas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pilih_kelas'])) {
    $kelas_terpilih = $_POST['kelas'];
    $stmt = $db->prepare("SELECT * FROM siswa WHERE kelas_id = :kelas_id");
    $stmt->bindParam(':kelas_id', $kelas_terpilih);
    $stmt->execute();
    $siswa_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Jika menyimpan presensi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['button_create'])) {
    $tanggal = $_POST['tanggal'];
    $mata_pelajaran_id = $_POST['mata_pelajaran_id'];

    // Pastikan mata pelajaran dipilih
    if (empty($mata_pelajaran_id)) {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Silakan pilih mata pelajaran!";
        echo "<meta http-equiv='refresh' content='0;url=?page=presensi'>";
        exit();
    }

    // Simpan presensi untuk setiap siswa
    foreach ($_POST['siswa_id'] as $siswa_id) {
        $status = $_POST['presensi'][$siswa_id];

        $query = "INSERT INTO presensi (siswa_id, tanggal, mata_pelajaran_id, status) 
                  VALUES (:siswa_id, :tanggal, :mata_pelajaran_id, :status)
                  ON DUPLICATE KEY UPDATE status=:status";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':mata_pelajaran_id', $mata_pelajaran_id);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil simpan data";
    echo "<meta http-equiv='refresh' content='0;url=?page=presensi'>";
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Presensi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST" class="d-flex gap-2 mb-3">
                            <div class="flex-grow-1">
                                <!-- 🔹 Input Tanggal -->
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" 
                                       value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d'); ?>" required>

                                <!-- 🔹 Dropdown Mata Pelajaran -->
                                <label class="mt-2">Pilih Mata Pelajaran:</label>
                                <select name="mata_pelajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php
                                    $selectMataPelajaranSQL = "SELECT * FROM mata_pelajaran";
                                    $stmtMataPelajaran = $db->prepare($selectMataPelajaranSQL);
                                    $stmtMataPelajaran->execute();
                                    while ($rowMataPelajaran = $stmtMataPelajaran->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = (isset($_POST['mata_pelajaran_id']) && $_POST['mata_pelajaran_id'] == $rowMataPelajaran['id']) ? "selected" : "";
                                        echo "<option value='{$rowMataPelajaran['id']}' $selected>{$rowMataPelajaran['mata_pelajaran']}</option>";
                                    }
                                    ?>
                                </select>

                                <!-- 🔹 Dropdown Kelas -->
                                <label class="mt-2">Pilih Kelas:</label>
                                <select name="kelas" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    $kelas_result = $db->query("SELECT DISTINCT k.id, k.kelas FROM kelas k JOIN siswa s ON k.id = s.kelas_id");
                                    while ($row = $kelas_result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($kelas_terpilih == $row['id']) ? "selected" : "";
                                        echo "<option value='{$row['id']}' $selected>{$row['kelas']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" name="pilih_kelas" class="btn btn-primary mt-auto">Tampilkan Siswa</button>
                        </form>
                    </div>
                </div>

                <?php if (!empty($siswa_result)) { ?>
                    <form method="POST">
                        <!-- 🔹 Pastikan Tanggal & Mata Pelajaran Terkirim dengan Hidden Input -->
                        <input type="hidden" name="tanggal" value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d'); ?>">
                        <input type="hidden" name="mata_pelajaran_id" value="<?= isset($_POST['mata_pelajaran_id']) ? $_POST['mata_pelajaran_id'] : ''; ?>">
                        <input type="hidden" name="kelas" value="<?= $kelas_terpilih; ?>">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Presensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswa_result as $row) { ?>
                                    <tr>
                                        <td><?= $row['nama']; ?></td>
                                        <td>
                                            <input type="hidden" name="siswa_id[]" value="<?= $row['id']; ?>">
                                            <div class="d-flex gap-2">
                                                <input type="radio" name="presensi[<?= $row['id']; ?>]" value="Hadir" checked> Hadir
                                                <input type="radio" name="presensi[<?= $row['id']; ?>]" value="Sakit"> Sakit
                                                <input type="radio" name="presensi[<?= $row['id']; ?>]" value="Izin"> Izin
                                                <input type="radio" name="presensi[<?= $row['id']; ?>]" value="Alfa"> Alfa
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <a href="?page=presensi" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_create" class="btn btn-success">Simpan Presensi</button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</section>