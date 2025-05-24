<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $query = "SELECT nr.*, s.nama, k.kelas, mp.mata_pelajaran, ta.tahun_ajaran FROM nilai_rapor nr JOIN siswa s ON nr.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id JOIN mata_pelajaran mp ON nr.mata_pelajaran_id = mp.id JOIN tahun_ajaran ta ON nr.tahun_ajaran_id = ta.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'Kelas');
    $sheet->setCellValue('C1', 'Mata Pelajaran');
    $sheet->setCellValue('D1', 'Nilai UTS');
    $sheet->setCellValue('E1', 'Semester');
    $sheet->setCellValue('F1', 'Tahun Ajaran');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['nama']);
        $sheet->setCellValue('B' . $rowNumber, $row['kelas']);
        $sheet->setCellValue('C' . $rowNumber, $row['mata_pelajaran']);
        $sheet->setCellValue('D' . $rowNumber, $row['nilai_uts']);
        $sheet->setCellValue('E' . $rowNumber, $row['semester']);
        $sheet->setCellValue('F' . $rowNumber, $row['tahun_ajaran']);
        $rowNumber++;
    }
    
    ob_end_clean();

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_nilai_siswa.xlsx';

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
            fgetcsv($handle); // skip header

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nis = trim($data[0]);
                $tahunAjaran = trim($data[1]);
                $mapel = trim($data[2]);
                $uts = is_numeric($data[3]) ? floatval($data[3]) : null;
                $semester = trim($data[4]);

                if (!$nis || !$tahunAjaran || !$mapel || !$semester) continue;

                $stmt = $db->prepare("SELECT id FROM siswa WHERE nis = ?");
                $stmt->execute([$nis]);
                $siswa_id = $stmt->fetchColumn();

                $stmt = $db->prepare("SELECT id FROM tahun_ajaran WHERE tahun_ajaran = ?");
                $stmt->execute([$tahunAjaran]);
                $tahun_ajaran_id = $stmt->fetchColumn();

                $stmt = $db->prepare("SELECT id FROM mata_pelajaran WHERE mata_pelajaran = ?");
                $stmt->execute([$mapel]);
                $mapel_id = $stmt->fetchColumn();

                if (!$siswa_id || !$tahun_ajaran_id || !$mapel_id) continue;

                $stmt = $db->prepare("SELECT id FROM nilai_rapor WHERE siswa_id=? AND mata_pelajaran_id=? AND tahun_ajaran_id=? AND semester=?");
                $stmt->execute([$siswa_id, $mapel_id, $tahun_ajaran_id, $semester]);
                $existing = $stmt->fetchColumn();

                if ($existing) {
                    $stmt = $db->prepare("UPDATE nilai_rapor SET nilai_uts=? WHERE id=?");
                    $stmt->execute([$uts, $existing]);
                } else {
                    $stmt = $db->prepare("INSERT INTO nilai_rapor (siswa_id, tahun_ajaran_id, mata_pelajaran_id, nilai_uts, semester) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$siswa_id, $tahun_ajaran_id, $mapel_id, $uts, $semester]);
                }
            }
            fclose($handle);
        }
    } elseif (in_array($ext, ['xls', 'xlsx'])) {
        $file = $_FILES['file']['tmp_name'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (in_array($ext, ['xls', 'xlsx'])) {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();

            foreach ($sheet->getRowIterator(2) as $row) {
                $cells = $row->getCellIterator();
                $cells->setIterateOnlyExistingCells(false);
                $values = [];
                foreach ($cells as $cell) {
                    $values[] = trim($cell->getCalculatedValue());
                }

                $nis         = $values[0];
                $tahunAjaran = $values[1];
                $mapel       = $values[2];
                $uts         = is_numeric($values[3]) ? floatval($values[3]) : null;
                $semester    = $values[4];

                if (!$nis || !$tahunAjaran || !$mapel || !$semester) continue;

                $stmt = $db->prepare("SELECT id FROM siswa WHERE nis = ?");
                $stmt->execute([$nis]);
                $siswa_id = $stmt->fetchColumn();

                $stmt = $db->prepare("SELECT id FROM tahun_ajaran WHERE tahun_ajaran = ?");
                $stmt->execute([$tahunAjaran]);
                $tahun_ajaran_id = $stmt->fetchColumn();

                $stmt = $db->prepare("SELECT id FROM mata_pelajaran WHERE mata_pelajaran = ?");
                $stmt->execute([$mapel]);
                $mapel_id = $stmt->fetchColumn();

                if (!$siswa_id || !$tahun_ajaran_id || !$mapel_id) continue;

                $stmt = $db->prepare("SELECT id FROM nilai_rapor WHERE siswa_id=? AND mata_pelajaran_id=? AND tahun_ajaran_id=? AND semester=?");
                $stmt->execute([$siswa_id, $mapel_id, $tahun_ajaran_id, $semester]);
                $existing = $stmt->fetchColumn();

                if ($existing) {
                    $stmt = $db->prepare("UPDATE nilai_rapor SET nilai_uts=? WHERE id=?");
                    $stmt->execute([$uts, $existing]);
                } else {
                    $stmt = $db->prepare("INSERT INTO nilai_rapor (siswa_id, tahun_ajaran_id, mata_pelajaran_id, nilai_uts, semester) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$siswa_id, $tahun_ajaran_id, $mapel_id, $uts, $semester]);
                }
            }
        }
    } else {
        echo "Format file tidak didukung.";
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=nilai-rapor-uts'>";
    exit();
}
?>

<section class="content">
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Ekspor/Impor Data</h3>
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
                            <a href="?page=nilai-rapor-uts" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-success">Impor Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Cara Import</h3>
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