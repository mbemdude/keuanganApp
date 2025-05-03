<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Handler;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handler Expor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $query = "SELECT s.*, j.jenjang, k.kelas, st.status FROM siswa s JOIN jenjang j ON s.jenjang_id = j.id JOIN kelas k ON s.kelas_id = k.id JOIN status st ON s.status_id = st.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'NIS');
    $sheet->setCellValue('B1', 'NAMA');
    $sheet->setCellValue('C1', 'ALAMAT');
    $sheet->setCellValue('D1', 'JENIS KELAMIN');
    $sheet->setCellValue('E1', 'JENJANG');
    $sheet->setCellValue('F1', 'KELAS');
    $sheet->setCellValue('G1', 'STATUS');
    $sheet->setCellValue('H1', 'JUMLAH SAUDARA');
    $sheet->setCellValue('I1', 'PEKERJAAN AYAH');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['nis']);
        $sheet->setCellValue('B' . $rowNumber, $row['nama']);
        $sheet->setCellValue('C' . $rowNumber, $row['alamat']);
        $sheet->setCellValue('D' . $rowNumber, $row['jenis_kelamin']);
        $sheet->setCellValue('E' . $rowNumber, $row['jenjang']);
        $sheet->setCellValue('F' . $rowNumber, $row['kelas']);
        $sheet->setCellValue('G' . $rowNumber, $row['status']);
        $sheet->setCellValue('H' . $rowNumber, $row['jumlah_saudara']);
        $sheet->setCellValue('I' . $rowNumber, $row['pekerjaan_ayah']);
        $rowNumber++;
    }
    
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_siswa.xlsx';

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
            fgetcsv($handle, 1000, ","); // Melewati header file CSV

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nis            = trim($data[0]);
                $nama           = trim($data[1]);
                $jenis_kelamin  = trim($data[2]);
                $jenjangNama    = trim($data[3]);
                $kelasNama      = trim($data[4]);
                $statusNama     = trim($data[5]);
                $pekerjaan_ayah = trim($data[6]);
                $jumlah_saudara = trim($data[7]);

                // 🔹 Cari ID jenjang berdasarkan nama
                $stmtJenjang = $db->prepare("SELECT id FROM jenjang WHERE jenjang = :jenjang");
                $stmtJenjang->bindParam(':jenjang', $jenjangNama);
                $stmtJenjang->execute();
                $jenjangRow = $stmtJenjang->fetch(PDO::FETCH_ASSOC);
                $jenjang_id = $jenjangRow ? $jenjangRow['id'] : null;

                // 🔹 Cari ID kelas berdasarkan nama
                $stmtkelas = $db->prepare("SELECT id FROM kelas WHERE kelas = :kelas");
                $stmtkelas->bindParam(':kelas', $kelasNama);
                $stmtkelas->execute();
                $kelasRow = $stmtkelas->fetch(PDO::FETCH_ASSOC);
                $kelas_id = $kelasRow ? $kelasRow['id'] : null;

                // 🔹 Cari ID status berdasarkan nama
                $stmtStatus = $db->prepare("SELECT id FROM status WHERE status = :status");
                $stmtStatus->bindParam(':status', $statusNama);
                $stmtStatus->execute();
                $status = $stmtStatus->fetch(PDO::FETCH_ASSOC);
                $status_id = $status ? $status['id'] : null;

                // 🔹 Hanya masukkan data jika ID ditemukan
                if ($jenjang_id && $kelas_id && $status_id) {
                    $query = "INSERT INTO siswa (nis, nama, jenis_kelamin, jenjang_id, kelas_id, status_id, jumlah_saudara, pekerjaan_ayah) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";      
                    $stmt = $db->prepare($query);
                    $stmt->execute([$nis, $nama, $jenis_kelamin, $jenjang_id, $kelas_id, $status_id, $jumlah_saudara, $pekerjaan_ayah]);
                }
            }
            fclose($handle);
        }
    } elseif (in_array($ext, ['xls', 'xlsx'])) {
        // Jika file adalah Excel
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // Melewati header

            $nis            = trim($worksheet->getCell("A$rowIndex")->getValue());
            $nama           = trim($worksheet->getCell("B$rowIndex")->getValue());
            $jenis_kelamin  = trim($worksheet->getCell("C$rowIndex")->getValue());
            $jenjangNama    = trim($worksheet->getCell("D$rowIndex")->getValue());
            $kelasNama      = trim($worksheet->getCell("E$rowIndex")->getValue());
            $statusNama     = trim($worksheet->getCell("F$rowIndex")->getValue());
            $pekerjaan_ayah = trim($worksheet->getCell("G$rowIndex")->getValue());
            $jumlah_saudara = trim($worksheet->getCell("H$rowIndex")->getValue());

            // 🔹 Cari ID jenjang berdasarkan nama
            $stmtJenjang = $db->prepare("SELECT id FROM jenjang WHERE jenjang = :jenjang");
            $stmtJenjang->bindParam(':jenjang', $jenjangNama);
            $stmtJenjang->execute();
            $jenjangRow = $stmtJenjang->fetch(PDO::FETCH_ASSOC);
            $jenjang_id = $jenjangRow ? $jenjangRow['id'] : null;

            // 🔹 Cari ID kelas berdasarkan nama
            $stmtkelas = $db->prepare("SELECT id FROM kelas WHERE kelas = :kelas");
            $stmtkelas->bindParam(':kelas', $kelasNama);
            $stmtkelas->execute();
            $kelasRow = $stmtkelas->fetch(PDO::FETCH_ASSOC);
            $kelas_id = $kelasRow ? $kelasRow['id'] : null;

            // 🔹 Cari ID status berdasarkan nama
            $stmtStatus = $db->prepare("SELECT id FROM status WHERE status = :status");
            $stmtStatus->bindParam(':status', $statusNama);
            $stmtStatus->execute();
            $status = $stmtStatus->fetch(PDO::FETCH_ASSOC);
            $status_id = $status ? $status['id'] : null;

            // 🔹 Hanya masukkan data jika ID ditemukan
            if ($jenjang_id && $kelas_id && $status_id) {
                $query = "INSERT INTO siswa (nis, nama, jenis_kelamin, jenjang_id, kelas_id, status_id, jumlah_saudara, pekerjaan_ayah) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";      
                $stmt = $db->prepare($query);
                $stmt->execute([$nis, $nama, $jenis_kelamin, $jenjang_id, $kelas_id, $status_id, $jumlah_saudara, $pekerjaan_ayah]);
            }
        }
    } else {
        echo "Format file tidak didukung.";
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=nilai-rapor'>";
    exit();
}
?>

<section class="content">
    <div class="row">
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
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Pilih File CSV atau Excel</label>
                            <input type="file" id="file" name="file" class="form-control" accept=".csv, .xls, .xlsx" required>
                        </div>
                        <div class="mt-2">
                            <a href="?page=nilai-rapor" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-success">Impor Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Tata Cara Import Presensi</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Pastikan format file Import bertipekan csv, xls, atau xlsx</li>
                        <li>Pastikan data yang diimport jika ada mengambil data dari tempat lain, data tersebut sudah terinputkan</li>
                        <ul>
                            <li>Contoh, kita memiliki 3 baris data siswa A, B, C masing masing siswa memiliki kunci utama yaitu berupa id. Id disini berupa angka yang otomatis bertambah sendiri jika ada inputan baru</li>
                        </ul>
                        <li>Lebih mudahnya bisa download contoh import data dibawah ini</li>
                        <a href="assets/sample/test_import_presensi_siswa.xlsx" class="btn btn-primary">Download Sample</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>