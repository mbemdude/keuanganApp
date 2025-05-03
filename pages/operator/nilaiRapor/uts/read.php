<?php 
    if (isset($_SESSION["hasil"])) {
    ?>
        <div class="alert alert-<?php echo $_SESSION["hasil"] ? "success" : "danger"; ?> alert-dismissible fade show" role="alert">
            <h5><?php echo $_SESSION["hasil"] ? "Berhasil" : "Gagal"; ?></h5>
            <?php echo $_SESSION['pesan']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php 
        unset($_SESSION['hasil']);
        unset($_SESSION['pesan']);
    }
    ?>

    <?php
        $database = new Database();
        $db = $database->getConnection();
        
        $tahunAjaranAktif = isset($_POST['tahun_ajaran']) && $_POST['tahun_ajaran'] !== '' ? $_POST['tahun_ajaran'] : '';
        $semesterAktif = isset($_POST['semester']) && $_POST['semester'] !== '' ? $_POST['semester'] : '';
        $kelas_terpilih = isset($_POST['kelas_id']) ? $_POST['kelas_id'] : '';
        $tahunList = $db->query("SELECT * FROM tahun_ajaran")->fetchAll(PDO::FETCH_ASSOC);
        
        // Ambil semua siswa berdasarkan filter
        $querySiswa = "SELECT s.id, s.nama, s.nis, k.kelas, k.id AS kelas_id FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE 1=1";
        if (!empty($kelas_terpilih)) {
            $querySiswa .= " AND k.id = :kelas_id";
        }
        $querySiswa .= " ORDER BY s.nama";
        $stmtSiswa = $db->prepare($querySiswa);
        if (!empty($kelas_terpilih)) {
            $stmtSiswa->bindParam(':kelas_id', $kelas_terpilih);
        }
        $stmtSiswa->execute();
        $siswaList = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);
        
        // Ambil jumlah mapel
        $stmtMapel = $db->prepare("SELECT COUNT(*) FROM mata_pelajaran");
        $stmtMapel->execute();
        $jumlahMapel = $stmtMapel->fetchColumn();
        
        $tahunList = $db->query("SELECT * FROM tahun_ajaran")->fetchAll(PDO::FETCH_ASSOC);
    ?>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <a href="?page=tambah-nilai-rapor-uts" class="btn btn-success">Tambah Nilai</a>
                <a href="?page=impor-nilai-rapor-uts" class="btn btn-info">Impor Nilai</a>
                <a href="#" onclick="confirmDelete('?page=hapus-semua-nilai-rapor-uts')" class="btn btn-danger">Hapus Semua Nilai</a>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Tahun Ajaran</label>
                            <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Semua --</option>
                                <?php foreach ($tahunList as $ta): ?>
                                    <option value="<?= $ta['id'] ?>" <?= ($tahunAjaranAktif == $ta['id']) ? 'selected' : '' ?>><?= $ta['tahun_ajaran'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Semester</label>
                            <select name="semester" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Semua --</option>
                                <option value="1" <?= ($semesterAktif == '1') ? 'selected' : '' ?>>Semester 1</option>
                                <option value="2" <?= ($semesterAktif == '2') ? 'selected' : '' ?>>Semester 2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Kelas</label>
                            <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Semua --</option>
                                <?php
                                $kelas_result = $db->query("SELECT * FROM kelas");
                                while ($row = $kelas_result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($kelas_terpilih == $row['id']) ? "selected" : "";
                                    echo "<option value='{$row['id']}' $selected>{$row['kelas']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
                <hr>

                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Mapel Sudah diisi</th>
                            <th>Mapel Belum diisi</th>
                            <th>Semester</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($tahunAjaranAktif) && !empty($semesterAktif) && !empty($kelas_terpilih)): ?>
                    <?php
                        $querySiswa = "SELECT s.id, s.nama, s.nis, k.kelas, k.id AS kelas_id FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE k.id = :kelas_id ORDER BY s.nama";
                        $stmtSiswa = $db->prepare($querySiswa);
                        $stmtSiswa->bindParam(':kelas_id', $kelas_terpilih);
                        $stmtSiswa->execute();
                        $siswaList = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);

                        $stmtMapel = $db->prepare("SELECT COUNT(*) FROM mata_pelajaran");
                        $stmtMapel->execute();
                        $jumlahMapel = $stmtMapel->fetchColumn();

                        $stmtTahun = $db->prepare("SELECT tahun_ajaran FROM tahun_ajaran WHERE id = ?");
                        $stmtTahun->execute([$tahunAjaranAktif]);
                        $tahun = $stmtTahun->fetchColumn();
                    ?>

                    <?php $no = 1; foreach ($siswaList as $siswa): ?>
                    <?php
                        $stmtIsi = $db->prepare("SELECT COUNT(DISTINCT mata_pelajaran_id) FROM nilai_rapor WHERE siswa_id = ? AND tahun_ajaran_id = ? AND semester = ? AND nilai_uts != '' AND nilai_uts IS NOT NULL");
                        $stmtIsi->execute([$siswa['id'], $tahunAjaranAktif, $semesterAktif]);
                        $mapelDiisi = $stmtIsi->fetchColumn();

                        $mapelBelum = max(0, $jumlahMapel - $mapelDiisi);

                        $stmtTahun = $db->prepare("SELECT tahun_ajaran FROM tahun_ajaran WHERE id = ?");
                        $stmtTahun->execute([$tahunAjaranAktif]);
                        $tahun = $stmtTahun->fetchColumn();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($siswa['nama']) ?></td>
                        <td><?= htmlspecialchars($siswa['kelas']) ?></td>
                        <td><?= $mapelDiisi ?></td>
                        <td><?= $mapelBelum ?></td>
                        <td><?= htmlspecialchars($semesterAktif) ?></td>
                        <td><?= htmlspecialchars($tahun) ?></td>
                        <td>
                            <a href="#" onclick="printUts(<?= $siswa['id'] ?>, <?= $tahunAjaranAktif ?>, <?= $semesterAktif ?>)" class="btn btn-info"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</section>