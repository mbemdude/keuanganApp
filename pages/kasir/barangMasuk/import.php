<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Fungsi untuk mengonversi format tanggal
function convertToDate($dateValue, $format = 'Y-m-d') {
    if (is_numeric($dateValue)) {
        // Jika tanggal dalam format angka Excel, konversi ke DateTime
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
    } else {
        // Jika tanggal dalam format teks, konversi ke DateTime
        $date = date_create_from_format('Y-m-d', $dateValue);
        if (!$date) {
            // Jika format gagal, coba format lain, seperti d-m-Y atau m/d/Y
            $date = date_create_from_format('d-m-Y', $dateValue) ?: date_create($dateValue);
        }
    }

    // Kembalikan format tanggal yang sesuai atau null jika gagal
    return $date ? $date->format($format) : null;
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
                $kode_barang        = $data[0];
                $nama_barang        = $data[1];
                $jumlah             = $data[2];
                $harga_beli         = $data[3];
                $tanggal_transaksi  = convertToDate($data[4]); // Konversi tanggal

                // Proses pengecekan barang dan penyimpanan ke database tetap sama
                $checkBarang = "SELECT id FROM barang WHERE kode_barang = ?";
                $stmtCheck = $db->prepare($checkBarang);
                $stmtCheck->execute([$kode_barang]);
                $barang = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($barang) {
                    $barang_id = $barang['id'];
                } else {
                    $insertBarang = "INSERT INTO barang (kode_barang, nama_barang) VALUES (?, ?)";
                    $stmtInsertBarang = $db->prepare($insertBarang);
                    $stmtInsertBarang->execute([$kode_barang, $nama_barang]);
                    $barang_id = $db->lastInsertId();
                }

                // Insert ke tabel barang_masuk
                $query = "INSERT INTO barang_masuk (barang_id, jumlah, harga_beli, tanggal_transaksi) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$barang_id, $jumlah, $harga_beli, $tanggal_transaksi]);
            }
            fclose($handle);
        }
    } elseif (in_array($ext, ['xls', 'xlsx'])) {
        // Jika file adalah Excel
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // Melewati header di baris pertama

            $kode_barang        = $worksheet->getCell("A$rowIndex")->getCalculatedValue();
            $nama_barang        = $worksheet->getCell("B$rowIndex")->getCalculatedValue();
            $jumlah             = $worksheet->getCell("C$rowIndex")->getCalculatedValue();
            $harga_beli         = $worksheet->getCell("D$rowIndex")->getCalculatedValue();
            $tanggal_transaksi  = convertToDate($worksheet->getCell("E$rowIndex")->getCalculatedValue()); // Konversi tanggal

            // Proses pengecekan barang dan penyimpanan ke database tetap sama
            $checkBarang = "SELECT id FROM barang WHERE kode_barang = ?";
            $stmtCheck = $db->prepare($checkBarang);
            $stmtCheck->execute([$kode_barang]);
            $barang = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($barang) {
                $barang_id = $barang['id'];
            } else {
                $insertBarang = "INSERT INTO barang (kode_barang, nama_barang) VALUES (?, ?)";
                $stmtInsertBarang = $db->prepare($insertBarang);
                $stmtInsertBarang->execute([$kode_barang, $nama_barang]);
                $barang_id = $db->lastInsertId();
            }

            // Insert ke tabel barang_masuk
            $query = "INSERT INTO barang_masuk (barang_id, jumlah, harga_beli, tanggal_transaksi) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$barang_id, $jumlah, $harga_beli, $tanggal_transaksi]);
        }
    } else {
        echo "Format file tidak didukung.";
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=barang-masuk'>";
    exit();
}

?>

<section class="content">
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Impor Barang Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Pilih File CSV atau Excel</label>
                            <input type="file" id="file" name="file" class="form-control" accept=".csv, .xls, .xlsx" required>
                        </div>
                        <div class="mt-2">
                            <a href="?page=barang-masuk" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-success">Impor Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card mx-3">
                <div class="card-header">
                    <h3 class="card-title">Tata Cara Import Siswa</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Pastikan format file Import bertipekan csv, xls, atau xlsx</li>
                        <li>Pastikan data yang diimport jika ada mengambil data dari tempat lain, data tersebut sudah terinputkan</li>
                        <ul>
                            <li>Contoh, kita memiliki 3 baris data siswa A, B, C masing masing siswa memiliki kunci utama yaitu berupa id. Id disini berupa angka yang otomatis bertambah sendiri jika ada inputan baru</li>
                        </ul>
                        <li>Lebih mudahnya bisa download contoh import data dibawah ini</li>
                        <a href="assets/sample/test_import_siswa.xlsx" class="btn btn-primary">Download Sample</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
