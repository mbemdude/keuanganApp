<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function resetKelasTable() {
    $database = new Database();
    $db = $database->getConnection();
    $tableName = 'kelas';
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
        $_SESSION['pesan'] = "Semua data kelas berhasil dihapus.";
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $db->rollBack();
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}

resetKelasTable();

header("Location: ?page=kelas");
exit();
?>
