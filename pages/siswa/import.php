<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT s.*, j.jenjang, k.kelas, st.status FROM siswa JOIN jenjang j ON s.jenjang_id = j.id JOIN kelas k ON s.kelas_id = k.id JOIN status st ON s.status_id = st.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Kode');
    $sheet->setCellValue('C1', 'NIS');
    $sheet->setCellValue('D1', 'Nama');
    $sheet->setCellValue('E1', 'Alamat');
    $sheet->setCellValue('F1', 'Jenis Kelamin');
    $sheet->setCellValue('G1', 'Jenjang');
    $sheet->setCellValue('H1', 'Kelas');
    $sheet->setCellValue('I1', 'Status');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['kode']);
        $sheet->setCellValue('C' . $rowNumber, $row['nis']);
        $sheet->setCellValue('D' . $rowNumber, $row['nama']);
        $sheet->setCellValue('E' . $rowNumber, $row['alamat']);
        $sheet->setCellValue('F' . $rowNumber, $row['jenis_kelamin']);
        $sheet->setCellValue('G' . $rowNumber, $row['jenjang']);
        $sheet->setCellValue('H' . $rowNumber, $row['kelas']);
        $sheet->setCellValue('I' . $rowNumber, $row['status']);
        $rowNumber++;
    }

    // Menghapus semua output buffer sebelum memulai proses export
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_siswa.xlsx';

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
                $kode           = $data[0];
                $nis            = $data[1];
                $nama           = $data[2];
                $alamat         = $data[3];
                $jenis_kelamin  = $data[4];
                $jenjang_id     = $data[5];
                $kelas_id       = $data[6];
                $status_id      = $data[7];

                $query = "INSERT INTO siswa (kode, nis, nama, alamat, jenis_kelamin, jenjang_id, kelas_id, status_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $db->prepare($query);
                $stmt->execute([$kode, $nis, $nama, $alamat, $jenis_kelamin, $jenjang_id, $kelas_id, $status_id]);
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
            $kode = $worksheet->getCell("A$rowIndex")->getCalculatedValue();
            $nis = $worksheet->getCell("B$rowIndex")->getCalculatedValue();
            $nama = $worksheet->getCell("C$rowIndex")->getCalculatedValue();
            $alamat = $worksheet->getCell("D$rowIndex")->getCalculatedValue();
            $jenis_kelamin = $worksheet->getCell("E$rowIndex")->getCalculatedValue();
            $jenjang_id = $worksheet->getCell("F$rowIndex")->getCalculatedValue();
            $kelas_id = $worksheet->getCell("G$rowIndex")->getCalculatedValue();
            $status_id = $worksheet->getCell("H$rowIndex")->getCalculatedValue();

            $query = "INSERT INTO siswa (kode, nis, nama, alamat, jenis_kelamin, jenjang_id, kelas_id, status_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$kode, $nis, $nama, $alamat, $jenis_kelamin, $jenjang_id, $kelas_id, $status_id]);
        }
    } else {
        echo "Format file tidak didukung.";
    }
    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    exit();
}
?>

<section class="content">
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
