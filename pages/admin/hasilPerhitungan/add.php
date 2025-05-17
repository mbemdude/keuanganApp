<?php
// Ambil semua data normalisasi
$stmt = $db->prepare("SELECT * FROM normalisasi");
$stmt->execute();
$dataNormalisasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua kriteria
$stmt = $db->prepare("SELECT * FROM kriteria");
$stmt->execute();
$dataKriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat array bobot kriteria (dibagi 100 agar sesuai format desimal)
$bobot = [];
foreach ($dataKriteria as $kriteria) {
    $bobot[$kriteria['id']] = $kriteria['bobot'] / 100;
}

// Hitung skor akhir per siswa
$hasil = [];
foreach ($dataNormalisasi as $norm) {
    $siswa_id = $norm['siswa_id'];
    $kriteria_id = $norm['kriteria_id'];
    $nilai = $norm['nilai_normalisasi'];

    if (!isset($hasil[$siswa_id])) {
        $hasil[$siswa_id] = 0;
    }

    $hasil[$siswa_id] += $nilai * $bobot[$kriteria_id];
}

// Kosongkan tabel hasil_perhitungan
$db->exec("DELETE FROM hasil_perhitungan");

// Masukkan hasil ke database
foreach ($hasil as $siswa_id => $skor) {
    $stmt = $db->prepare("INSERT INTO hasil_perhitungan (siswa_id, nilai_akhir) VALUES (?, ?)");
    $stmt->execute([$siswa_id, $skor]);
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil melakukan perhitungan SAW.";
echo "<meta http-equiv='refresh' content='0;url=?page=perhitungan-spk'>";
exit();
