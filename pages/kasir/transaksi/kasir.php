<?php
if (isset($_POST['qr_code'])) {
    $kode = $_POST['qr_code'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT us.*, s.nama, k.kelas FROM uang_saku us JOIN siswa s ON us.siswa_id = s.id JOIN kelas k ON S.kelas_id=k.id WHERE s.nis = :nis";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nis', $kode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['siswa'] = $siswa;
        header("Location: ?page=belanja");
        exit;
    } else {
        $_SESSION['error'] = "Siswa tidak ditemukan!";
        header("Location: ?page=transaksi.php");
        exit;
    }
    ob_end_flush();
}
?>
<div id="qr-reader" style="width: 500px;"></div>
    <div id="qr-reader-results"></div>
    <form id="qr-form" method="POST">
        <input type="hidden" id="qr_code" name="qr_code">
        <button type="submit" name="scan_qr" class="btn btn-primary">Cari</button>
    </form>