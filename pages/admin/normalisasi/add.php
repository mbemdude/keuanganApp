<?php
// Ambil semua data nilai alternatif
$stmt = $db->prepare("SELECT * FROM nilai_alternatif");
$stmt->execute();
$dataAlternatif = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua kriteria
$stmt = $db->prepare("SELECT * FROM kriteria");
$stmt->execute();
$dataKriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat array max/min berdasarkan kriteria
$max = [];
$min = [];
foreach ($dataKriteria as $kriteria) {
    $id = $kriteria['id'];
    $stmt = $db->prepare("SELECT nilai FROM nilai_alternatif WHERE kriteria_id = ?");
    $stmt->execute([$id]);
    $nilaiList = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $max[$id] = max($nilaiList);
    $min[$id] = min($nilaiList);
}

// Kosongkan tabel normalisasi
$db->exec("DELETE FROM normalisasi");

// Proses normalisasi dan simpan
$nilaiNormalisasi = [];
foreach ($dataAlternatif as $alt) {
    $kriteria_id = $alt['kriteria_id'];
    $siswa_id = $alt['siswa_id'];
    $nilai = $alt['nilai'];

    $tipe = '';
    foreach ($dataKriteria as $krit) {
        if ($krit['id'] == $kriteria_id) {
            $tipe = $krit['tipe'];
            break;
        }
    }

    if ($tipe == 'benefit') {
        $nilaiNorm = $max[$kriteria_id] ? $nilai / $max[$kriteria_id] : 0;
    } else {
        $nilaiNorm = $nilai ? $min[$kriteria_id] / $nilai : 0;
    }

    $nilaiNormalisasi[$siswa_id][$kriteria_id] = $nilaiNorm;

    $stmt = $db->prepare("INSERT INTO normalisasi (siswa_id, kriteria_id, nilai_normalisasi) VALUES (?, ?, ?)");
    $stmt->execute([$siswa_id, $kriteria_id, $nilaiNorm]);
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil melakukan normalisasi.";
echo "<meta http-equiv='refresh' content='0;url=?page=normalisasi'>";
exit();
