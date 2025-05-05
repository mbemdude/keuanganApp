<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function resetNilaiRaporTable() {
    $database = new Database();
    $db = $database->getConnection();
    $tableName = 'nilai_rapor';
    $resetSql = "UPDATE $tableName SET nilai_uas = NULL";
    $resetAutoIncrementSql = "ALTER TABLE $tableName AUTO_INCREMENT = 1";

    try {
        $db->beginTransaction();
        $stmt = $db->prepare($resetSql);
        $stmt->execute();
        $db->commit();
        
        $stmt = $db->prepare($resetAutoIncrementSql);
        $stmt->execute();

        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Semua data nilai berhasil dihapus.";
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $db->rollBack();
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}

resetNilaiRaporTable();

header("Location: ?page=nilai-rapor-uts");
exit();
?>
