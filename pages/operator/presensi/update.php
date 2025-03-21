<?php 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $findSql = "SELECT p.id, p.status, p.siswa_id, s.nama FROM presensi p 
                JOIN siswa s ON p.siswa_id = s.id 
                WHERE p.id = :id";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row) {
        if (isset($_POST['button_update'])) {
            $updateSql = "UPDATE presensi SET status = :status WHERE id = :id";
            $stmt = $db->prepare($updateSql);
            $stmt->bindParam(':status', $_POST['status']);
            $stmt->bindParam(':id', $_POST['id']);

            if ($stmt->execute()) {
                $_SESSION['hasil'] = true;
                $_SESSION['pesan'] = "Presensi berhasil diperbarui!";
            } else {
                $_SESSION['hasil'] = false;
                $_SESSION['pesan'] = "Gagal memperbarui presensi.";
            }
            echo "<meta http-equiv='refresh' content='0;url=?page=show-presensi&siswa_id={$row['siswa_id']}'>";
            exit();
        }
        ?>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Presensi</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">

                        <!-- 🔹 Menampilkan Nama Siswa -->
                        <div class="mb-3">
                            <label class="form-label"><strong>Nama Siswa:</strong></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($row['nama']); ?></p>
                        </div>

                        <!-- 🔹 Pilihan Status (Radio Button) -->
                        <div class="mb-3">
                            <label for="status"><strong>Status Presensi:</strong></label>
                            <div class="d-flex gap-3 mt-2">
                                <input type="radio" name="status" value="Hadir" <?= ($row['status'] == 'Hadir') ? 'checked' : '' ?>> Hadir
                                <input type="radio" name="status" value="Sakit" <?= ($row['status'] == 'Sakit') ? 'checked' : '' ?>> Sakit
                                <input type="radio" name="status" value="Izin" <?= ($row['status'] == 'Izin') ? 'checked' : '' ?>> Izin
                                <input type="radio" name="status" value="Alfa" <?= ($row['status'] == 'Alfa') ? 'checked' : '' ?>> Alfa
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="?page=show-presensi&siswa_id=<?= $row['siswa_id']; ?>" class="btn btn-danger">Batal</a>
                            <button type="submit" name="button_update" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=presensi'>";
    }
}
?>