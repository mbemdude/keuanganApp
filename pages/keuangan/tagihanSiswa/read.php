    <?php 
        if (isset($_SESSION["hasil"])) {
            if ($_SESSION["hasil"]) {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <h5>Berhasil</h5>
          <?php echo $_SESSION['pesan'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php 
        } else {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <h5>Berhasil</h5>
          <?php echo $_SESSION['pesan'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
        }
            unset($_SESSION['hasil']);
            unset($_SESSION['pesan']);
        }
    ?>
<!-- Content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <a href="?page=tambah-tagihan-siswa" class="btn btn-success">Tambah Data <i class="bi bi-plus-circle-fill"></i></a>
            <a href="#" onclick="confirmDelete('?page=hapus-semua-tagihan-siswa')"" class="btn btn-danger">Hapus Semua Data <i class="bi bi-trash-fill"></i></a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="myTable" class="display table table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Total Tagihan</th>
                    <th>Tipe Pembayaran</th>
                    <th>Tanggal Tagihan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $database = new Database();
                    $db = $database->getConnection();
                    
                    $selectSql = "SELECT ts.siswa_id, s.nama, SUM(ts.jumlah_tagihan) AS total_tagihan, tp.tipe, ts.tanggal_tagihan FROM tagihan_siswa ts JOIN siswa s ON ts.siswa_id = s.id JOIN tarif_pembayaran tp ON ts.tarif_pembayaran_id = tp.id GROUP BY s.nama ORDER BY s.nama ASC, ts.tanggal_tagihan DESC";
                    $stmt = $db->prepare($selectSql);
                    $stmt->execute();
                    $row_data = $stmt->rowCount();
    
                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                ?>
                <tr>
                    <th scope="row"><?php echo $no++ ?></th>
                    <td><?php echo $row['nama'] ?></td>
                    <td><?php echo rupiah($row['total_tagihan']) ?></td>
                    <td><?php echo $row['tipe'] ?></td>
                    <td><?php echo $row['tanggal_tagihan'] ?></td>
                    <td>
                    <a href="?page=show-pembayaran&siswa_id=<?php echo $row['siswa_id']?>" class="btn btn-info"><i class="bi bi-eye"></i></a>
                    <a href="?page=edit-pembayaran&siswa_id=<?php echo $row['siswa_id']?>" class="btn btn-warning"><i class="bi bi-pen"></i></a>
                    <a href="#" onclick="confrimDelete('?page=hapus-tagihan-siswa&siswa_id=<?php echo $row['siswa_id']?>')" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Total Tagihan</th>
                    <th>Tipe Pembayaran</th>
                    <th>Tanggal Tagihan</th>
                    <th>Aksi</th>
                </tr>
                </tfoot>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>