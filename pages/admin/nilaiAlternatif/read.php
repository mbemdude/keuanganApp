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
                <a href="?page=generate-nilai-alternatif" class="btn btn-success">Generate Data <i class="bi bi-plus-circle-fill"></i></a>
                <a href="#" onclick="confirmDelete('?page=hapus-semua-nliai-alternatif')" class="btn btn-danger">Hapus Semua Data <i class="bi bi-trash-fill"></i></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="myTable" class="display table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>C1</th>
                      <th>C2</th>
                      <th>C3</th>
                      <th>C4</th>
                      <th>C5</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $database = new Database();
                      $db = $database->getConnection();
                      
                      $selectSql = "SELECT na.*, s.nama,
                      MAX(CASE WHEN na.kriteria_id = '1' THEN na.nilai END) AS c1,
                      MAX(CASE WHEN na.kriteria_id = '2' THEN na.nilai END) AS c2,
                      MAX(CASE WHEN na.kriteria_id = '3' THEN na.nilai END) AS c3,
                      MAX(CASE WHEN na.kriteria_id = '4' THEN na.nilai END) AS c4,
                      MAX(CASE WHEN na.kriteria_id = '5' THEN na.nilai END) AS c5
                      FROM nilai_alternatif na JOIN siswa s ON na.siswa_id = s.id JOIN kriteria k ON na.kriteria_id = k.id GROUP BY na.siswa_id";
                      $stmt = $db->prepare($selectSql);
                      $stmt->execute();
                      $row_data = $stmt->rowCount();
      
                      $no = 1;
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                    ?>
                    <tr>
                      <th scope="row"><?php echo $no++ ?></th>
                      <td><?php echo$row['nama'] ?></td>
                      <td><?php echo$row['c1'] ?></td>
                      <td><?php echo$row['c2'] ?></td>
                      <td><?php echo$row['c3'] ?></td>
                      <td><?php echo$row['c4'] ?></td>
                      <td><?php echo$row['c5'] ?></td>
                      <td>
                        <a href="?page=edit-kriteria&id=<?php echo $row['id'] ?>" class="btn btn-warning"><i class="bi bi-pen"></i></a>
                        <a href="#" onclick="confirmDelete('?page=hapus-nilai-alternatif&id=<?php echo $row['id'] ?>')" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>C1</th>
                      <th>C2</th>
                      <th>C3</th>
                      <th>C4</th>
                      <th>C5</th>
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