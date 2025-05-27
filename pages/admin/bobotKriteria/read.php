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

    <?php 
      $matriksPerbandingan = $_SESSION['matriks_perbandingan'] ?? null;
      $matriksNormalisasi = $_SESSION['matriks_normalisasi'] ?? null;
    ?>

    <!-- Content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <a href="?page=tambah-bobot-kriteria" class="btn btn-success">Generate Data <i class="bi bi-plus-circle-fill"></i></a>
                <a href="#" onclick="confirmDelete('?page=hapus-semua-bobot-kriteria')" class="btn btn-danger">Hapus Semua Data <i class="bi bi-trash-fill"></i></a>
              </div>
              <div class="card-body">
                <table id="myTable" class="display table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kriteria</th>
                      <th>Bobot</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $database = new Database();
                      $db = $database->getConnection();
                      
                      $selectSql = "SELECT bka.*, k.nama_kriteria FROM bobot_kriteria_ahp bka JOIN kriteria k ON bka.kriteria_id = k.id";
                      $stmt = $db->prepare($selectSql);
                      $stmt->execute();
                      $row_data = $stmt->rowCount();
      
                      $no = 1;
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                    ?>
                    <tr>
                      <th scope="row"><?php echo $no++ ?></th>
                      <td><?php echo$row['nama_kriteria'] ?></td>
                      <td><?php echo number_format($row['bobot'], 3) ?></td>
                      <td>
                        <a href="#" onclick="confirmDelete('?page=hapus-bobot-kriteria&id=<?php echo $row['id'] ?>')" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Kriteria</th>
                      <th>Bobot</th>
                      <th>Aksi</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-body">
                <table class="display table table-sctriped">
                  <thead>
                    <tr>
                      <th scope="col">Matriks Perbandingan</th>
                      <th>K1</th>
                      <th>K2</th>
                      <th>K3</th>
                      <th>K4</th>
                      <th>K5</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if (isset($matriksPerbandingan)) {
                      foreach ($matriksPerbandingan as $i => $row) {
                        echo "<tr><th>K" . ($i + 1) . "</th>";
                        foreach ($row as $val) {
                          echo "<td>" . number_format($val, 3) . "</td>";
                        }
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card mt-2">
              <div class="card-body">
                <table class="display table table-sctriped">
                  <thead>
                    <tr>
                      <th scope="col">Matriks Normalisasi</th>
                      <th>K1</th>
                      <th>K2</th>
                      <th>K3</th>
                      <th>K4</th>
                      <th>K5</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if (isset($matriksNormalisasi)) {
                      foreach ($matriksNormalisasi as $i => $row) {
                        echo "<tr><th>K" . ($i + 1) . "</th>";
                        foreach ($row as $val) {
                          echo "<td>" . number_format($val, 3) . "</td>";
                        }
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>