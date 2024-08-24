<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    // Array untuk menyimpan data tagihan
    $tagihanData = [
        ['tarif_pembayaran_id' => 1, 'jumlah_tagihan' => $_POST['jumlah_tagihan1']],
        ['tarif_pembayaran_id' => 2, 'jumlah_tagihan' => $_POST['jumlah_tagihan2']],
        ['tarif_pembayaran_id' => 3, 'jumlah_tagihan' => $_POST['jumlah_tagihan3']],
    ];

    // Prepare the insert statement
    $insertSql = "INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan) 
                  VALUES (:siswa_id, :tarif_pembayaran_id, :tanggal_tagihan, :jumlah_tagihan)";
    $stmt = $db->prepare($insertSql);

    // Iterasi melalui setiap tagihan dan lakukan insert
    foreach ($tagihanData as $data) {
        // Bind parameters
        $stmt->bindParam(':siswa_id', $_POST['siswa_id']);
        $stmt->bindParam(':tarif_pembayaran_id', $data['tarif_pembayaran_id']);
        $stmt->bindParam(':tanggal_tagihan', $_POST['tanggal_tagihan']);
        $stmt->bindParam(':jumlah_tagihan', $data['jumlah_tagihan']);

        // Execute the statement and check if successful
        if (!$stmt->execute()) {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal simpan data";
            echo "<meta http-equiv='refresh' content='0;url=?page=tagihan-siswa'>";
            exit();
        }
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil simpan data";
    echo "<meta http-equiv='refresh' content='0;url=?page=tagihan-siswa'>";
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
            $selectTarifSQL = "
                SELECT 
                    tp.tipe, 
                    ta.tahun_ajaran, 
                    j.jenjang, 
                    GROUP_CONCAT(CONCAT_WS(':', tp.jenis_pembayaran_id, tp.nominal)) AS pembayaran 
                FROM tarif_pembayaran tp
                JOIN tahun_ajaran ta ON tp.tahun_ajaran_id = ta.id
                JOIN jenjang j ON tp.jenjang_id = j.id
                GROUP BY tp.tipe, ta.tahun_ajaran, j.jenjang ORDER BY ta.tahun_ajaran, j.jenjang ASC";
            $stmtTarif = $db->prepare($selectTarifSQL);
            $stmtTarif->execute();

            while ($rowTarif = $stmtTarif->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='{$rowTarif['tipe']}' data-pembayaran='{$rowTarif['pembayaran']}'>
                        Tipe {$rowTarif['tipe']} | Tahun Ajaran {$rowTarif['tahun_ajaran']} | {$rowTarif['jenjang']}
                      </option>";
            }
            ?>
        </select>

        <label for="tanggal_tagihan">Tanggal Tagihan</label>
        <input type="date" name="tanggal_tagihan" class="form-control">

        <label for="jumlah_tagihan1">Jumlah Tagihan Uang Pangkal</label>
        <input type="text" name="jumlah_tagihan1" id="jumlah_tagihan1" class="form-control" readonly>

        <label for="jumlah_tagihan2">Jumlah Tagihan Daftar Ulang</label>
        <input type="text" name="jumlah_tagihan2" id="jumlah_tagihan2" class="form-control" readonly>

        <label for="jumlah_tagihan3">Jumlah Tagihan SPP</label>
        <input type="text" name="jumlah_tagihan3" id="jumlah_tagihan3" class="form-control" readonly>
    </div>
    
    <div class="mt-2">
        <a href="?page=tagihan-siswa" class="btn btn-danger">Batal</a>
        <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
    </div>
</form>

<script>
document.getElementById('tarif_pembayaran_id').addEventListener('change', function() {
    var pembayaran = this.options[this.selectedIndex].getAttribute('data-pembayaran');
    var pembayaranArray = pembayaran.split(',');

    // Reset all fields before setting new values
    document.getElementById('jumlah_tagihan1').value = '';
    document.getElementById('jumlah_tagihan2').value = '';
    document.getElementById('jumlah_tagihan3').value = '';

    pembayaranArray.forEach(function(item) {
        var parts = item.split(':');
        var jenis = parts[0];
        var nominal = parts[1];

        if (jenis == 1) { // Uang Pangkal
            document.getElementById('jumlah_tagihan1').value = nominal;
        } else if (jenis == 2) { // Daftar Ulang
            document.getElementById('jumlah_tagihan2').value = nominal;
        } else if (jenis == 3) { // SPP
            document.getElementById('jumlah_tagihan3').value = nominal;
        }
    });
});
</script>
