<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT s.nama, 
    MAX(CASE WHEN tb.jenis_pembayaran_id = '1' THEN ts.jumlah_tagihan END) AS uang_pangkal, 
    MAX(CASE WHEN tb.jenis_pembayaran_id = '2' THEN ts.jumlah_tagihan END) AS daftar_ulang, 
    MAX(CASE WHEN tb.jenis_pembayaran_id = '3' THEN ts.jumlah_tagihan END) AS spp, 
    tb.tipe, ts.tanggal_tagihan, k.kelas, j.jenjang FROM tagihan_siswa ts 
    JOIN siswa s ON ts.siswa_id=s.id JOIN kelas k ON s.kelas_id = k.id JOIN jenjang j ON s.jenjang_id = j.id 
    JOIN tarif_pembayaran tb ON ts.tarif_pembayaran_id=tb.id JOIN jenis_pembayaran tpb ON tb.jenis_pembayaran_id=tpb.id 
    GROUP BY s.nama, tb.tipe";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'Kelas');
    $sheet->setCellValue('C1', 'Jenjang');
    $sheet->setCellValue('D1', 'Uang Pangkal');
    $sheet->setCellValue('E1', 'Daftar Ulang');
    $sheet->setCellValue('F1', 'SPP');
    $sheet->setCellValue('G1', 'Tipe');
    $sheet->setCellValue('H1', 'Tanggal Tagihan');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['nama']);
        $sheet->setCellValue('B' . $rowNumber, $row['kelas']);
        $sheet->setCellValue('C' . $rowNumber, $row['jenjang']);
        $sheet->setCellValue('D' . $rowNumber, $row['uang_pangkal']);
        $sheet->setCellValue('E' . $rowNumber, $row['daftar_ulang']);
        $sheet->setCellValue('F' . $rowNumber, $row['spp']);
        $sheet->setCellValue('G' . $rowNumber, $row['tipe']);
        $sheet->setCellValue('H' . $rowNumber, $row['tanggal_tagihan']);
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
    <div class="row">
        <div class="col-lg-6 col-sm-12">
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
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Cara Penggunaan Impor</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Pastikan format file Impor bertipekan csv, xls, atau xlsx</li>
                        <li>Pastikan data yang diimpor jika ada mengambil data dari tempat lain, data tersebut sudah terinputkan</li>
                        <ul>
                            <li>Contoh, kita memiliki 3 baris data siswa A, B, C masing masing siswa memiliki kunci utama yaitu berupa id. Id disini berupa angka yang otomatis bertambah sendiri jika ada inputan baru</li>
                        </ul>
                        <li>Lebih mudahnya bisa download contoh impor data dibawah ini</li>
                        <a href="assets/sample/test_import_tagihan_siswa.xlsx" class="btn btn-primary">Download Sample</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</section>
