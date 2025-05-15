<?php
// Kosongkan tabel normalisasi sebelum insert baru
$db->exec("DELETE FROM normalisasi");

// Ambil semua kriteria
$stmtKriteria = $db->prepare("SELECT id, tipe FROM kriteria");
$stmtKriteria->execute();
$kriteriaList = $stmtKriteria->fetchAll(PDO::FETCH_ASSOC);

foreach ($kriteriaList as $kriteria) {
    $kriteria_id = $kriteria['id'];
    $tipe = strtolower($kriteria['tipe']);

    // Ambil semua nilai alternatif untuk kriteria ini
    $stmtNilai = $db->prepare("SELECT siswa_id, nilai FROM nilai_alternatif WHERE kriteria_id = ?");
    $stmtNilai->execute([$kriteria_id]);
    $nilaiList = $stmtNilai->fetchAll(PDO::FETCH_ASSOC);

    // Hitung pembagi: max (benefit) atau min (cost)
    $pembagi = ($tipe === 'benefit') ? max(array_column($nilaiList, 'nilai')) : min(array_column($nilaiList, 'nilai'));

    // Simpan nilai normalisasi ke tabel
    foreach ($nilaiList as $row) {
        $siswa_id = $row['siswa_id'];
        $nilai = $row['nilai'];

        $nilai_normalisasi = ($tipe === 'benefit') 
            ? ($pembagi != 0 ? $nilai / $pembagi : 0) 
            : ($nilai != 0 ? $pembagi / $nilai : 0);

        $stmtInsert = $db->prepare("INSERT INTO normalisasi (siswa_id, kriteria_id, nilai_normalisasi) VALUES (?, ?, ?)");
        $stmtInsert->execute([$siswa_id, $kriteria_id, $nilai_normalisasi]);
    }
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil generate nilai normalisasi.";
echo "<meta http-equiv='refresh' content='0;url=?page=normalisasi'>";
exit();
