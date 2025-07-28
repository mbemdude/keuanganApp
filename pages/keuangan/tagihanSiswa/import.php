<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT s.nama, 
        MAX(CASE WHEN tb.jenis_pembayaran_id = '1' THEN ts.jumlah_tagihan END) AS uang_pangkal, 
        MAX(CASE WHEN tb.jenis_pembayaran_id = '2' THEN ts.jumlah_tagihan END) AS daftar_ulang, 
        MAX(CASE WHEN tb.jenis_pembayaran_id = '3' THEN ts.jumlah_tagihan END) AS spp, 
        tb.tipe, ts.tanggal_tagihan, k.kelas, j.jenjang 
        FROM tagihan_siswa ts 
        JOIN siswa s ON ts.siswa_id=s.id 
        JOIN kelas k ON s.kelas_id = k.id 
        JOIN jenjang j ON s.jenjang_id = j.id 
        JOIN tarif_pembayaran tb ON ts.tarif_pembayaran_id=tb.id 
        JOIN jenis_pembayaran tpb ON tb.jenis_pembayaran_id=tpb.id 
        GROUP BY s.nama, tb.tipe";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'Kelas');
    $sheet->setCellValue('C1', 'Jenjang');
    $sheet->setCellValue('D1', 'Uang Pangkal');
    $sheet->setCellValue('E1', 'Daftar Ulang');
    $sheet->setCellValue('F1', 'SPP');
    $sheet->setCellValue('G1', 'Tipe');
    $sheet->setCellValue('H1', 'Tanggal Tagihan');

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

    ob_end_clean();
    $writer = new Xlsx($spreadsheet);
    $filename = 'tagihan_siswa.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    $database = new Database();
    $db = $database->getConnection();

    if (in_array($ext, ['xls', 'xlsx'])) {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestDataRow();

        for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
            $nis            = trim($worksheet->getCell("A$rowIndex")->getValue());
            $tipePembayaran = trim($worksheet->getCell("B$rowIndex")->getCalculatedValue());
            $tahunAjaran    = trim($worksheet->getCell("C$rowIndex")->getValue());

            $stmtSiswa = $db->prepare("SELECT id, jenjang_id FROM siswa WHERE nis = ?");
            $stmtSiswa->execute([$nis]);
            $siswa = $stmtSiswa->fetch(PDO::FETCH_ASSOC);
            if (!$siswa) continue;
            $siswa_id = $siswa['id'];
            $jenjang_id = $siswa['jenjang_id'];

            $stmtTahun = $db->prepare("SELECT id FROM tahun_ajaran WHERE tahun_ajaran = ?");
            $stmtTahun->execute([$tahunAjaran]);
            $tahunRow = $stmtTahun->fetch(PDO::FETCH_ASSOC);
            if (!$tahunRow) continue;
            $tahun_ajaran_id = $tahunRow['id'];

            $stmtTarif = $db->prepare("SELECT id, nominal FROM tarif_pembayaran WHERE jenjang_id = ? AND tahun_ajaran_id = ? AND tipe = ?");
            $stmtTarif->execute([$jenjang_id, $tahun_ajaran_id, $tipePembayaran]);
            $tarifList = $stmtTarif->fetchAll(PDO::FETCH_ASSOC);
            if (!$tarifList) continue;

            foreach ($tarifList as $tarif) {
                $tarif_pembayaran_id = $tarif['id'];
                $jumlahTagihan = $tarif['nominal'];

                $stmtInsert = $db->prepare("INSERT INTO tagihan_siswa (siswa_id, tarif_pembayaran_id, tanggal_tagihan, jumlah_tagihan) VALUES (?, ?, NOW(), ?)");
                $stmtInsert->execute([$siswa_id, $tarif_pembayaran_id, $jumlahTagihan]);
            }
        }
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data tagihan siswa";
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
                        <a href="assets/sample/Sample Import Tagihan Siswa.xlsx" class="btn btn-primary">Download Sample</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</section>
