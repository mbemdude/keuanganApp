<?php
$stmtSiswa = $db->prepare("SELECT s.id, s.nama, s.jumlah_saudara, s.pekerjaan_ayah FROM siswa s");
$stmtSiswa->execute();
$siswaList = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);

$stmtKriteria = $db->prepare("SELECT * FROM kriteria");
$stmtKriteria->execute();
$kriteriaList = $stmtKriteria->fetchAll(PDO::FETCH_ASSOC);

$db->exec("DELETE FROM nilai_alternatif");

foreach ($siswaList as $siswa) {
    foreach ($kriteriaList as $kriteria) {
        $kriteria_id = $kriteria['id'];
        $namaKriteria = strtolower($kriteria['nama_kriteria']);
        $nilai = 0;

        switch ($namaKriteria) {
            case 'nilai akademik':
                $stmtNilai = $db->prepare("SELECT AVG((nilai_uts + nilai_uas) / 2) as rata2 FROM nilai_rapor WHERE siswa_id = ?");
                $stmtNilai->execute([$siswa['id']]);
                $rata = $stmtNilai->fetchColumn();
                if ($rata >= 90) {
                    $nilai = 5;
                } elseif ($rata >= 80) {
                    $nilai = 4;
                } elseif ($rata >= 70) {
                    $nilai = 3;
                } elseif ($rata >= 60) {
                    $nilai = 2;
                } else {
                    $nilai = 1;
                }
                break;

            case 'presensi':
                $stmtPresensi = $db->prepare("SELECT COUNT(*) FROM presensi WHERE siswa_id = ? AND status = 'Hadir'");
                $stmtPresensi->execute([$siswa['id']]);
                $hadir = $stmtPresensi->fetchColumn();
                if ($hadir >= 25) {
                    $nilai = 5;
                } elseif ($hadir >= 20) {
                    $nilai = 4;
                } elseif ($hadir >= 15) {
                    $nilai = 3;
                } elseif ($hadir >= 10) {
                    $nilai = 2;
                } else {
                    $nilai = 1;
                }
                break;

            case 'jumlah tunggakkan':
                $stmtTunggakan = $db->prepare("SELECT SUM(jumlah_tagihan) FROM tagihan_siswa WHERE siswa_id = ?");
                $stmtTunggakan->execute([$siswa['id']]);
                $total = $stmtTunggakan->fetchColumn();
                if ($total >= 8000000) {
                    $nilai = 5;
                } elseif ($total >= 6000000) {
                    $nilai = 4;
                } elseif ($total >= 4000000) {
                    $nilai = 3;
                } elseif ($total >= 2000000) {
                    $nilai = 2;
                } else {
                    $nilai = 1;
                }
                break;

            case 'pekerjaan orang tua':
                $pekerjaan = strtolower($siswa['pekerjaan_ayah']);
                $nilai = match($pekerjaan) {
                    'tidak bekerja' => 5,
                    'buruh' => 4,
                    'wirausaha' => 3,
                    'karyawan swasta' => 2,
                    'pns' => 1,
                    default => 1
                };
                break;

            case 'tanggungan orang tua':
                $tanggungan = (int) $siswa['jumlah_saudara'];
                if ($tanggungan >= 5) {
                    $nilai = 5;
                } elseif ($tanggungan == 4) {
                    $nilai = 4;
                } elseif ($tanggungan == 3) {
                    $nilai = 3;
                } elseif ($tanggungan == 2) {
                    $nilai = 2;
                } else {
                    $nilai = 1;
                }
                break;
        }

        $stmtInsert = $db->prepare("INSERT INTO nilai_alternatif (siswa_id, kriteria_id, nilai) VALUES (?, ?, ?)");
        $stmtInsert->execute([$siswa['id'], $kriteria_id, $nilai]);
    }
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil generate nilai alternatif.";
echo "<meta http-equiv='refresh' content='0;url=?page=nilai-alternatif'>";
exit();
