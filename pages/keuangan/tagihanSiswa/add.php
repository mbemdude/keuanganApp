<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    // Prepare the insert statement
    $insertSql = "INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan, tunggakan, tanggal_tunggakan) VALUES (:siswa_id, :tarif_pembayaran_id, :tanggal_tagihan, :jumlah_tagihan, :tunggakan, :tanggal_tunggakan)";
    $stmt = $db->prepare($insertSql);
    
    // Bind parameters
    $stmt->bindParam(':siswa_id', $_POST['siswa_id']);
    $stmt->bindParam(':tarif_pembayaran_id', $_POST['tarif_pembayaran_id']);
    $stmt->bindParam(':tanggal_tagihan', $_POST['tanggal_tagihan']);
    $stmt->bindParam(':jumlah_tagihan', $_POST['jumlah_tagihan']);
    $stmt->bindParam(':tunggakan', $_POST['tunggakan']);
    $stmt->bindParam(':tanggal_tunggakan', $_POST['tanggal_tunggakan']);
    
    // Execute the statement and handle result
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil simpan data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal simpan data";
    }
    header("Location: ?page=tagihan-siswa");
    exit();
}
?>


<form method="POST" id="form-tagihan">
    <div class="form-group">
        <label for="siswa_id">Nama</label>
        <select name="siswa_id" class="form-select">
            <option value="">- Pilih -</option>
            <?php 
            $database = new Database();
            $db = $database->getConnection();

            $selectSiswaSQL = "SELECT * FROM siswa";
            $stmtSiswa = $db->prepare($selectSiswaSQL);
            $stmtSiswa->execute();

            while ($rowSiswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='{$rowSiswa['id']}'>{$rowSiswa['nama']}</option>";
            }
            ?>
        </select>
        <label for="tarif_pembayaran_id">Jenis Pembayaran</label>
        <select name="tarif_pembayaran_id" id="tarif_pembayaran_id" class="form-select">
            <option value="">- Pilih -</option>
            <?php 
            $selectTarifSQL = "SELECT * FROM tarif_pembayaran";
            $stmtTarif = $db->prepare($selectTarifSQL);
            $stmtTarif->execute();

            while ($rowTarif = $stmtTarif->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='{$rowTarif['id']}' data-nominal='{$rowTarif['nominal']}'>{$rowTarif['tipe']} | {$rowTarif['jenis_pembayaran_id']}</option>";
            }
            ?>
        </select>
        <label for="tanggal_tagihan">Tanggal Tagihan</label>
        <input type="date" name="tanggal_tagihan" class="form-control">
        <label for="jumlah_tagihan">Jumlah Tagihan</label>
        <input type="text" name="jumlah_tagihan" id="jumlah_tagihan" class="form-control" readonly>
        <label for="tunggakan">Tunggakan</label>
        <input type="text" name="tunggakan" class="form-control">
        <label for="tanggal_tunggakan">Tanggal Tunggakan</label>
        <input type="date" name="tanggal_tunggakan" class="form-control">
    </div>
    <div class="mt-2">
        <a href="?page=tagihan-siswa" class="btn btn-danger">Batal</a>
        <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
    </div>
</form>

<script>
document.getElementById('tarif_pembayaran_id').addEventListener('change', function() {
    var nominal = this.options[this.selectedIndex].getAttribute('data-nominal');
    document.getElementById('jumlah_tagihan').value = nominal;
});
</script>
