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
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <h5>Gagal</h5>
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
                <!-- <a href="?page=tambah-transaksi" class="btn btn-success">Tambah Data <i class="bi bi-plus-circle-fill"></i></a> -->
                <a href="?page=kasir" class="btn btn-primary">Kasir <i class="bi bi-cart4"></i></a>
                <a href="?page=export-transaksi" class="btn btn-success">Export Data <i class="bi bi-database-fill-up"></i></a>
                <a href="#" onclick="confirmDelete('?page=hapus-semua-transaksi')" class="btn btn-danger">Hapus Semua <i class="bi bi-trash"></i></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="myTable" class="display table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal Transaksi</th>
                      <th>Nama</th>
                      <th>Barang</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Subtotal</th>
                      <th>Petugas</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $database = new Database();
                      $db = $database->getConnection();
                      
                      $selectSql = "SELECT t.*, s.nama AS nama_siswa, b.nama_barang, b.harga, u.nama AS petugas FROM transaksi t LEFT JOIN uang_saku us ON t.uang_saku_id = us.id LEFT JOIN siswa s ON us.siswa_id = s.id LEFT JOIN barang b ON t.barang_id = b.id LEFT JOIN users u ON t.user_id = u.id";
                      $stmt = $db->prepare($selectSql);
                      $stmt->execute();
                      $row_data = $stmt->rowCount();
      
                      $no = 1;
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                    ?>
                    <tr>
                      <th scope="row"><?php echo $no++ ?></th>
                      <td><?php echo $row['tanggal'] ?></td>
                      <td><?php echo $row['nama_siswa'] ?></td>
                      <td><?php echo $row['nama_barang'] ?></td>
                      <td><?php echo $row['jumlah'] ?></td>
                      <td><?php echo $row['harga'] ?></td>
                      <td><?php echo $row['harga'] * $row['jumlah'] ?></td>
                      <td><?php echo $row['petugas'] ?></td>
                      <td>
                        <a href="?page=edit-transaksi&id=<?php echo $row['id'] ?>" class="btn btn-warning"><i class="bi bi-pen"></i></a>
                        <a href="#" onclick="confirmDelete('?page=hapus-transaksi&id=<?php echo $row['id'] ?>')" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Tanggal Transaksi</th>
                      <th>Nama</th>
                      <th>Barang</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Subtotal</th>
                      <th>Petugas</th>
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