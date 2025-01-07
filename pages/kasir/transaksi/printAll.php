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
        /* .container {
            width: 80%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
            margin-top: 50px;
        } */
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
            margin-bottom: 20px;
            text-align: left; /* Menambahkan perataan kiri untuk konten */
        }
        .content p {
            margin: 5px 0;
        }
        .content p strong {
            display: inline-block;
            width: 150px; /* Menentukan lebar label yang konsisten */
            text-align: left;
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
        <table class="table table-bordered">
            <thead class="table">
                <tr>
                    <th>No</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Siswa</th>
                    <th>Merk Barang</th>
                    <th>Jumlah Yang Dibeli</th>
                    <th>Harga Barang</th>
                    <th>Total Harga</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $database = new Database();
                    $db = $database->getConnection();
                    
                    $selectSql = "SELECT t.*, s.nama AS nama_siswa, b.nama_barang, b.harga, u.nama AS petugas FROM transaksi t LEFT JOIN uang_saku us ON t.uang_saku_id = us.id LEFT JOIN siswa s ON us.siswa_id = s.id LEFT JOIN barang b ON t.barang_id = b.id LEFT JOIN users u ON t.user_id = u.id";
                    $stmt = $db->prepare($selectSql);
                    $stmt->execute();
                    $row_data = $stmt->rowCount();

                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){   
                ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $row['tanggal'] ?></td>
                    <td><?php echo $row['nama_siswa'] ?></td>
                    <td><?php echo $row['nama_barang'] ?></td>
                    <td><?php echo $row['jumlah'] ?></td>
                    <td><?php echo rupiah($row['harga']) ?></td>
                    <td><?php echo rupiah($row['harga'] * $row['jumlah']) ?></td>
                    <td><?php echo $row['petugas'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        window.print();
        window.onafterprint = function () {
            window.close();
        }
    </script>
</body>
</html>
