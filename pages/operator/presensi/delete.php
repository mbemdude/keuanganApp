<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $selectSql = "SELECT * FROM presensi WHERE id = :id";
    $stmtSelect = $db->prepare($selectSql);
    $stmtSelect->bindParam(':id', $id);
    $stmtSelect->execute();
    $row = $stmtSelect->fetch();

    if($row) {
        $deleteSql = "DELETE FROM presensi WHERE id = ?";
        $stmt = $db->prepare($deleteSql);
        $stmt->bindParam(1, $_GET['id']);
        if($stmt->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Data berhasil dihapus";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Data gagal dihapus";
        }
    }
    echo "<meta http-equiv='refresh' content='0;url=?page=show-presensi&siswa_id={$row['siswa_id']}'>";
    }

?>