<?php
// Ambil daftar siswa unik dari normalisasi_ahp
$stmt = $db->prepare("SELECT DISTINCT siswa_id FROM normalisasi_ahp");
$stmt->execute();
$siswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($siswaList as $siswa) {
    $siswa_id = $siswa['siswa_id'];

    // Jumlahkan nilai normalisasi AHP per siswa
    $stmtSum = $db->prepare("SELECT SUM(nilai_normalisasi) AS total 
                             FROM normalisasi_ahp 
                             WHERE siswa_id = :id");
    $stmtSum->execute([':id' => $siswa_id]);
    $total = $stmtSum->fetch(PDO::FETCH_ASSOC)['total'];

    // Simpan ke hasil_perhitungan_ahp
    $stmtInsert = $db->prepare("INSERT INTO hasil_perhitungan_ahp (siswa_id, nilai_akhir)
                                VALUES (:siswa_id, :nilai)
                                ON DUPLICATE KEY UPDATE nilai_akhir = :nilai2");
    $stmtInsert->execute([
        ':siswa_id' => $siswa_id,
        ':nilai' => $total,
        ':nilai2' => $total
    ]);
}

$_SESSION['hasil'] = true;
$_SESSION['pesan'] = "Berhasil generate hasil akhir AHP.";
echo "<meta http-equiv='refresh' content='0;url=?page=perhitungan-ahp'>";
exit();
?>
