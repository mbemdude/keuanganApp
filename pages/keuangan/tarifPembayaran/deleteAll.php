<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function resetJenisPembayaranTable() {
    $database = new Database();
    $db = $database->getConnection();
    $tableName = 'tarif_pembayaran';
    $deleteSql = "DELETE FROM $tableName";
    $resetAutoIncrementSql = "ALTER TABLE $tableName AUTO_INCREMENT = 1";

    try {
        $db->beginTransaction();
        $stmt = $db->prepare($deleteSql);
        $stmt->execute();
        $db->commit();
        
        $stmt = $db->prepare($resetAutoIncrementSql);
        $stmt->execute();

        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Semua data tarif pembayaran berhasil dihapus.";
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $db->rollBack();
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}

resetJenisPembayaranTable();

header("Location: ?page=tarif-pembayaran");
exit();
?>
