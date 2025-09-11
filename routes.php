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

    // User section
    // 'dashboard-siswa' => 'pages/user/home.php',
    // 'riwayat-transaksi' => 'pages/user/riwayatTransaksi.php',

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
    'tambah-tagihan-siswa-lama' => 'pages/keuangan/tagihanSiswa/addLama.php',
    'edit-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/update.php',
    'hapus-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/delete.php',
    'hapus-semua-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/deleteAll.php',
    'import-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/import.php',
    'cetak-tagihan-siswa' => 'pages/keuangan/tagihanSiswa/printAll.php',

    'transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/read.php',
    'tambah-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/add.php',
    'edit-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/update.php',
    'hapus-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/delete.php',
    'hapus-semua-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/deleteAll.php',
    'import-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/import.php',
    'print-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/print.php',
    'cetak-transaksi-keuangan' => 'pages/keuangan/transaksiKeuangan/printAll.php',

    'jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/read.php',
    'tambah-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/add.php',
    'edit-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/update.php',
    'hapus-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/delete.php',
    'hapus-semua-jenis-pembayaran' => 'pages/masterdata/jenisPembayaran/deleteAll.php',
    
    // Kasir section
    'barang' => 'pages/kasir/barang/read.php',
    'tambah-barang' => 'pages/kasir/barang/add.php',
    'edit-barang' => 'pages/kasir/barang/update.php',
    'hapus-barang' => 'pages/kasir/barang/delete.php',
    'hapus-semua-barang' => 'pages/kasir/barang/deleteAll.php',
    'import-barang' => 'pages/kasir/barang/import.php',
    
    'barang-masuk' => 'pages/kasir/barangMasuk/read.php',
    'tambah-barang-masuk' => 'pages/kasir/barangMasuk/add.php',
    'edit-barang-masuk' => 'pages/kasir/barangMasuk/update.php',
    'hapus-barang-masuk' => 'pages/kasir/barangMasuk/delete.php',
    'hapus-semua-barang-masuk' => 'pages/kasir/barangMasuk/deleteAll.php',
    'import-barang-masuk' => 'pages/kasir/barangMasuk/import.php',
    'cetak-barang-masuk' => 'pages/kasir/barangMasuk/printAll.php',
    
    'supplier' => 'pages/kasir/supplier/read.php',
    'tambah-supplier' => 'pages/kasir/supplier/add.php',
    'edit-supplier' => 'pages/kasir/supplier/update.php',
    'hapus-supplier' => 'pages/kasir/supplier/delete.php',
    'hapus-semua-supplier' => 'pages/kasir/supplier/deleteAll.php',
    'import-supplier' => 'pages/kasir/supplier/import.php',

    'transaksi' => 'pages/kasir/transaksi/read.php',
    'tambah-transaksi' => 'pages/kasir/transaksi/add.php',
    'edit-transaksi' => 'pages/kasir/transaksi/update.php',
    'hapus-transaksi' => 'pages/kasir/transaksi/delete.php',
    'hapus-semua-transaksi' => 'pages/kasir/transaksi/deleteAll.php',
    'export-transaksi' => 'pages/kasir/transaksi/export.php',
    'cetak-transaksi' => 'pages/kasir/transaksi/printAll.php',

    'kasir' => 'pages/kasir/transaksi/kasir.php',
    'belanja' => 'pages/kasir/transaksi/belanja.php',

    // Administrator section
    'role' => 'pages/admin/role/read.php',
    'tambah-role' => 'pages/admin/role/add.php',
    'edit-role' => 'pages/admin/role/update.php',
    'hapus-role' => 'pages/admin/role/delete.php',
    
    'user' => 'pages/admin/user/read.php',
    'tambah-user' => 'pages/admin/user/add.php',
    'edit-user' => 'pages/admin/user/update.php',
    'hapus-user' => 'pages/admin/user/delete.php',
    'hapus-semua-user' => 'pages/admin/user/deleteAll.php',
    
    'kriteria' => 'pages/admin/kriteria/read.php',
    'tambah-kriteria' => 'pages/admin/kriteria/add.php',
    'edit-kriteria' => 'pages/admin/kriteria/update.php',
    'hapus-kriteria' => 'pages/admin/kriteria/delete.php',
    'hapus-semua-kriteria' => 'pages/admin/kriteria/deleteAll.php',
    
    'nilai-alternatif' => 'pages/admin/nilaiAlternatif/read.php',
    'generate-nilai-alternatif' => 'pages/admin/nilaiAlternatif/add.php',
    'hapus-nilai-alternatif' => 'pages/admin/nilaiAlternatif/delete.php',
    'hapus-semua-nilai-alternatif' => 'pages/admin/nilaiAlternatif/deleteAll.php',
    
    'perbandingan-kriteria' => 'pages/admin/perbandinganKriteria/read.php',
    'tambah-perbandingan-kriteria' => 'pages/admin/perbandinganKriteria/add.php',
    'edit-perbandingan-kriteria' => 'pages/admin/perbandinganKriteria/update.php',
    'hapus-perbandingan-kriteria' => 'pages/admin/perbandinganKriteria/delete.php',
    'hapus-semua-perbandingan-kriteria' => 'pages/admin/perbandinganKriteria/deleteAll.php',
    
    'bobot-kriteria' => 'pages/admin/bobotKriteria/read.php',
    'tambah-bobot-kriteria' => 'pages/admin/bobotKriteria/add.php',
    'edit-bobot-kriteria' => 'pages/admin/bobotKriteria/update.php',
    'hapus-bobot-kriteria' => 'pages/admin/bobotKriteria/delete.php',
    'hapus-semua-bobot-kriteria' => 'pages/admin/bobotKriteria/deleteAll.php',
    
    // SAW
    'normalisasi' => 'pages/admin/normalisasi/read.php',
    'generate-normalisasi' => 'pages/admin/normalisasi/add.php',
    'hapus-normalisasi' => 'pages/admin/normalisasi/delete.php',
    'hapus-semua-normalisasi' => 'pages/admin/normalisasi/deleteAll.php',
    
    'perhitungan-saw' => 'pages/admin/hasilPerhitunganSaw/read.php',
    'generate-perhitungan-saw' => 'pages/admin/hasilPerhitunganSaw/add.php',
    'hapus-perhitungan-saw' => 'pages/admin/hasilPerhitunganSaw/delete.php',
    'hapus-semua-perhitungan-saw' => 'pages/admin/hasilPerhitunganSaw/deleteAll.php',
    'cetak-perhitungan-saw' => 'pages/admin/hasilPerhitunganSaw/printAll.php',
    
    // AHP
    'normalisasi-ahp' => 'pages/admin/normalisasiAhp/read.php',
    'generate-normalisasi-ahp' => 'pages/admin/normalisasiAhp/add.php',
    'hapus-normalisasi-ahp' => 'pages/admin/normalisasiAhp/delete.php',
    'hapus-semua-normalisasi-ahp' => 'pages/admin/normalisasiAhp/deleteAll.php',
    
    'perhitungan-ahp' => 'pages/admin/hasilPerhitunganAhp/read.php',
    'generate-perhitungan-ahp' => 'pages/admin/hasilPerhitunganAhp/add.php',
    'hapus-perhitungan-ahp' => 'pages/admin/hasilPerhitunganAhp/delete.php',
    'hapus-semua-perhitungan-ahp' => 'pages/admin/hasilPerhitunganAhp/deleteAll.php',
    'cetak-perhitungan-ahp' => 'pages/admin/hasilPerhitunganAhp/printAll.php',
    
    // WP
    'perbaikan-bobot-wp' => 'pages/admin/perbaikanBobotWp/read.php',
    'generate-perbaikan-bobot-wp' => 'pages/admin/perbaikanBobotWp/add.php',
    'hapus-semua-perbaikan-bobot-wp' => 'pages/admin/perbaikanBobotWp/deleteAll.php',
    
    'perhitungan-wp' => 'pages/admin/hasilPerhitunganWp/read.php',
    'generate-perhitungan-wp' => 'pages/admin/hasilPerhitunganWp/add.php',
    'hapus-semua-perhitungan-wp' => 'pages/admin/hasilPerhitunganWp/deleteAll.php',
    'cetak-perhitungan-wp' => 'pages/admin/hasilPerhitunganWp/printAll.php',

    // Operator Section
    'mata-pelajaran' => 'pages/masterdata/mataPelajaran/read.php',
    'tambah-mata-pelajaran' => 'pages/masterdata/mataPelajaran/add.php',
    'edit-mata-pelajaran' => 'pages/masterdata/mataPelajaran/update.php',
    'hapus-mata-pelajaran' => 'pages/masterdata/mataPelajaran/delete.php',
    'hapus-semua-mata-pelajaran' => 'pages/masterdata/mataPelajaran/deleteAll.php',

    'tahun-ajaran' => 'pages/masterdata/tahunAjaran/read.php',
    'tambah-tahun-ajaran' => 'pages/masterdata/tahunAjaran/add.php',
    'edit-tahun-ajaran' => 'pages/masterdata/tahunAjaran/update.php',
    'hapus-tahun-ajaran' => 'pages/masterdata/tahunAjaran/delete.php',

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

    'presensi' => 'pages/operator/presensi/read.php',
    'show-presensi' => 'pages/operator/presensi/show.php',
    'tambah-presensi' => 'pages/operator/presensi/add.php',
    'edit-presensi' => 'pages/operator/presensi/update.php',
    'hapus-presensi' => 'pages/operator/presensi/delete.php',
    'hapus-semua-presensi' => 'pages/operator/presensi/deleteAll.php',
    'cetak-presensi' => 'pages/operator/presensi/printAll.php',
    'impor-presensi' => 'pages/operator/presensi/export.php',

    'nilai-rapor-uts' => 'pages/operator/nilaiRapor/uts/read.php',
    'tambah-nilai-rapor-uts' => 'pages/operator/nilaiRapor/uts/add.php',
    'hapus-semua-nilai-rapor-uts' => 'pages/operator/nilaiRapor/uts/deleteAll.php',
    'cetak-nilai-uts' => 'pages/operator/nilaiRapor/uts/printUts.php',
    'impor-nilai-rapor-uts' => 'pages/operator/nilaiRapor/uts/export.php',

    'nilai-rapor-uas' => 'pages/operator/nilaiRapor/uas/read.php',
    'tambah-nilai-rapor-uas' => 'pages/operator/nilaiRapor/uas/add.php',
    'hapus-semua-nilai-rapor-uas' => 'pages/operator/nilaiRapor/uas/deleteAll.php',
    'cetak-nilai-uas' => 'pages/operator/nilaiRapor/uas/printUas.php',
    'impor-nilai-rapor-uas' => 'pages/operator/nilaiRapor/uas/export.php'
];

// Check if page parameter exists and load corresponding file or default to home
$page = $_GET['page'] ?? 'home'; // Default to 'home' if 'page' is not set
if (array_key_exists($page, $routes)) {
    $path = $routes[$page];
    file_exists($path) ? include $path : include 'pages/404.php';
} else {
    include 'pages/404.php';
}
?>