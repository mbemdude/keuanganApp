<?php 
    function rupiah($angka) {
        $hasil = 'Rp ' . number_format($angka, 2, ",", ".");
        return $hasil;
    }

    function checkAccess($requiredRole) {
        if(!isset($_SESSION['role_id']) || $_SESSION['role_id' != $requiredRole]) {
            header('Location: index.php?page=login');
        }
    }
?>