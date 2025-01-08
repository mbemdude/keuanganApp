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
            if (empty($dataPembayaran['tanggal'])) {
                $dataPembayaran['tanggal'] = date('Y-m-d');
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
    header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
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
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header img {
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            margin-top: 20px;
            font-size: 23px;
            font-weight: bold;
        }
        .header-line {
            border-top: 2px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .header-line-bottom {
            border-top: 2px solid #000;
            margin-top: -8px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 10px;
            text-align: left; /* Menambahkan perataan kiri untuk konten */
        }
        .content h1 {
            margin-bottom: 50px;
        }
        .content p {
            margin: 5px 0;
            font-size: 20px;
        }
        .content p strong {
            display: inline-block;
            width: 200px; /* Menentukan lebar label yang konsisten */
            text-align: left;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        .footer-text {
            position: absolute;
            bottom: 20px;
            right: 20px;
            text-align: right;
        }
        .footer-text p {
            margin: 0;
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
    <!-- <div class="container"> -->
        <div class="header">
            <img src="assets/image/logoats3.png" alt="Logo Yayasan">
            <div class="header-text">
                <h2>Yayasan Sa'adah Martapura</h2>
                <p>Jl. Sa'adah 3, Sungai Paring, Martapura, Banjar, Kalimantan Selatan <br> Telp +62 5115913230 Atau +62 81380852013</p>
            </div>
        </div>
        <div class="header-line"></div>
        <div class="header-line-bottom"></div>
        <div class="content">
            <h1 align="center">KWITANSI PEMBAYARAN</h1>
            <p><strong>Nomor Kwitansi:</strong> <?php echo htmlspecialchars($nomor_kwitansi); ?></p>
            <p><strong>Nama Siswa:</strong> <?php echo htmlspecialchars($dataPembayaran['nama_siswa']); ?></p>
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($dataPembayaran['kelas']); ?></p>
            <p><strong>Total Pembayaran:</strong> Rp<?php echo number_format($dataPembayaran['jumlah'], 0, ',', '.'); ?></p>
        </div>
        <div class="footer-text">
            <p>Martapura, <?php echo strftime('%d %B %Y', strtotime($dataPembayaran['tanggal'])); ?></p>
            <br>
            <br>
            <br>
            <p><br>Yayasan Sa'adah Martapura</p>
        </div>
    <!-- </div> -->

    <script>
        window.print();
        window.onafterprint = function () {
            window.close();
        }
    </script>
</body>
</html>