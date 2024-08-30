<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan FROM tagihan_siswa";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Siswa ID');
    $sheet->setCellValue('B1', 'Tarif Pembayaran ID');
    $sheet->setCellValue('C1', 'Tanggal Tagihan');
    $sheet->setCellValue('D1', 'Nominal Tagihan');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['siswa_id']);
        $sheet->setCellValue('B' . $rowNumber, $row['tarif_pembayaran_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['tanggal_tagihan']);
        $sheet->setCellValue('D' . $rowNumber, $row['jumlah_tagihan']);
        $rowNumber++;
    }

    // Menghapus semua output buffer sebelum memulai proses export
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'tagihan_siswa.xlsx';

    // Mengatur header untuk mendownload file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}

// Handle impor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    $database = new Database();
    $db = $database->getConnection();

    if ($ext === 'csv') {
        // Jika file adalah CSV
        $handle = fopen($file, 'r');
        if ($handle !== FALSE) {
            // Melewati header file CSV
            fgetcsv($handle, 1000, ",");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $siswaId = $data[0];
                $tarifPembayaranId = $data[1];
                $tanggalTagihan = $data[2];
                $jumlahTagihan = $data[3];

                $query = "INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$siswaId, $tarifPembayaranId, $tanggalTagihan, $jumlahTagihan]);
            }
            fclose($handle);
        }
    } elseif (in_array($ext, ['xls', 'xlsx'])) {
        // Jika file adalah Excel
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            // Melewati header di baris pertama
            if ($rowIndex == 1) continue;

            // Mengambil nilai yang dihitung dari sel
            $siswaId = $worksheet->getCell("A$rowIndex")->getCalculatedValue();
            $tarifPembayaranId = $worksheet->getCell("B$rowIndex")->getCalculatedValue();
            $tanggalTagihan = $worksheet->getCell("C$rowIndex")->getCalculatedValue();
            $jumlahTagihan = $worksheet->getCell("D$rowIndex")->getCalculatedValue();

            if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($worksheet->getCell("C$rowIndex"))) {
                $tanggalTagihan = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalTagihan)->format('Y-m-d');
            } else {
                $tanggalTagihan = DateTime::createFromFormat('m/d/Y', $tanggalTagihan)->format('Y-m-d');
            }
            $query = "INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$siswaId, $tarifPembayaranId, $tanggalTagihan, $jumlahTagihan]);
        }
    } else {
        echo "Format file tidak didukung.";
    }
    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=tagihan-siswa'>";
    exit();
}
?>

<section class="content">
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Ekspor/Impor Data Tagihan Siswa</h3>
        </div>
        <div class="card-body">
            <form action="" method="post" class="mb-4">
                <div class="form-group">
                    <input type="hidden" name="export" value="1">
                    <button type="submit" class="btn btn-success">Ekspor Data (XLSX)</button>
                </div>
            </form>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Pilih File CSV atau Excel</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".csv, .xls, .xlsx" required>
                </div>
                <div class="mt-2">
                    <a href="?page=tagihan-siswa" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-success">Impor Data</button>
                </div>
            </form>
        </div>
    </div>
</section>
