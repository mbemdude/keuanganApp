<?php
$stmtKriteria = $db->prepare("SELECT k.id, k.tipe, bka.bobot 
                              FROM kriteria k 
                              JOIN bobot_kriteria_ahp bka ON k.id = bka.kriteria_id");
$stmtKriteria->execute();
$kriteriaList = $stmtKriteria->fetchAll(PDO::FETCH_ASSOC);

$stmtNilai = $db->prepare("SELECT * FROM nilai_alternatif");
$stmtNilai->execute();
$nilaiList = $stmtNilai->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($nilaiList as $row) {
    $data[$row['siswa_id']][$row['kriteria_id']] = $row['nilai'];
}

$min = [];
$max = [];
foreach ($kriteriaList as $kriteria) {
    $kid = $kriteria['id'];
    $nilai_kriteria = [];

    foreach ($data as $siswa_id => $nilai_siswa) {
        if (isset($nilai_siswa[$kid])) {
            $nilai_kriteria[] = $nilai_siswa[$kid];
        }
    }

    $min[$kid] = min($nilai_kriteria);
    $max[$kid] = max($nilai_kriteria);
}

// Hitung nilai normalisasi × bobot dan simpan ke normalisasi_ahp
foreach ($data as $siswa_id => $nilai_per_kriteria) {
    foreach ($kriteriaList as $kriteria) {
        $kid = $kriteria['id'];
        $tipe = strtolower($kriteria['tipe']);
        $bobot = $kriteria['bobot'];

        if (!isset($nilai_per_kriteria[$kid])) continue;

        $nilai = $nilai_per_kriteria[$kid];
        $normal = ($tipe === 'benefit') 
                  ? ($nilai / $max[$kid]) 
                  : ($min[$kid] / $nilai);

        $nilai_final = $normal * $bobot;

        // Simpan ke normalisasi_ahp
        $stmt = $db->prepare("INSERT INTO normalisasi_ahp (siswa_id, kriteria_id, nilai_normalisasi)
                              VALUES (:siswa, :kriteria, :nilai)
                              ON DUPLICATE KEY UPDATE nilai_normalisasi = :nilai");
        $stmt->execute([
            ':siswa' => $siswa_id,
            ':kriteria' => $kid,
            ':nilai' => $nilai_final
        ]);
    }
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Normalisasi AHP berhasil (termasuk bobot).";
echo "<meta http-equiv='refresh' content='0;url=?page=normalisasi-ahp'>";
exit();
?>