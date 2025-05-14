<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $query = "SELECT p.*, s.nama, mp.mata_pelajaran FROM presensi p JOIN siswa s ON p.siswa_id = s.id JOIN mata_pelajaran mp ON p.mata_pelajaran_id = mp.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'Status');
    $sheet->setCellValue('C1', 'Tanggal');
    $sheet->setCellValue('D1', 'Mata Pelajaran');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['nama']);
        $sheet->setCellValue('B' . $rowNumber, $row['status']);
        $sheet->setCellValue('C' . $rowNumber, $row['tanggal']);
        $sheet->setCellValue('D' . $rowNumber, $row['mata_pelajaran']);
        $rowNumber++;
    }
    
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_presensi_siswa.xlsx';

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
                $namaSiswa      = trim($data[0]);
                $mataPelajaran  = trim($data[1]);
                $tanggal        = trim($data[2]);
                $status         = trim($data[3]);

                // 🔹 Cari ID siswa berdasarkan nama
                $stmtSiswa = $db->prepare("SELECT id FROM siswa WHERE nama = :nama");
                $stmtSiswa->bindParam(':nama', $namaSiswa);
                $stmtSiswa->execute();
                $siswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC);
                $siswa_id = $siswa ? $siswa['id'] : null;

                // 🔹 Cari ID mata pelajaran berdasarkan nama
                $stmtMapel = $db->prepare("SELECT id FROM mata_pelajaran WHERE mata_pelajaran = :mapel");
                $stmtMapel->bindParam(':mapel', $mataPelajaran);
                $stmtMapel->execute();
                $mapel = $stmtMapel->fetch(PDO::FETCH_ASSOC);
                $mata_pelajaran_id = $mapel ? $mapel['id'] : null;

                // 🔹 Hanya masukkan data jika ID ditemukan
                if ($siswa_id && $mata_pelajaran_id) {
                    $query = "INSERT INTO presensi (siswa_id, mata_pelajaran_id, tanggal, status) 
                              VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$siswa_id, $mata_pelajaran_id, $tanggal, $status]);
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

            $namaSiswa      = trim($worksheet->getCell("A$rowIndex")->getValue());
            $mataPelajaran  = trim($worksheet->getCell("B$rowIndex")->getValue());
            $tanggal        = convertToDate(trim($worksheet->getCell("C$rowIndex")->getCalculatedValue()));
            $status         = trim($worksheet->getCell("D$rowIndex")->getCalculatedValue());

            // 🔹 Cari ID siswa berdasarkan nama
            $stmtSiswa = $db->prepare("SELECT id FROM siswa WHERE nama = :nama");
            $stmtSiswa->bindParam(':nama', $namaSiswa);
            $stmtSiswa->execute();
            $siswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC);
            $siswa_id = $siswa ? $siswa['id'] : null;

            // 🔹 Cari ID mata pelajaran berdasarkan nama
            $stmtMapel = $db->prepare("SELECT id FROM mata_pelajaran WHERE mata_pelajaran = :mapel");
            $stmtMapel->bindParam(':mapel', $mataPelajaran);
            $stmtMapel->execute();
            $mapel = $stmtMapel->fetch(PDO::FETCH_ASSOC);
            $mata_pelajaran_id = $mapel ? $mapel['id'] : null;

            // 🔹 Hanya masukkan data jika ID ditemukan
            if ($siswa_id && $mata_pelajaran_id) {
                $query = "INSERT INTO presensi (siswa_id, mata_pelajaran_id, tanggal, status) 
                          VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$siswa_id, $mata_pelajaran_id, $tanggal, $status]);
            }
        }
    } else {
        echo "Format file tidak didukung.";
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=presensi'>";
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
                            <a href="?page=presensi" class="btn btn-danger">Batal</a>
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