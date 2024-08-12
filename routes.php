<?php
if(isset($_GET['page'])) {
    $page = $_GET['page'];
    switch($page) {
        case 'home':
            file_exists('pages/home.php') ? include 'pages/home.php' : include 'pages/404.php';
            break;
        case 'siswa':
            file_exists('pages/siswa/read.php') ? include 'pages/siswa/read.php' : include 'pages/404.php';
            break;
        case 'show-siswa':
            file_exists('pages/siswa/show.php') ? include 'pages/siswa/show.php' : include 'pages/404.php';
            break;
        case 'tambah-siswa':
            file_exists('pages/siswa/add.php') ? include 'pages/siswa/add.php' : include 'pages/404.php';
            break;
        case 'edit-siswa':
            file_exists('pages/siswa/update.php') ? include 'pages/siswa/update.php' : include 'pages/404.php';
            break;
        case 'hapus-siswa':
            file_exists('pages/siswa/delete.php') ? include 'pages/siswa/delete.php' : include 'pages/404.php';
            break;
        case 'uang-saku':
            file_exists('pages/keuangan/uangSaku/read.php') ? include 'pages/keuangan/uangSaku/read.php' : include 'pages/404.php';
            break;
        case 'tambah-uang-saku':
            file_exists('pages/keuangan/uangSaku/add.php') ? include 'pages/keuangan/uangSaku/add.php' : include 'pages/404.php';
            break;
        case 'edit-uang-saku':
            file_exists('pages/keuangan/uangSaku/update.php') ? include 'pages/keuangan/uangSaku/update.php' : include 'pages/404.php';
            break;
        case 'hapus-uang-saku':
            file_exists('pages/keuangan/uangSaku/delete.php') ? include 'pages/keuangan/uangSaku/delete.php' : include 'pages/404.php';
            break;
        case 'barang':
            file_exists('pages/kasir/barang/read.php') ? include 'pages/kasir/barang/read.php' : include 'pages/404.php';
            break;
        case 'tambah-barang':
            file_exists('pages/kasir/barang/add.php') ? include 'pages/kasir/barang/add.php' : include 'pages/404.php';
            break;
        case 'edit-barang':
            file_exists('pages/kasir/barang/update.php') ? include 'pages/kasir/barang/update.php' : include 'pages/404.php';
            break;
        case 'hapus-barang':
            file_exists('pages/kasir/barang/delete.php') ? include 'pages/kasir/barang/delete.php' : include 'pages/404.php';
            break;
        case 'transaksi':
            file_exists('pages/kasir/transaksi/read.php') ? include 'pages/kasir/transaksi/read.php' : include 'pages/404.php';
            break;
        case 'tambah-transaksi':
            file_exists('pages/kasir/transaksi/add.php') ? include 'pages/kasir/transaksi/add.php' : include 'pages/404.php';
            break;
        case 'edit-transaksi':
            file_exists('pages/kasir/transaksi/update.php') ? include 'pages/kasir/transaksi/update.php' : include 'pages/404.php';
            break;
        case 'hapus-transaksi':
            file_exists('pages/kasir/transaksi/delete.php') ? include 'pages/kasir/transaksi/delete.php' : include 'pages/404.php';
            break;
        case 'kasir':
            file_exists('pages/kasir/transaksi/kasir.php') ? include 'pages/kasir/transaksi/kasir.php' : include 'pages/404.php';
            break;
        case 'kasir-hapusAll':
            file_exists('pages/kasir/transaksi/deleteAll.php') ? include 'pages/kasir/transaksi/deleteAll.php' : include 'pages/404.php';
            break;
        case 'belanja':
            file_exists('pages/kasir/transaksi/belanja.php') ? include 'pages/kasir/transaksi/belanja.php' : include 'pages/404.php';
            break;
        case 'jenjang':
            file_exists('pages/masterdata/jenjang/read.php') ? include 'pages/masterdata/jenjang/read.php' : include 'pages/404.php';
            break;
        case 'tambah-jenjang':
            file_exists('pages/masterdata/jenjang/add.php') ? include 'pages/masterdata/jenjang/add.php' : include 'pages/404.php';
            break;
        case 'edit-jenjang':
            file_exists('pages/masterdata/jenjang/update.php') ? include 'pages/masterdata/jenjang/update.php' : include 'pages/404.php';
            break;
        case 'hapus-jenjang':
            file_exists('pages/masterdata/jenjang/delete.php') ? include 'pages/masterdata/jenjang/delete.php' : include 'pages/404.php';
            break;
        case 'kelas':
            file_exists('pages/masterdata/kelas/read.php') ? include 'pages/masterdata/kelas/read.php' : include 'pages/404.php';
            break;
        case 'tambah-kelas':
            file_exists('pages/masterdata/kelas/add.php') ? include 'pages/masterdata/kelas/add.php' : include 'pages/404.php';
            break;
        case 'edit-kelas':
            file_exists('pages/masterdata/kelas/update.php') ? include 'pages/masterdata/kelas/update.php' : include 'pages/404.php';
            break;
        case 'hapus-kelas':
            file_exists('pages/masterdata/kelas/delete.php') ? include 'pages/masterdata/kelas/delete.php' : include 'pages/404.php';
            break;
        case 'status':
            file_exists('pages/masterdata/status/read.php') ? include 'pages/masterdata/status/read.php' : include 'pages/404.php';
            break;
        case 'tambah-status':
            file_exists('pages/masterdata/status/add.php') ? include 'pages/masterdata/status/add.php' : include 'pages/404.php';
            break;
        case 'edit-status':
            file_exists('pages/masterdata/status/update.php') ? include 'pages/masterdata/status/update.php' : include 'pages/404.php';
            break;
        case 'hapus-status':
            file_exists('pages/masterdata/status/delete.php') ? include 'pages/masterdata/status/delete.php' : include 'pages/404.php';
            break;
        case 'role':
            file_exists('pages/admin/role/read.php') ? include 'pages/admin/role/read.php' : include 'pages/404.php';
            break;
        case 'tambah-role':
            file_exists('pages/admin/role/add.php') ? include 'pages/admin/role/add.php' : include 'pages/404.php';
            break;
        case 'edit-role':
            file_exists('pages/admin/role/update.php') ? include 'pages/admin/role/update.php' : include 'pages/404.php';
            break;
        case 'hapus-role':
            file_exists('pages/admin/role/delete.php') ? include 'pages/admin/role/delete.php' : include 'pages/404.php';
            break;
        case 'user':
            file_exists('pages/admin/user/read.php') ? include 'pages/admin/user/read.php' : include 'pages/404.php';
            break;
        case 'tambah-user':
            file_exists('pages/admin/user/add.php') ? include 'pages/admin/user/add.php' : include 'pages/404.php';
            break;
        case 'edit-user':
            file_exists('pages/admin/user/update.php') ? include 'pages/admin/user/update.php' : include 'pages/404.php';
            break;
        case 'hapus-user':
            file_exists('pages/admin/user/delete.php') ? include 'pages/admin/user/delete.php' : include 'pages/404.php';
            break;
        default:
            include 'pages/404.php';
    }
} else {
    include 'pages/home.php';
}
?>
