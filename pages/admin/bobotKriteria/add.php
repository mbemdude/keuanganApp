<?php
// Ambil semua kriteria
$stmtKriteria = $db->prepare("SELECT * FROM kriteria ORDER BY id");
$stmtKriteria->execute();
$dataKriteria = $stmtKriteria->fetchAll(PDO::FETCH_ASSOC);

// Mapping kriteria
$kriteria = $dataKriteria;
$n = count($kriteria);
$map = array_column($kriteria, 'id'); // [id1, id2, ...]
$kmap = array_flip($map); // [id1 => 0, id2 => 1, ...]

// Ambil semua data perbandingan
$stmtPerbandingan = $db->prepare("SELECT * FROM perbandingan_kriteria");
$stmtPerbandingan->execute();
$dataPerbandingan = $stmtPerbandingan->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi matriks
$matrix = array_fill(0, $n, array_fill(0, $n, 1));

foreach ($dataPerbandingan as $row) {
    $i = array_search($row['kriteria_id_1'], $map);
    $j = array_search($row['kriteria_id_2'], $map);
    $matrix[$i][$j] = $row['nilai_perbandingan'];
    $matrix[$j][$i] = 1 / $row['nilai_perbandingan'];
}

// Hitung total kolom
$col_sum = array_fill(0, $n, 0);
for ($j = 0; $j < $n; $j++) {
    for ($i = 0; $i < $n; $i++) {
        $col_sum[$j] += $matrix[$i][$j];
    }
}

// Normalisasi dan hitung bobot
$bobot = [];
$normalisasi = [];
$matriks_asli = $matrix;

for ($i = 0; $i < $n; $i++) {
    $total = 0;
    for ($j = 0; $j < $n; $j++) {
        $matrix[$i][$j] = $matrix[$i][$j] / $col_sum[$j];
        $normalisasi[$i][$j] = $matrix[$i][$j];
        $total += $matrix[$i][$j];
    }
    $bobot[$map[$i]] = $total / $n;
}

// Simpan bobot ke database
foreach ($bobot as $kriteria_id => $nilai_bobot) {
    $stmt = $db->prepare("INSERT INTO bobot_kriteria_ahp (kriteria_id, bobot)
                          VALUES (:id, :bobot)
                          ON DUPLICATE KEY UPDATE bobot = :bobot2");
    $stmt->execute([
        ':id' => $kriteria_id,
        ':bobot' => $nilai_bobot,
        ':bobot2' => $nilai_bobot
    ]);
}

// Simpan ke session
$_SESSION['matriks_perbandingan'] = $matriks_asli;
$_SESSION['matriks_normalisasi'] = $normalisasi;
$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil generate bobot kriteria ahp.";

// Redirect
echo "<meta http-equiv='refresh' content='0;url=?page=bobot-kriteria'>";
exit();
?>
