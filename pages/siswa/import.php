<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                $kode = $data[0];
                $nis = $data[1];
                $nama = $data[2];
                $jenjang_id = $data[3];
                $kelas_id = $data[4];
                $status_id = $data[5];

                $query = "INSERT INTO siswa (kode, nis, nama, jenjang_id, kelas_id, status_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$kode, $nis, $nama, $jenjang_id, $kelas_id, $status_id]);
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

            $kode = $worksheet->getCell("A$rowIndex")->getValue();
            $nis = $worksheet->getCell("B$rowIndex")->getValue();
            $nama = $worksheet->getCell("C$rowIndex")->getValue();
            $jenjang_id = $worksheet->getCell("D$rowIndex")->getValue();
            $kelas_id = $worksheet->getCell("E$rowIndex")->getValue();
            $status_id = $worksheet->getCell("F$rowIndex")->getValue();

            $query = "INSERT INTO siswa (kode, nis, nama, jenjang_id, kelas_id, status_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$kode, $nis, $nama, $jenjang_id, $kelas_id, $status_id]);
        }
    } else {
        echo "Format file tidak didukung.";
    }
    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=siswa'>";
    exit();
} else {
    echo "File tidak ditemukan atau tidak valid.";
}
?>

<section class="content">
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Impor Data Tagihan Siswa</h3>
        </div>
        <div class="card-body">
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
