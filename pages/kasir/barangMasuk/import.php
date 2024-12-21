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

    // Validasi tipe file
    $mimeType = mime_content_type($file);
    if (!in_array($mimeType, ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
        echo "File yang diunggah tidak valid.";
        exit();
    }

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
                $kategori           = $data[2];
                $konversi_satuan    = $data[3];
                $jumlah             = $data[4];
                $harga_beli         = $data[5];
                $tanggal_transaksi  = convertToDate($data[6]); // Konversi tanggal
                $nama_supplier      = $data[7];
                $no_mitra           = $data[8];
                $stock_tambahan     = $konversi_satuan * $jumlah; // Hitung stock tambahan

                // Proses pengecekan barang dan penyimpanan ke database
                $checkBarang = "SELECT id FROM barang WHERE kode_barang = ?";
                $stmtCheck = $db->prepare($checkBarang);
                $stmtCheck->execute([$kode_barang]);
                $barang = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($barang) {
                    $barang_id = $barang['id'];
                } else {
                    $insertBarang = "INSERT INTO barang (kode_barang, nama_barang, kategori, konversi_satuan, stock) VALUES (?, ?, ?, ?, ?)";
                    $stmtInsertBarang = $db->prepare($insertBarang);
                    $stmtInsertBarang->execute([$kode_barang, $nama_barang, $kategori, $konversi_satuan, $stock_tambahan]);
                    $barang_id = $db->lastInsertId();
                }

                // Proses pengecekan supplier dan penyimpanan ke database
                $checkSupplier = "SELECT id FROM supplier WHERE no_mitra = ?";
                $stmtCheck = $db->prepare($checkSupplier);
                $stmtCheck->execute([$no_mitra]);
                $supplier = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($supplier) {
                    $supplier_id = $supplier['id'];
                } else {
                    $insertSupplier = "INSERT INTO supplier (nama_supplier, no_mitra) VALUES (?, ?)";
                    $stmtInsertSupplier = $db->prepare($insertSupplier);
                    $stmtInsertSupplier->execute([$nama_supplier, $no_mitra]);
                    $supplier_id = $db->lastInsertId();
                }

                // Insert ke tabel barang_masuk
                $query = "INSERT INTO barang_masuk (jumlah, harga_beli, tanggal_transaksi, barang_id, supplier_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$jumlah, $harga_beli, $tanggal_transaksi, $barang_id, $supplier_id]);
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
            $kategori           = $worksheet->getCell("C$rowIndex")->getCalculatedValue();
            $konversi_satuan    = $worksheet->getCell("D$rowIndex")->getCalculatedValue();
            $jumlah             = $worksheet->getCell("E$rowIndex")->getCalculatedValue();
            $harga_beli         = $worksheet->getCell("F$rowIndex")->getCalculatedValue();
            $tanggal_transaksi  = convertToDate($worksheet->getCell("G$rowIndex")->getCalculatedValue());
            $nama_supplier      = $worksheet->getCell("H$rowIndex")->getCalculatedValue();
            $no_mitra           = $worksheet->getCell("I$rowIndex")->getCalculatedValue();
            $stock_tambahan     = $konversi_satuan * $jumlah; // Hitung stock tambahan

            // Proses pengecekan barang dan penyimpanan ke database
            $checkBarang = "SELECT id FROM barang WHERE kode_barang = ?";
            $stmtCheck = $db->prepare($checkBarang);
            $stmtCheck->execute([$kode_barang]);
            $barang = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($barang) {
                $barang_id = $barang['id'];
            } else {
                $insertBarang = "INSERT INTO barang (kode_barang, nama_barang, kategori, konversi_satuan, stock) VALUES (?, ?, ?, ?, ?)";
                $stmtInsertBarang = $db->prepare($insertBarang);
                $stmtInsertBarang->execute([$kode_barang, $nama_barang, $kategori, $konversi_satuan, $stock_tambahan]);
                $barang_id = $db->lastInsertId();
            }

            // Proses pengecekan supplier dan penyimpanan ke database
            $checkSupplier = "SELECT id FROM supplier WHERE no_mitra = ?";
            $stmtCheck = $db->prepare($checkSupplier);
            $stmtCheck->execute([$no_mitra]);
            $supplier = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($supplier) {
                $supplier_id = $supplier['id'];
            } else {
                $insertSupplier = "INSERT INTO supplier (nama_supplier, no_mitra) VALUES (?, ?)";
                $stmtInsertSupplier = $db->prepare($insertSupplier);
                $stmtInsertSupplier->execute([$nama_supplier, $no_mitra]);
                $supplier_id = $db->lastInsertId();
            }

            // Insert ke tabel barang_masuk
            $query = "INSERT INTO barang_masuk (jumlah, harga_beli, tanggal_transaksi, barang_id, supplier_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$jumlah, $harga_beli, $tanggal_transaksi, $barang_id, $supplier_id]);
        }
    } else {
        echo "Format file tidak didukung.";
        exit();
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
                    <h3 class="card-title">Tata Cara Import Data</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Pastikan format file bertipe CSV, XLS, atau XLSX.</li>
                        <li>Pastikan data yang diimport sudah valid.</li>
                        <a href="assets/sample/test_import_siswa.xlsx" class="btn btn-primary">Download Sample</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
