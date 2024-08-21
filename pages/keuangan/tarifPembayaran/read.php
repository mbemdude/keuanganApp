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
            <a href="?page=tambah-tarif-pembayaran" class="btn btn-success">Tambah Data <i class="bi bi-plus-circle-fill"></i></a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="myTable" class="display table table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Jenjang</th>
                    <th>Tahun Ajaran</th>
                    <th>Uang Pangkal</th>
                    <th>Daftar Ulang</th>
                    <th>SPP</th>
                    <th>Tipe</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $database = new Database();
                    $db = $database->getConnection();
                    
                    $selectSql = "SELECT tp.id,
                                tp.jenjang_id,
                                tp.jenis_pembayaran_id,
                                tp.tahun_ajaran_id,
                                j.jenjang, 
                                ta.tahun_ajaran, 
                                tp.tipe, 
                                MAX(CASE WHEN tp.jenis_pembayaran_id = '1' THEN tp.nominal END) AS uang_pangkal, 
                                MAX(CASE WHEN tp.jenis_pembayaran_id = '2' THEN tp.nominal END) AS daftar_ulang, 
                                MAX(CASE WHEN tp.jenis_pembayaran_id = '3' THEN tp.nominal END) AS spp 
                                FROM tarif_pembayaran tp JOIN tahun_ajaran ta ON tp.tahun_ajaran_id = ta.id JOIN jenjang j ON tp.jenjang_id = j.id GROUP BY ta.tahun_ajaran, tp.tipe ORDER BY ta.tahun_ajaran ASC, tp.tipe ASC";
                    $stmt = $db->prepare($selectSql);
                    $stmt->execute();
                    $row_data = $stmt->rowCount();
    
                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                ?>
                <tr>
                    <th scope="row"><?php echo $no++ ?></th>
                    <td><?php echo $row['jenjang'] ?></td>
                    <td><?php echo $row['tahun_ajaran'] ?></td>
                    <td><?php echo rupiah($row['uang_pangkal']) ?></td>
                    <td><?php echo rupiah($row['daftar_ulang']) ?></td>
                    <td><?php echo rupiah($row['spp']) ?></td>
                    <td><?php echo $row['tipe'] ?></td>
                    <td>
                        <a href="?page=show-tarif-pembayaran&id=<?php echo $row['id']?>" class="btn btn-info"><i class="bi bi-eye"></i></a>
                        <a href="?page=edit-tarif-pembayaran&jenjang_id=<?php echo $row['jenjang_id']?>&tahun_ajaran_id=<?php echo $row['tahun_ajaran_id']?>&tipe=<?php echo $row['tipe']?>" class="btn btn-warning"><i class="bi bi-pen"></i></a>
                        <a href="?page=hapus-tarif-pembayaran&jenjang_id=<?php echo $row['jenjang_id']?>&tahun_ajaran_id=<?php echo $row['tahun_ajaran_id']?>&tipe=<?php echo $row['tipe']?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>Jenjang</th>
                    <th>Tahun Ajaran</th>
                    <th>Uang Pangkal</th>
                    <th>Daftar Ulang</th>
                    <th>SPP</th>
                    <th>Tipe</th>
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