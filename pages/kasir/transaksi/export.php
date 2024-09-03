<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT t.*, s.nama AS nama_siswa, b.nama_barang, b.harga, u.nama AS petugas FROM transaksi t LEFT JOIN uang_saku us ON t.uang_saku_id = us.id LEFT JOIN siswa s ON us.siswa_id = s.id LEFT JOIN barang b ON t.barang_id = b.id LEFT JOIN users u ON t.user_id = u.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Tanggal Transaksi');
    $sheet->setCellValue('B1', 'Nama Siswa');
    $sheet->setCellValue('C1', 'Merk Barang');
    $sheet->setCellValue('D1', 'Jumlah Yang dibeli');
    $sheet->setCellValue('E1', 'Harga Barang');
    $sheet->setCellValue('F1', 'Total Harga');
    $sheet->setCellValue('G1', 'Petugas');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['tanggal']);
        $sheet->setCellValue('B' . $rowNumber, $row['nama_siswa']);
        $sheet->setCellValue('C' . $rowNumber, $row['nama_barang']);
        $sheet->setCellValue('D' . $rowNumber, $row['jumlah']);
        $sheet->setCellValue('E' . $rowNumber, $row['harga']);
        $sheet->setCellValue('F' . $rowNumber, $row['harga'] * $row['jumlah']);
        $sheet->setCellValue('G' . $rowNumber, $row['petugas']);
        $rowNumber++;
    }

    // Menghapus semua output buffer sebelum memulai proses export
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_transaksi.xlsx';

    // Mengatur header untuk mendownload file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}
?>


<section class="content">
    <div class="col-lg-6 col-sm-12">
        <div class="card mx-3">
            <div class="card-header">
                <h3 class="card-title">Ekspor/Impor Data Siswa</h3>
            </div>
            <div class="card-body">
                <form action="" method="post" class="mb-4">
                    <div class="form-group">
                        <input type="hidden" name="export" value="1">
                        <button type="submit" class="btn btn-success">Ekspor Data (XLSX)</button>
                    </div>
                </form>
            </div>
        </div>
</section>