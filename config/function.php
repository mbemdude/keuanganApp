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

    function terbilang($angka) {
        $angka = abs($angka);
        $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $hasil = "";

        if ($angka < 12) {
            $hasil = " " . $baca[$angka];
        } else if ($angka < 20) {
            $hasil = terbilang($angka - 10) . " belas";
        } else if ($angka < 100) {
            $hasil = terbilang($angka / 10) . " puluh " . terbilang($angka % 10);
        } else if ($angka < 200) {
            $hasil = " seratus " . terbilang($angka - 100);
        } else if ($angka < 1000) {
            $hasil = terbilang($angka / 100) . " ratus " . terbilang($angka % 100);
        } else if ($angka < 2000) {
            $hasil = " seribu " . terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $hasil = terbilang($angka / 1000) . " ribu " . terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $hasil = terbilang($angka / 1000000) . " juta " . terbilang($angka % 1000000);
        }

        return trim($hasil);
    }
?>