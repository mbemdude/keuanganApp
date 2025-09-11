<?php
// Ambil bobot kriteria AHP
$stmtBobot = $db->prepare("SELECT kriteria_id, bobot FROM bobot_kriteria_ahp");
$stmtBobot->execute();
$bobotList = $stmtBobot->fetchAll(PDO::FETCH_KEY_PAIR); // [kriteria_id => bobot]

// Daftar ID kriteria
$kriteria_ids = [
    'akademik' => 1,
    'kehadiran' => 2,
    'tunggakan' => 3,
    'pekerjaan_ayah' => 4,
    'tanggungan' => 5
];

// Ambil data siswa dengan nilai rapor, presensi, tagihan, pekerjaan ayah, jumlah tanggungan
$stmtSiswa = $db->prepare("
    SELECT 
        s.id AS siswa_id,
        s.nama,
        s.pekerjaan_ayah,
        s.jumlah_saudara,
        (SELECT AVG((nilai_uts + nilai_uas)/2) 
         FROM nilai_rapor nr 
         WHERE nr.siswa_id = s.id) AS nilai_akademik,
        (SELECT COUNT(*) 
         FROM presensi p 
         WHERE p.siswa_id = s.id AND p.status = 'Hadir') AS kehadiran,
        (SELECT SUM(jumlah_tagihan) 
         FROM tagihan_siswa t 
         WHERE t.siswa_id = s.id) AS tunggakan
    FROM siswa s
");
$stmtSiswa->execute();
$siswaList = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);

// Siapkan query insert/update
$stmtInsert = $db->prepare("
    INSERT INTO normalisasi_ahp (siswa_id, kriteria_id, nilai_normalisasi)
    VALUES (:siswa_id, :kriteria_id, :nilai_normalisasi)
    ON DUPLICATE KEY UPDATE nilai_normalisasi = :nilai_normalisasi2
");

foreach ($siswaList as $siswa) {
    $siswa_id = $siswa['siswa_id'];

    // ==== Nilai Raport ====
    $na = 0;
    if ($siswa['nilai_akademik'] >= 90) $na = 0.437;
    elseif ($siswa['nilai_akademik'] >= 80) $na = 0.282;
    elseif ($siswa['nilai_akademik'] >= 70) $na = 0.142;
    elseif ($siswa['nilai_akademik'] >= 60) $na = 0.088;
    else $na = 0.052;
    $na *= $bobotList[$kriteria_ids['akademik']];

    // ==== Kehadiran ====
    $nk = 0;
    if ($siswa['kehadiran'] >= 25) $nk = 0.482;
    elseif ($siswa['kehadiran'] >= 20) $nk = 0.264;
    elseif ($siswa['kehadiran'] >= 15) $nk = 0.141;
    elseif ($siswa['kehadiran'] >= 10) $nk = 0.077;
    else $nk = 0.036;
    $nk *= $bobotList[$kriteria_ids['kehadiran']];

    // ==== Tunggakan ====
    $nt = 0;
    if ($siswa['tunggakan'] >= 8000000) $nt = 0.4301;
    elseif ($siswa['tunggakan'] >= 6000000) $nt = 0.2558;
    elseif ($siswa['tunggakan'] >= 4000000) $nt = 0.1707;
    elseif ($siswa['tunggakan'] >= 2000000) $nt = 0.1104;
    else $nt = 0.0329;
    $nt *= $bobotList[$kriteria_ids['tunggakan']];

    // ==== Pekerjaan Ayah ====
    $npa = 0;
    switch ($siswa['pekerjaan_ayah']) {
        case 'Tidak Bekerja': $npa = 0.461; break;
        case 'Buruh': $npa = 0.232; break;
        case 'Wirausaha': $npa = 0.182; break;
        case 'Karyawan Swasta': $npa = 0.079; break;
        case 'PNS': $npa = 0.046; break;
        default: $npa = 0;
    }
    $npa *= $bobotList[$kriteria_ids['pekerjaan_ayah']];

    // ==== Tanggungan ====
    $ntg = 0;
    if ($siswa['jumlah_saudara'] >= 5) $ntg = 0.418;
    elseif ($siswa['jumlah_saudara'] == 4) $ntg = 0.298;
    elseif ($siswa['jumlah_saudara'] == 3) $ntg = 0.166;
    elseif ($siswa['jumlah_saudara'] == 2) $ntg = 0.072;
    else $ntg = 0.046;
    $ntg *= $bobotList[$kriteria_ids['tanggungan']];

    // Simpan ke normalisasi_ahp
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':kriteria_id' => $kriteria_ids['akademik'],
        ':nilai_normalisasi' => $na,
        ':nilai_normalisasi2' => $na
    ]);
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':kriteria_id' => $kriteria_ids['kehadiran'],
        ':nilai_normalisasi' => $nk,
        ':nilai_normalisasi2' => $nk
    ]);
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':kriteria_id' => $kriteria_ids['tunggakan'],
        ':nilai_normalisasi' => $nt,
        ':nilai_normalisasi2' => $nt
    ]);
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':kriteria_id' => $kriteria_ids['pekerjaan_ayah'],
        ':nilai_normalisasi' => $npa,
        ':nilai_normalisasi2' => $npa
    ]);
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':kriteria_id' => $kriteria_ids['tanggungan'],
        ':nilai_normalisasi' => $ntg,
        ':nilai_normalisasi2' => $ntg
    ]);
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Normalisasi AHP berhasil (nilai sudah dikalikan bobot kriteria).";
echo "<meta http-equiv='refresh' content='0;url=?page=normalisasi-ahp'>";
exit();
?>
