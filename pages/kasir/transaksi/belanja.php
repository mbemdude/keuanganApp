<?php
// Cek jika sesi siswa ada
if (!isset($_SESSION['siswa'])) {
    header("Location: ?page=transaksi");
    exit;
}

$siswa = $_SESSION['siswa'];

if (isset($_POST['button_create'])) {
    $database = new Database();
    $db = $database->getConnection();

    try {
        $db->beginTransaction();

        // Ambil saldo saat ini
        $saldoSql = "SELECT saldo FROM uang_saku WHERE id = :uang_saku_id";
        $stmtSaldo = $db->prepare($saldoSql);
        $stmtSaldo->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
        $stmtSaldo->execute();
        $saldo = $stmtSaldo->fetch(PDO::FETCH_ASSOC)['saldo'];

        $totalHarga = 0;

        // Proses setiap barang
        foreach ($_POST['barang_id'] as $index => $barang_id) {
            $jumlah = (int)$_POST['jumlah'][$index];
            $harga = (float)$_POST['harga'][$index];

            // Cek stok barang
            $stokSql = "SELECT stock FROM barang WHERE id = :barang_id";
            $stmtStok = $db->prepare($stokSql);
            $stmtStok->bindParam(':barang_id', $barang_id);
            $stmtStok->execute();
            $stok = $stmtStok->fetch(PDO::FETCH_ASSOC)['stock'];

            if ($jumlah > $stok) {
                throw new Exception("Jumlah untuk barang ID $barang_id melebihi stok");
            }

            // Kurangi stok
            $updateStokSql = "UPDATE barang SET stock = stock - :jumlah WHERE id = :barang_id";
            $stmtUpdateStok = $db->prepare($updateStokSql);
            $stmtUpdateStok->bindParam(':jumlah', $jumlah);
            $stmtUpdateStok->bindParam(':barang_id', $barang_id);
            $stmtUpdateStok->execute();

            // Hitung total harga
            $totalHarga += $jumlah * $harga;
        }

        // Cek apakah saldo cukup
        if ($saldo >= $totalHarga) {
            // Kurangi saldo
            $updateSaldoSql = "UPDATE uang_saku SET saldo = saldo - :totalHarga WHERE id = :uang_saku_id";
            $stmtUpdateSaldo = $db->prepare($updateSaldoSql);
            $stmtUpdateSaldo->bindParam(':totalHarga', $totalHarga);
            $stmtUpdateSaldo->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
            $stmtUpdateSaldo->execute();

            // Simpan transaksi
            foreach ($_POST['barang_id'] as $index => $barang_id) {
                $insertSql = "INSERT INTO transaksi (tanggal, uang_saku_id, barang_id, jumlah, harga, user_id) VALUES (:tanggal, :uang_saku_id, :barang_id, :jumlah, :harga, :user_id)";
                $stmt = $db->prepare($insertSql);
                $stmt->bindParam(':tanggal', $_POST['tanggal']);
                $stmt->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
                $stmt->bindParam(':barang_id', $barang_id);
                $stmt->bindParam(':jumlah', $_POST['jumlah'][$index]);
                $stmt->bindParam(':harga', $_POST['harga'][$index]);
                $stmt->bindParam(':user_id', $_POST['user_id']);
                $stmt->execute();
            }

            $db->commit();
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil simpan data";
            echo "<meta http-equiv='refresh' content='0;url=?page=transaksi'>";
        } else {
            throw new Exception("Saldo tidak cukup");
        }
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal simpan data: " . $e->getMessage();
        echo "<meta http-equiv='refresh' content='0;url=?page=transaksi'>";
    }
}
?>

