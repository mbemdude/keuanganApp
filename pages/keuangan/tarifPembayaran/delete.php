<?php
if (isset($_GET['jenjang_id']) && isset($_GET['tahun_ajaran_id']) && isset($_GET['tipe'])) {

    $database = new Database();
    $db = $database->getConnection();

    $deleteSql = "DELETE FROM tarif_pembayaran WHERE jenjang_id = :jenjang_id AND tahun_ajaran_id = :tahun_ajaran_id AND tipe = :tipe";
    $stmtDelete = $db->prepare($deleteSql);
    $stmtDelete->bindParam(':jenjang_id', $_GET['jenjang_id']);
    $stmtDelete->bindParam(':tahun_ajaran_id', $_GET['tahun_ajaran_id']);
    $stmtDelete->bindParam(':tipe', $_GET['tipe']);

    if ($stmtDelete->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil hapus data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal hapus data";
    }

    echo "<meta http-equiv='refresh' content='0;url=?page=tarif-pembayaran'>";
}
?>
