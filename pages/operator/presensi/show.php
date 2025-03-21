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
                        <a href="?page=presensi" class="btn btn-success">Kembali</a>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $tampilSiswa = isset($_GET['siswa_id']) ? $_GET['siswa_id'] : '';
                                    $selectSql = "SELECT p.*, s.nama, mp.mata_pelajaran 
                                                  FROM presensi p 
                                                  JOIN siswa s ON p.siswa_id = s.id 
                                                  JOIN mata_pelajaran mp ON p.mata_pelajaran_id = mp.id 
                                                  WHERE p.siswa_id = :siswa_id";

                                    $stmt = $db->prepare($selectSql);
                                    $stmt->bindParam(':siswa_id', $tampilSiswa);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        $no = 1;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                                    <td>
                                        <a href="?page=edit-presensi&id=<?php echo $row['id']; ?>" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
                                        <a href="#" onclick="confirmDelete('?page=hapus-presensi&id=<?php echo $row['id']; ?>')" class="btn btn-danger"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Data Presensi Masih Kosong</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
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
