<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    // Array untuk menyimpan data tagihan
    $tagihanData = [
        ['jenis_pembayaran_id' => 1, 'jumlah_tagihan' => $_POST['jumlah_tagihan1']],
        ['jenis_pembayaran_id' => 2, 'jumlah_tagihan' => $_POST['jumlah_tagihan2']],
        ['jenis_pembayaran_id' => 3, 'jumlah_tagihan' => $_POST['jumlah_tagihan3']],
    ];

    // Ambil tarif_pembayaran_id berdasarkan tipe dan jenis_pembayaran_id
    $tarifQuery = "
        SELECT id AS tarif_pembayaran_id, jenis_pembayaran_id 
        FROM tarif_pembayaran 
        WHERE tipe = :tipe AND tahun_ajaran_id = :tahun_ajaran_id AND jenis_pembayaran_id = :jenis_pembayaran_id
    ";
    $tarifStmt = $db->prepare($tarifQuery);

    // Query untuk insert data
    $insertSql = "INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan) 
                  VALUES (:siswa_id, :tarif_pembayaran_id, :tanggal_tagihan, :jumlah_tagihan)";
    $stmt = $db->prepare($insertSql);

    foreach ($tagihanData as $data) {
        // Cari tarif_pembayaran_id berdasarkan jenis pembayaran
        $tarifStmt->bindParam(':tipe', $_POST['tarif_pembayaran_tipe']);
        $tarifStmt->bindParam(':tahun_ajaran_id', $_POST['tahun_ajaran_id']);
        $tarifStmt->bindParam(':jenis_pembayaran_id', $data['jenis_pembayaran_id']);
        $tarifStmt->execute();

        $tarifResult = $tarifStmt->fetch(PDO::FETCH_ASSOC);
        if (!$tarifResult) {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Tarif pembayaran tidak ditemukan untuk jenis pembayaran ID: " . $data['jenis_pembayaran_id'];
            echo "<meta http-equiv='refresh' content='0;url=?page=tagihan-siswa'>";
            exit();
        }

        $tarif_pembayaran_id = $tarifResult['tarif_pembayaran_id'];

        // Bind parameter untuk insert
        $stmt->bindParam(':siswa_id', $_POST['siswa_id']);
        $stmt->bindParam(':tarif_pembayaran_id', $tarif_pembayaran_id);
        $stmt->bindParam(':tanggal_tagihan', $_POST['tanggal_tagihan']);
        $stmt->bindParam(':jumlah_tagihan', $data['jumlah_tagihan']);

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

        <label for="tarif_pembayaran_tipe">Tipe Pembayaran</label>
        <select name="tarif_pembayaran_tipe" id="tarif_pembayaran_tipe" class="form-select">
            <option value="">- Pilih -</option>
            <?php 
            $selectTarifSQL = "
                SELECT 
                    tp.tipe, 
                    ta.id AS tahun_ajaran_id, 
                    ta.tahun_ajaran, 
                    j.jenjang, 
                    GROUP_CONCAT(CONCAT_WS(':', tp.jenis_pembayaran_id, tp.nominal)) AS pembayaran 
                FROM tarif_pembayaran tp
                JOIN tahun_ajaran ta ON tp.tahun_ajaran_id = ta.id
                JOIN jenjang j ON tp.jenjang_id = j.id
                GROUP BY tp.tipe, ta.id, ta.tahun_ajaran, j.jenjang 
                ORDER BY ta.tahun_ajaran, j.jenjang ASC";
            $stmtTarif = $db->prepare($selectTarifSQL);
            $stmtTarif->execute();

            while ($rowTarif = $stmtTarif->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='{$rowTarif['tipe']}' data-tahun-ajaran='{$rowTarif['tahun_ajaran_id']}' data-pembayaran='{$rowTarif['pembayaran']}'>
                        Tipe {$rowTarif['tipe']} | Tahun Ajaran {$rowTarif['tahun_ajaran']} | {$rowTarif['jenjang']}
                      </option>";
            }
            ?>
        </select>

        <input type="hidden" name="tahun_ajaran_id" id="tahun_ajaran_id">

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
document.getElementById('tarif_pembayaran_tipe').addEventListener('change', function() {
    var pembayaran = this.options[this.selectedIndex].getAttribute('data-pembayaran');
    var tahunAjaranId = this.options[this.selectedIndex].getAttribute('data-tahun-ajaran');
    document.getElementById('tahun_ajaran_id').value = tahunAjaranId;

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
