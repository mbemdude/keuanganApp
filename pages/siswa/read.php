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
                <a href="?page=tambah-siswa" class="btn btn-success">Tambah Data <i class="bi bi-plus-circle-fill"></i></a>
                <a href="?page=import-siswa" class="btn btn-info">Import & Export Data <i class="bi bi-database-fill-gear"></i></a>
                <a href="?page=hapus-semua-siswa" class="btn btn-danger">Hapus Semua Data <i class="bi bi-trash-fill"></i></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="myTable" class="display table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>NIS</th>
                      <th>Nama</th>
                      <th>Kelas</th>
                      <th>Jenjang</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $database = new Database();
                      $db = $database->getConnection();
                      
                      $selectSql = "SELECT s.*, k.kelas, j.jenjang FROM siswa s LEFT JOIN jenjang j ON S.jenjang_id=J.id JOIN kelas k ON s.kelas_id=k.id";
                      $stmt = $db->prepare($selectSql);
                      $stmt->execute();
                      $row_data = $stmt->rowCount();
      
                      $no = 1;
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                    ?>
                    <tr>
                      <th scope="row"><?php echo $no++ ?></th>
                      <td><?php echo$row['kode'] ?></td>
                      <td><?php echo$row['nis'] ?></td>
                      <td><?php echo$row['nama'] ?></td>
                      <td><?php echo$row['kelas'] ?></td>
                      <td><?php echo$row['jenjang'] ?></td>
                      <td>
                        <a href="?page=show-siswa&id=<?php echo $row['id'] ?>" class="btn btn-info"><i class="bi bi-eye"></i></a>
                        <a href="?page=edit-siswa&id=<?php echo $row['id'] ?>" class="btn btn-warning"><i class="bi bi-pen"></i></a>
                        <a href="?page=hapus-siswa&id=<?php echo $row['id'] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>NIS</th>
                      <th>Nama</th>
                      <th>Kelas</th>
                      <th>Jenjang</th>
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