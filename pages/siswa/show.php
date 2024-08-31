<?php 
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    // Find data
    $id = $_GET['id'];
    $findSql = "SELECT * FROM siswa WHERE id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if (isset($row['id'])) {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/foto_siswa/";
            $fileName = $row['nama'] . ".jpg"; // Save as [nama].png
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Allow only PNG, JPG, JPEG
            $allowTypes = ['png', 'jpg', 'jpeg'];
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    // Update foto in database
                    $updateFotoSql = "UPDATE siswa SET foto = :foto WHERE id = :id";
                    $stmtUpdateFoto = $db->prepare($updateFotoSql);
                    $stmtUpdateFoto->bindParam(':foto', $fileName);
                    $stmtUpdateFoto->bindParam(':id', $id);
                    $stmtUpdateFoto->execute();

                    echo "<div class='alert alert-success'>Gambar berhasil diubah!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Gagal mengupload gambar!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Format gambar tidak valid! Gunakan PNG, JPG, atau JPEG.</div>";
            }
        }

        if (isset($_POST['button_update'])) {
            // Update Query
            $updateSql = "UPDATE siswa SET kode = :kode, nis = :nis, nama = :nama, alamat = :alamat, jenis_kelamin = :jenis_kelamin, jenjang_id = :jenjang_id, kelas_id = :kelas_id, status_id = :status_id WHERE id = :id";
            $stmt = $db->prepare($updateSql);
            $stmt->bindParam(':kode', $_POST['kode']);
            $stmt->bindParam(':nis', $_POST['nis']);
            $stmt->bindParam(':nama', $_POST['nama']);
            $stmt->bindParam(':alamat', $_POST['alamat']);
            $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
            $stmt->bindParam(':jenjang_id', $_POST['jenjang_id']);
            $stmt->bindParam(':kelas_id', $_POST['kelas_id']);
            $stmt->bindParam(':status_id', $_POST['status_id']);
            $stmt->bindParam(':id', $_POST['id']);

            if ($stmt->execute()) {
                $_SESSION['hasil'] = true;
                $_SESSION['pesan'] = "Berhasil simpan data";
            } else {
                $_SESSION['hasil'] = false;
                $_SESSION['pesan'] = "Gagal simpan data";
            }
            echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
        }
        ?>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Siswa</h3>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-5 col-sm-4">
                                <div class="image-container position-relative">
                                    <?php 
                                    // Cek jika foto ada, tampilkan foto, jika tidak tampilkan placeholder
                                    $fotoPath = 'assets/foto_siswa/' . $row['foto'];
                                    $placeholderPath = 'assets/image/placeholder.png';
                                    if (!empty($row['foto']) && file_exists($fotoPath)) {
                                        echo "<img src='$fotoPath' alt='{$row['nama']}' class='img-fluid siswa-img'>";
                                    } else {
                                        echo "<img src='$placeholderPath' alt='Placeholder Image' class='img-fluid siswa-img'>";
                                    }
                                    ?>
                                    <!-- Tombol Ganti Gambar -->
                                    <button type="button" class="btn btn-primary btn-change-image" onclick="document.getElementById('uploadImage').click();">
                                        Ganti Gambar
                                    </button>
                                    <!-- Input File untuk Upload Gambar -->
                                    <input type="file" id="uploadImage" name="image" style="display: none;" accept="image/*" onchange="this.form.submit()">
                                </div>
                            </div>
                            <div class="col-lg-7 col-sm-8">
                                <!-- Form untuk data siswa -->
                                <div class="form-group">
                                    <label for="kode">Kode</label>
                                    <input type="text" name="kode" class="form-control" value="<?= $row['kode'] ?>">
                                    <label for="nis">NIS</label>
                                    <input type="text" name="nis" class="form-control" value="<?= $row['nis'] ?>">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="<?= $row['alamat'] ?>">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="">- Pilih -</option>
                                        <option value="Laki-laki" <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Perempuan" <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                    <label for="jenjang_id">Jenjang</label>
                                    <select name="jenjang_id" class="form-select">
                                        <option value="">- Pilih -</option>
                                        <?php 
                                        $selectJenjangSQL = "SELECT * FROM jenjang";
                                        $stmtJenjang = $db->prepare($selectJenjangSQL);
                                        $stmtJenjang->execute();

                                        while($rowJenjang = $stmtJenjang->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($rowJenjang['id'] == $row['jenjang_id']) ? 'selected' : '';
                                            echo "<option value=\"" . $rowJenjang['id'] . "\" $selected>" . $rowJenjang['jenjang'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="kelas_id">Kelas</label>
                                    <select name="kelas_id" class="form-select">
                                        <option value="">- Pilih -</option>
                                        <?php 
                                        $selectKelasSQL = "SELECT * FROM kelas";
                                        $stmtKelas = $db->prepare($selectKelasSQL);
                                        $stmtKelas->execute();

                                        while($rowKelas = $stmtKelas->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($rowKelas['id'] == $row['kelas_id']) ? 'selected' : '';
                                            echo "<option value=\"" . $rowKelas['id'] . "\" $selected>" . $rowKelas['kelas'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="status_id">Status</label>
                                    <select name="status_id" class="form-select">
                                        <option value="">- Pilih -</option>
                                        <?php 
                                        $selectStatusSQL = "SELECT * FROM status";
                                        $stmtStatus = $db->prepare($selectStatusSQL);
                                        $stmtStatus->execute();

                                        while($rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($rowStatus['id'] == $row['status_id']) ? 'selected' : '';
                                            echo "<option value=\"" . $rowStatus['id'] . "\" $selected>" . $rowStatus['status'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                </div>
                            </div>
                        </div>
                        <a href="?page=siswa" class="btn btn-danger btn-sm float-right">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" name="button_update" class="btn btn-success btn-sm float-right">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    }
}
?>

<script>
    // JavaScript untuk handle hover button Ganti Gambar
    document.querySelectorAll('.image-container').forEach(container => {
        const btnChangeImage = container.querySelector('.btn-change-image');
        container.addEventListener('mouseenter', () => {
            btnChangeImage.style.display = 'block';
        });
        container.addEventListener('mouseleave', () => {
            btnChangeImage.style.display = 'none';
        });
    });
</script>