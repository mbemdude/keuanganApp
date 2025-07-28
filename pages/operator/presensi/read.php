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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <a href="?page=tambah-presensi" class="btn btn-success">Tambah Presensi</a>
                            <a href="?page=impor-presensi" class="btn btn-info">Impor & Ekspor Presensi</a>
                            <!-- <a href="#" onclick="printPresensiAll()" class="btn btn-warning">Cetak Data <i class="bi bi-printer-fill"></i></a> -->
                            <a href="#" onclick="confirmDelete('?page=hapus-semua-presensi')" class="btn btn-danger">Hapus Data Presensi</a>
                        </div>
                        <form method="GET" class="ms-auto">
                            <label for="">Filter Kelas</label>
                            <input type="hidden" name="page" value="presensi">
                            <select name="kelas" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Filter Kelas --</option>
                                <?php
                                $kelas_result = $db->query("SELECT * FROM kelas");
                                while ($row = $kelas_result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = (isset($_GET['kelas']) && $_GET['kelas'] == $row['kelas']) ? "selected" : "";
                                    echo "<option value='{$row['id']}' $selected>{$row['kelas']}</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </div>

                    <div class="card-body">
                        <table id="myTable" class="display table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Alfa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

                                    $selectSql = "SELECT p.siswa_id, s.nama,
                                                    COUNT(CASE WHEN p.status = 'Hadir' THEN 1 END) AS jumlah_hadir,
                                                    COUNT(CASE WHEN p.status = 'Sakit' THEN 1 END) AS jumlah_sakit,
                                                    COUNT(CASE WHEN p.status = 'Izin' THEN 1 END) AS jumlah_izin,
                                                    COUNT(CASE WHEN p.status = 'Alfa' THEN 1 END) AS jumlah_alfa
                                                  FROM siswa s
                                                  LEFT JOIN presensi p ON s.id = p.siswa_id";

                                    // Tambahkan filter kelas jika dipilih
                                    if (!empty($kelas_filter)) {
                                        $selectSql .= " WHERE s.kelas_id = :kelas_id";
                                    }

                                    $selectSql .= " GROUP BY s.id, s.nama ORDER BY s.nama ASC";

                                    $stmt = $db->prepare($selectSql);

                                    // Bind parameter jika kelas dipilih
                                    if (!empty($kelas_filter)) {
                                        $stmt->bindParam(':kelas_id', $kelas_filter);
                                    }

                                    $stmt->execute();
                                    $no = 1;

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo $row['jumlah_hadir']; ?></td>
                                    <td><?php echo $row['jumlah_sakit']; ?></td>
                                    <td><?php echo $row['jumlah_izin']; ?></td>
                                    <td><?php echo $row['jumlah_alfa']; ?></td>
                                    <td>
                                        <a href="?page=show-presensi&siswa_id=<?php echo $row['siswa_id']; ?>" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Alfa</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>