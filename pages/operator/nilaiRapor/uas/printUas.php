<!DOCTYPE html>
<html>
<head>
    <title>Rapot Siswa - UAS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            text-align: center;
            padding: 0;
            margin: 0;
        }
        hr {
            border: 0.5px solid black;
        }
        .wali-teks {
            font-size: 12px;
        }
        header {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
        img {
            display: flex;
            max-width: 200px;
            height: 100px;
        }
        header p {
            margin: 1px 0;
        }
        .table-rapor {
            border: 1px solid #000;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            padding: 2px 5px;
        }
        .header-bio, .header-isi {
            font-size: 12px;
        }
        .hr-container {
            display: flex;
            justify-content: center;
        }
        .hr-short {
            width: 50%;
            background-color: black;
            margin: auto;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
<?php
$siswa_id = $_GET['siswa_id'] ?? null;
$tahun_ajaran_id = $_GET['tahun_ajaran_id'] ?? null;
$semester = $_GET['semester'] ?? null;

if (!$siswa_id || !$tahun_ajaran_id || !$semester) {
    die('Parameter tidak lengkap!');
}

$querySiswa = $db->prepare("SELECT s.*, k.kelas, ta.tahun_ajaran FROM siswa s JOIN kelas k ON s.kelas_id = k.id JOIN tahun_ajaran ta ON ta.id = ? WHERE s.id = ?");
$querySiswa->execute([$tahun_ajaran_id, $siswa_id]);
$siswa = $querySiswa->fetch();

$queryNilai = $db->prepare("
    SELECT mp.kategori, mp.mata_pelajaran AS mapel, nr.nilai_uas
    FROM nilai_rapor nr
    JOIN mata_pelajaran mp ON nr.mata_pelajaran_id = mp.id
    WHERE nr.siswa_id = ? AND nr.tahun_ajaran_id = ? AND nr.semester = ?
    ORDER BY mp.kategori, mp.mata_pelajaran
");
$queryNilai->execute([$siswa_id, $tahun_ajaran_id, $semester]);
$nilaiList = $queryNilai->fetchAll(PDO::FETCH_ASSOC);

$labelKategori = [
    'Agama' => '(Materi Agama) المواد الشرعية',
    'Bahasa' => '(Materi Bahasa) المواد اللغوية',
    'Umum' => '(Materi Umum) المواد العامة',
    '' => '(Tanpa Kategori) بدون تصنيف'
];
?>

<div class="container">
    <header>
        <div class="row align-items-center">
            <div class="col-2">
                <img src="assets/image/logo.png" alt="Logo Sekolah">
            </div>
            <div class="col-10">
                <p>بسم هللا الرحمن الرحيم</p>
                <p>قائمة النتائج للمدرسة المتوسطة</p>
                <p>Raport Penilaian Akhir Semester</p>
                <p>Pesantren Al Irsyad Tengaran 8 Martapura</p>
            </div>
        </div>
        <hr>
    </header>

    <table class="header-bio mb-4">
        <tr>
            <th><?= $siswa['kelas'] ?></th><th>Kelas / الفصل</th>
            <th><?= $siswa['nama'] ?></th><th>Siswa / الطالب</th>
        </tr>
        <tr>
            <th><?= $semester ?></th><th>Semester / السنة</th>
            <th><?= $siswa['nis'] ?></th><th>NIS</th>
        </tr>
        <tr>
            <th><?= $siswa['tahun_ajaran'] ?></th><th>Tahun Ajaran / العام الدراسي</th>
            <th>No Induk</th><th>NISN</th>
        </tr>
    </table>

    <?php
    $kategoriSebelumnya = null;
    $no = 1;
    foreach ($nilaiList as $nilai) {
        if ($nilai['kategori'] !== $kategoriSebelumnya) {
            if ($kategoriSebelumnya !== null) echo "</tbody></table><br>";
            echo "<table class='table-rapor header-isi'>";
            echo "<thead><tr><th>(Rata-rata kelas)</th><th>Huruf</th><th>(Nilai Santri)</th><th>(Pelajaran)</th><th>Nomor</th></tr></thead><tbody>";
            $judulKategori = $labelKategori[$nilai['kategori']] ?? "(Kategori: {$nilai['kategori']})";
            echo "<tr><td colspan='5'>$judulKategori</td></tr>";
            $no = 1;
            $kategoriSebelumnya = $nilai['kategori'];

        }
        // Ambil rata-rata nilai_uas siswa sekelas untuk mapel ini
        $queryRata = $db->prepare("
            SELECT SUM(nr.nilai_uas) / COUNT(DISTINCT s.id) AS rata_uas
            FROM nilai_rapor nr
            JOIN siswa s ON s.id = nr.siswa_id
            WHERE s.kelas_id = ?
            AND nr.tahun_ajaran_id = ?
            AND nr.semester = ?
            AND nr.mata_pelajaran_id = (
                SELECT id FROM mata_pelajaran WHERE mata_pelajaran = ? LIMIT 1
            )
        ");
        $queryRata->execute([$siswa['kelas_id'], $tahun_ajaran_id, $semester, $nilai['mapel']]);
        $rataRow = $queryRata->fetch(PDO::FETCH_ASSOC);
        $rataKelas = round($rataRow['rata_uas'] ?? 0, 1);
        
        echo "<tr>
            <td>{$rataKelas}</td>
            <td>" . ucwords(terbilang($nilai['nilai_uas'])) . "</td>
            <td>" . $nilai['nilai_uas'] . "</td>
            <td>" . htmlspecialchars($nilai['mapel']) . "</td>
            <td>" . $no . "</td>
        </tr>";
        $no++;
    }
    echo "</tbody></table>";
    ?>

    <div class="row text-center mt-5" style="font-size: 12px;">
        <div class="col-6"></div>
        <div class="col-6"><?= date('d/m/Y') ?> ،تحريرا مرتافورا</div>
    </div>
    <div class="row text-center" style="font-size: 12px;">
        <div class="col-6">ولي الطالب</div>
        <div class="col-6">ولي الفصل</div>
    </div>
    <div class="row text-center mb-5" style="font-size: 12px;">
        <div class="col-6">Wali Santri</div>
        <div class="col-6">Wali Kelas</div>
    </div>
    <div class="row text-center mt-3" style="font-size: 12px;">
        <div class="col-6 hr-container">
            <hr class="hr-short">
        </div>
        <div class="col-6 hr-container">
            <hr class="hr-short">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.print();
    window.onafterprint = function () {
        window.close();
    };
</script>
</body>
</html>
