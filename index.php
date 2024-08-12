<?php
session_start();
include 'database/database.php';
include 'config/function.php';

// kasir start
if (isset($_POST['qr_code'])) {
    ob_start(); // Mulai output buffering
    $kode = $_POST['qr_code'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT us.*, s.nama, k.kelas FROM uang_saku us JOIN siswa s ON us.siswa_id = s.id JOIN kelas k ON S.kelas_id=k.id WHERE s.nis = :nis";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nis', $kode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['siswa'] = $siswa;
        header("Location: ?page=belanja");
        exit;
    } else {
        $_SESSION['error'] = "Siswa tidak ditemukan!";
        header("Location: ?page=transaksi.php");
        exit;
    }
    ob_end_flush();
}
// kasir end

// belanja start
// if (!isset($_SESSION['cart'])) {
//     $_SESSION['cart'] = [];
// }

// if (isset($_POST['scan_barcode'])) {
//     $barcode = $_POST['barcode'];
    
//     $database = new Database();
//     $db = $database->getConnection();

//     $query = "SELECT * FROM barang WHERE kode_barang = :kode_barang";
//     $stmt = $db->prepare($query);
//     $stmt->bindParam(':kode_barang', $barcode);
//     $stmt->execute();

//     if ($stmt->rowCount() > 0) {
//         $barang = $stmt->fetch(PDO::FETCH_ASSOC);
//         $_SESSION['cart'][] = [
//             'nama_barang' => $barang['nama'],
//             'jumlah' => 1
//         ];
//     } else {
//         $_SESSION['error'] = "Barang tidak ditemukan!";
//     }

//     header("Location: ?page=belanja");
//     exit;
// }
// // belanja end

?>

<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>AdminLTE v4 | Dashboard</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="assets/dist/css/adminlte.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">

    <!-- scanner -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head> <!--end::Head--> <!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> 
    <!--begin::App Wrapper-->
    <div class="app-wrapper"> 
        <!--begin::Header-->
        <?php include 'partials/navbar.php' ?>
        <!--end::Header-->

        <!--begin::Sidebar-->
        <?php include 'partials/sidebar.php' ?>
        <!--end::Sidebar--> 
        <!--begin::App Main-->
        <main class="app-main"> 
            <!--begin::App Content Header-->
            <div class="app-content-header"> 
                <!--begin::Container-->
                <div class="container-fluid"> 
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Dashboard
                                </li>
                            </ol>
                        </div>
                    </div> 
                    <!--end::Row-->
                </div> <!--end::Container-->
            </div> <!--end::App Content Header--> 
            <!--begin::App Content-->
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <?php include 'routes.php' ?>
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> 
        <!--begin::Footer-->
        <?php include 'partials/footer.php' ?>
        <!--end::Footer-->
    </div> <!--end::App Wrapper--> 
    <!--begin::Script--> 
    <?php include 'partials/js.php' ?>
     <!--end::Script-->
</body><!--end::Body-->

</html>