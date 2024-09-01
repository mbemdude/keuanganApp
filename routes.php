<?php
// Define routes with their corresponding paths
$routes = [
    'home' => 'pages/home.php',

    // Siswa section
    'siswa' => 'pages/siswa/read.php',
    'show-siswa' => 'pages/siswa/show.php',
    'tambah-siswa' => 'pages/siswa/add.php',
    'edit-siswa' => 'pages/siswa/update.php',
    'hapus-siswa' => 'pages/siswa/delete.php',
    'hapus-semua-siswa' => 'pages/siswa/deleteAll.php',
    'import-siswa' => 'pages/siswa/import.php',
    'detail-siswa' => 'pages/siswa/show.php',

    // Keuangan section
    'uang-saku' => 'pages/keuangan/uangSaku/read.php',
    'tambah-uang-saku' => 'pages/keuangan/uangSaku/add.php',
    'edit-uang-saku' => 'pages/keuangan/uangSaku/update.php',
    'hapus-uang-saku' => 'pages/keuangan/uangSaku/delete.php',
    'hapus-semua-uang-saku' => 'pages/keuangan/uangSaku/deleteAll.php',
    'import-uang-saku' => 'pages/keuangan/uangSaku/import.php',

    'tarif-pembayaran' => 'pages/keuangan/tarifPembayaran/read.php',
    'tambah-tarif-pembayaran' => 'pages/keuangan/tarifPembayaran/add.php',
    'edit-tarif-pembayaran' => 'pages/keuangan/tarifPembayaran/update.php',
    'hapus-tarif-pembayaran' => 'pages/keuangan/tarifPembayaran/delete.php',
    'hapus-semua-tarif-pembayaran' => 'pages/keuangan/tarifPembayaran/deleteAll.php',

    'tagihan-siswa' => 'pages/keuangan/tagihanSiswa/read.php',
    'tambah-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/add.php',
    'edit-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/update.php',
    'hapus-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/delete.php',
    'hapus-semua-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/deleteAll.php',
    'import-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/import.php',

    'transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/read.php',
    'tambah-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/add.php',
    'edit-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/update.php',
    'hapus-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/delete.php',
    'hapus-semua-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/deleteAll.php',
    'import-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/import.php',

    // Kasir section
    'barang' => 'pages/kasir/barang/read.php',
    'tambah-barang' => 'pages/kasir/barang/add.php',
    'edit-barang' => 'pages/kasir/barang/update.php',
    'hapus-barang' => 'pages/kasir/barang/delete.php',

    'transaksi' => 'pages/kasir/transaksi/read.php',
    'tambah-transaksi' => 'pages/kasir/transaksi/add.php',
    'edit-transaksi' => 'pages/kasir/transaksi/update.php',
    'hapus-transaksi' => 'pages/kasir/transaksi/delete.php',

    'kasir' => 'pages/kasir/transaksi/kasir.php',
    'kasir-hapusAll' => 'pages/kasir/transaksi/deleteAll.php',
    'belanja' => 'pages/kasir/transaksi/belanja.php',

    // Masterdata section
    'jenjang' => 'pages/masterdata/jenjang/read.php',
    'tambah-jenjang' => 'pages/masterdata/jenjang/add.php',
    'edit-jenjang' => 'pages/masterdata/jenjang/update.php',
    'hapus-jenjang' => 'pages/masterdata/jenjang/delete.php',
    'hapus-semua-jenjang' => 'pages/masterdata/jenjang/deleteAll.php',

    'kelas' => 'pages/masterdata/kelas/read.php',
    'tambah-kelas' => 'pages/masterdata/kelas/add.php',
    'edit-kelas' => 'pages/masterdata/kelas/update.php',
    'hapus-kelas' => 'pages/masterdata/kelas/delete.php',
    'hapus-semua-kelas' => 'pages/masterdata/kelas/deleteAll.php',

    'status' => 'pages/masterdata/status/read.php',
    'tambah-status' => 'pages/masterdata/status/add.php',
    'edit-status' => 'pages/masterdata/status/update.php',
    'hapus-status' => 'pages/masterdata/status/delete.php',
    'hapus-semua-status' => 'pages/masterdata/status/deleteAll.php',

    'jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/read.php',
    'tambah-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/add.php',
    'edit-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/update.php',
    'hapus-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/delete.php',
];

// Check if page parameter exists and load corresponding file or 404
if (isset($_GET['page']) && array_key_exists($_GET['page'], $routes)) {
    $path = $routes[$_GET['page']];
    file_exists($path) ? include $path : include 'pages/404.php';
} else {
    include 'pages/404.php';
}
?>