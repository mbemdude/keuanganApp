<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


// Handle ekspor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT tagihan_siswa_id, jumlah, tanggal_transaksi FROM transaksi_keuangan";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header ke file Excel
    $sheet->setCellValue('A1', 'Tagihan Siswa ID');
    $sheet->setCellValue('B1', 'Nominal Pembayaran');
    $sheet->setCellValue('C1', 'Tanggal Transaksi');

    // Menulis data siswa ke file Excel
    $rowNumber = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['tagihan_siswa_id']);
        $sheet->setCellValue('B' . $rowNumber, $row['jumlah']);
        $sheet->setCellValue('C' . $rowNumber, $row['tanggal_transaksi']);
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

// Handle import
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    $database = new Database();
    $db = $database->getConnection();

    if ($ext === 'csv') {
        // (kode untuk CSV tidak berubah)
    } elseif (in_array($ext, ['xls', 'xlsx'])) {
        // Jika file adalah Excel
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Melewati header file Excel
        array_shift($data);

        foreach ($data as $row) {
            $siswaId = $row[0];
            $jumlah = $row[1];
            $tanggalTransaksi = date('Y-m-d', strtotime($row[2])); // Konversi tanggal

            try {
                $db->beginTransaction();

                // Mendapatkan semua tagihan siswa dengan prioritas
                $query = "SELECT id, jumlah_tagihan 
                          FROM tagihan_siswa 
                          WHERE siswa_id = :siswa_id 
                          ORDER BY tarif_pembayaran_id ASC";
                $stmt = $db->prepare($query);
                $stmt->execute([':siswa_id' => $siswaId]);
                $tagihanSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($tagihanSiswa)) {
                    throw new Exception("Tagihan siswa dengan siswa ID: $siswaId tidak ditemukan.");
                }

                foreach ($tagihanSiswa as $tagihan) {
                    $tagihanSiswaId = $tagihan['id'];
                    $sisaTagihan = $tagihan['jumlah_tagihan'];

                    // Mengurangi tagihan sesuai jumlah yang tersedia
                    if ($jumlah > 0) {
                        if ($jumlah <= $sisaTagihan) {
                            $query = "UPDATE tagihan_siswa 
                                      SET jumlah_tagihan = jumlah_tagihan - :jumlah 
                                      WHERE id = :tagihan_siswa_id";
                            $stmt = $db->prepare($query);
                            $stmt->execute([
                                ':jumlah' => $jumlah,
                                ':tagihan_siswa_id' => $tagihanSiswaId
                            ]);
                            $jumlah = 0;
                        } else {
                            $query = "UPDATE tagihan_siswa 
                                      SET jumlah_tagihan = 0 
                                      WHERE id = :tagihan_siswa_id";
                            $stmt = $db->prepare($query);
                            $stmt->execute([':tagihan_siswa_id' => $tagihanSiswaId]);
                            $jumlah -= $sisaTagihan;
                        }
                    }
                }

                // Jika masih ada sisa, masukkan ke saldo uang saku
                if ($jumlah > 0) {
                    $query = "UPDATE uang_saku 
                              SET saldo = saldo + :jumlah 
                              WHERE siswa_id = :siswa_id";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        ':jumlah' => $jumlah,
                        ':siswa_id' => $siswaId
                    ]);
                }

                // Menyimpan transaksi keuangan
                $query = "INSERT INTO transaksi_keuangan (tagihan_siswa_id, jumlah, tanggal_transaksi) 
                          VALUES (:tagihan_siswa_id, :jumlah, :tanggal_transaksi)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':tagihan_siswa_id' => $tagihanSiswa[0]['id'], // Simpan pada transaksi pertama saja
                    ':jumlah' => $row[1], // Jumlah awal sebelum dibagi
                    ':tanggal_transaksi' => $tanggalTransaksi
                ]);

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                echo "Gagal melakukan transaksi: " . $e->getMessage();
            }
        }
    } else {
        echo "Format file tidak didukung.";
    }
    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil import data";
    echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
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