<section class="content">
    <div class="card mx-3">
        <div class="card-header">
            <h3 class="card-title">Transaksi, <span class="fw-bold"><?php echo $siswa['nama'] ?></span></h3>
        </div>
        <div class="card-body">
        <form method="POST">
            <input type="hidden" name="tanggal" value="<?php echo date("Y/m/d H:i:s") ?>">
            <input type="hidden" name="uang_saku_id" value="<?php echo $siswa['id']; ?>">

            <div class="form-group">
                <label for="barang_id">Barang</label>
                <select id="barang_id" class="form-select">
                    <option value="">- Pilih -</option>
                    <?php 
                    $database = new Database();
                    $db = $database->getConnection();

                    $selectBarangSQL = "SELECT id, kode_barang, nama_barang, harga FROM barang";
                    $stmtBarang = $db->prepare($selectBarangSQL);
                    $stmtBarang->execute();

                    while ($rowBarang = $stmtBarang->fetch(PDO::FETCH_ASSOC)){
                        echo "<option value='{$rowBarang['id']}' data-nama='{$rowBarang['nama_barang']}' data-harga='{$rowBarang['harga']}'>{$rowBarang['kode_barang']}-{$rowBarang['nama_barang']}</option>";
                    }
                    ?>
                </select>
                <label for="jumlah">Qty</label>
                <input type="number" id="jumlah" class="form-control">
                <input type="hidden" id="harga" class="form-control">
                <label for="user_id">Petugas</label>
                <select name="user_id" class="form-select" required>
                    <option value="">- Pilih -</option>
                    <?php 
                    $database = new Database();
                    $db = $database->getConnection();

                    $selectUserSQL = "SELECT * FROM user";
                    $stmtUser = $db->prepare($selectUserSQL);
                    $stmtUser->execute();

                    while ($rowUser = $stmtUser->fetch(PDO::FETCH_ASSOC)){
                        echo "<option value='{$rowUser['id']}'>{$rowUser['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="button" id="add-item" class="btn btn-secondary mt-3">Tambah Barang</button>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-container">
                    <!-- Items akan ditambahkan di sini -->
                </tbody>
            </table>

            <div class="mt-3">
                <a href="?page=transaksi" class="btn btn-danger">Batal</a>
                <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</section>

<script>
    // Fungsi untuk menambahkan item ke tabel
    function addItemToTable(barang_id, nama_barang, harga, jumlah, total) {
        const container = document.getElementById('items-container');
        const newItemRow = document.createElement('tr');

        newItemRow.innerHTML = `
            <td>${nama_barang}<input type="hidden" name="barang_id[]" value="${barang_id}"></td>
            <td>${jumlah}<input type="hidden" name="jumlah[]" value="${jumlah}"></td>
            <td>${harga}<input type="hidden" name="harga[]" value="${harga}"></td>
            <td>${total}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
        `;

        container.appendChild(newItemRow);

        // Add event listener to the new remove button
        newItemRow.querySelector('.remove-item').addEventListener('click', function() {
            newItemRow.remove();
        });
    }

    // Event listener untuk tombol "Tambah Barang"
    document.getElementById('add-item').addEventListener('click', function() {
        const barangSelect = document.getElementById('barang_id');
        const selectedBarang = barangSelect.options[barangSelect.selectedIndex];
        const barang_id = selectedBarang.value;
        const nama_barang = selectedBarang.getAttribute('data-nama');
        const jumlah = document.getElementById('jumlah').value;
        const harga = selectedBarang.getAttribute('data-harga');
        const total = jumlah * harga;

        if (barang_id && jumlah && harga) {
            addItemToTable(barang_id, nama_barang, harga, jumlah, total);
            
            // Reset form input setelah menambahkan item
            barangSelect.value = '';
            document.getElementById('jumlah').value = '';
            document.getElementById('harga').value = '';
        } else {
            alert('Mohon lengkapi semua informasi barang.');
        }
    });

    // Trigger klik tombol "Tambah Barang" saat memilih barang
    document.getElementById('barang_id').addEventListener('change', function() {
        const barangSelect = this;
        if (barangSelect.value) {
            // Programmatically click the "Tambah Barang" button
            document.getElementById('add-item').click();
        }
    });
</script>
