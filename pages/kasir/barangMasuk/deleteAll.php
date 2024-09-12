<?php
// Pastikan session sudah dimulai jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function resetTransaksiTable() {
    // Membuat koneksi ke database
    $database = new Database();
    $db = $database->getConnection();

    // Nama tabel yang akan direset
    $tableName = 'barang_masuk';

    // Query untuk menghapus semua data dari tabel
    $deleteSql = "DELETE FROM $tableName";
    // Query untuk mereset auto-increment
    $resetAutoIncrementSql = "ALTER TABLE $tableName AUTO_INCREMENT = 1";

    try {
        // Mulai transaksi
        if (!$db->inTransaction()) {  // Cek apakah transaksi sudah dimulai
            $db->beginTransaction();
        }

        // Menjalankan query untuk menghapus data
        $stmt = $db->prepare($deleteSql);
        $stmt->execute();
        
        // Commit transaksi penghapusan data
        $db->commit();

        // Menjalankan query untuk mereset auto-increment di luar transaksi
        $stmt = $db->prepare($resetAutoIncrementSql);
        $stmt->execute();

        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Semua data barang masuk berhasil dihapus dan auto-increment telah direset.";
    } catch (Exception $e) {
        // Jika terjadi error, rollback hanya jika transaksi aktif
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Panggil fungsi untuk mereset tabel tagihan siswa
resetTransaksiTable();

// Redirect setelah operasi selesai
header("Location: ?page=barang-masuk");
exit();
?>