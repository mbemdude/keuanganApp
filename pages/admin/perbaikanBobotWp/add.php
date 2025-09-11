<?php
$database = new Database();
$db = $database->getConnection();

try {
    // 1. Ambil bobot asli dari tabel kriteria
    $sql = "SELECT id, bobot FROM kriteria ORDER BY id ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        die("Data kriteria kosong.");
    }

    // 2. Hitung total bobot
    $totalBobot = array_sum(array_column($rows, 'bobot'));
    if ($totalBobot == 0) {
        die("Total bobot = 0, perhitungan tidak bisa dilakukan.");
    }

    // 3. Kosongkan tabel perbaikan_bobot dulu (supaya tidak dobel)
    $db->exec("TRUNCATE TABLE perbaikan_bobot");

    // 4. Generate bobot normalisasi & simpan ke tabel
    $insert = $db->prepare("INSERT INTO perbaikan_bobot (kriteria_id, bobot_normalisasi) VALUES (:kriteria_id, :bobot_normalisasi)");

    foreach ($rows as $r) {
        $bobotNormal = $r['bobot'] / $totalBobot;

        $insert->execute([
            ':kriteria_id' => $r['id'],
            ':bobot_normalisasi' => $bobotNormal
        ]);
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Data berhasil di generate";
    echo "<meta http-equiv='refresh' content='0;url=?page=perbaikan-bobot-wp'>";
    exit();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
