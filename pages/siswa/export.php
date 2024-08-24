<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $format = $_POST['format'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT kode, nis, nama, jenjang_id, kelas_id, status_id FROM siswa";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel atau CSV
    $sheet->setCellValue('A1', 'Kode');
    $sheet->setCellValue('B1', 'NIS');
    $sheet->setCellValue('C1', 'Nama');
    $sheet->setCellValue('D1', 'Jenjang ID');
    $sheet->setCellValue('E1', 'Kelas ID');
    $sheet->setCellValue('F1', 'Status ID');

    // Menulis data siswa ke file Excel atau CSV
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['kode']);
        $sheet->setCellValue('B' . $rowNumber, $row['nis']);
        $sheet->setCellValue('C' . $rowNumber, $row['nama']);
        $sheet->setCellValue('D' . $rowNumber, $row['jenjang_id']);
        $sheet->setCellValue('E' . $rowNumber, $row['kelas_id']);
        $sheet->setCellValue('F' . $rowNumber, $row['status_id']);
        $rowNumber++;
    }

    if ($format === 'xlsx') {
        $writer = new Xlsx($spreadsheet);
        $filename = 'data_siswa.xlsx';
    } elseif ($format === 'csv') {
        $writer = new Csv($spreadsheet);
        $filename = 'data_siswa.csv';
    }

    // Mengatur header untuk mendownload file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}
?>

<section class="content">
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Ekspor Data Siswa</h3>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label for="format">Pilih Format Ekspor</label>
                    <select id="format" name="format" class="form-control" required>
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                    </select>
                </div>
                <div class="mt-2">
                    <a href="?page=tagihan-siswa" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-success">Ekspor Data</button>
                </div>
            </form>
        </div>
    </div>
</section>