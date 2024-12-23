<?php
    if (isset($_GET['id'])) {
        $database = new Database();
        $db = $database->getConnection();

        $id = $_GET['id'];
        $selectSql = "SELECT tk.id, tk.tanggal_transaksi, s.nama AS nama_siswa, tk.jumlah, k.kelas
                      FROM transaksi_keuangan tk 
                      JOIN tagihan_siswa ts ON tk.tagihan_siswa_id = ts.id 
                      JOIN siswa s ON ts.siswa_id = s.id 
                      JOIN kelas k ON s.kelas_id = k.id
                      WHERE tk.id = :id";
        $stmt = $db->prepare($selectSql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $dataPembayaran = $stmt->fetch(PDO::FETCH_ASSOC);
            // Pastikan tanggal ada atau gunakan fallback
            if (empty($dataPembayaran['tanggal'])) {
                $dataPembayaran['tanggal'] = date('Y-m-d'); // Gunakan tanggal saat ini
            }
            $nomor_kwitansi = date('Ymd', strtotime($dataPembayaran['tanggal'])) . str_pad($dataPembayaran['id'], 3, '0', STR_PAD_LEFT);
        } else {
            echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
            exit;
        }
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=transaksi-keuangan'>";
        exit;
    }
    header('Content-Type: text/html; charset=utf-8')
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
            margin-top: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .content p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        .print-button {
            margin-top: 20px;
            text-align: center;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Kwitansi Pembayaran</h2>
        </div>
        <div class="content">
            <p><strong>Nomor Kwitansi:</strong> <?php echo htmlspecialchars($nomor_kwitansi); ?></p>
            <p><strong>Tanggal:</strong> <?php echo date('d F Y', strtotime($dataPembayaran['tanggal'])); ?></p>
            <p><strong>Nama Siswa:</strong> <?php echo htmlspecialchars($dataPembayaran['nama_siswa']); ?></p>
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($dataPembayaran['kelas']); ?></p>
            <p><strong>Total Pembayaran:</strong> Rp<?php echo number_format($dataPembayaran['jumlah'], 0, ',', '.'); ?></p>
        </div>
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda.</p>
            <p><em>Admin Keuangan</em></p>
        </div>
    </div>

    <script>
        window.print();
        window.onafterprint = function () {
            window.close();
        }
    </script>
</body>
</html>