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
        for ($i = 0; $i < count($_POST['barang_id']); $i++) {
            $barang_id = $_POST['barang_id'][$i];
            $jumlah = (int)$_POST['jumlah'][$i];
            $harga = (float)$_POST['harga'][$i];

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
            for ($i = 0; $i < count($_POST['barang_id']); $i++) {
                $insertSql = "INSERT INTO transaksi (tanggal, uang_saku_id, barang_id, jumlah, harga, user_id) VALUES (:tanggal, :uang_saku_id, :barang_id, :jumlah, :harga, :user_id)";
                $stmt = $db->prepare($insertSql);
                $stmt->bindParam(':tanggal', $_POST['tanggal']);
                $stmt->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
                $stmt->bindParam(':barang_id', $_POST['barang_id'][$i]);
                $stmt->bindParam(':jumlah', $_POST['jumlah'][$i]);
                $stmt->bindParam(':harga', $_POST['harga'][$i]);
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

<div class="container mt-5">
        <h2>Input Transaksi</h2>
        <form method="POST">
            <input type="hidden" name="tanggal" value="<?php echo date("Y/m/d H:i:s") ?>">
            <input type="hidden" name="uang_saku_id" value="<?php echo $siswa['id']; ?>">

            <div class="form-group">
                <label for="barang_id">Barang</label>
                <select name="barang_id[]" id="barang_id" class="form-select" required>
                    <option value="">- Pilih -</option>
                    <?php 
                    $database = new Database();
                    $db = $database->getConnection();

                    $selectBarangSQL = "SELECT * FROM barang";
                    $stmtBarang = $db->prepare($selectBarangSQL);
                    $stmtBarang->execute();

                    while ($rowBarang = $stmtBarang->fetch(PDO::FETCH_ASSOC)){
                        echo "<option value='{$rowBarang['id']}' data-harga='{$rowBarang['harga']}'>{$rowBarang['nama_barang']}</option>";
                    }
                    ?>
                </select>
                <label for="jumlah">Qty</label>
                <input type="number" name="jumlah[]" class="form-control" required>
                <input type="hidden" name="harga[]" class="form-control">
                <label for="user_id">Petugas</label>
                <input type="text" name="user_id" class="form-select">
            </div>

            <button type="button" id="add-item" class="btn btn-secondary mt-3">Tambah Barang</button>

            <div id="items-container"></div>

            <div class="mt-3">
                <a href="?page=transaksi" class="btn btn-danger">Batal</a>
                <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.classList.add('form-group');
            newItem.innerHTML = 
               ` <div class="form-group">
                    <label for="barang_id">Barang</label>
                    <select name="barang_id[]" class="form-select" required>
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectBarangSQL = "SELECT * FROM barang";
                        $stmtBarang = $db->prepare($selectBarangSQL);
                        $stmtBarang->execute();

                        while ($rowBarang = $stmtBarang->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowBarang['id']}' data-harga='{$rowBarang['harga']}'>{$rowBarang['nama_barang']}</option>";
                        }
                        ?>
                    </select>
                    <label for="jumlah">Qty</label>
                    <input type="number" name="jumlah[]" class="form-control" required>
                    <input type="hidden" name="harga[]" class="form-control">
                </div>`
            ;
            container.appendChild(newItem);

            // Update harga saat barang dipilih
            container.querySelectorAll('select[name="barang_id[]"]').forEach(select => {
                select.addEventListener('change', function() {
                    const harga = this.options[this.selectedIndex].getAttribute('data-harga');
                    this.parentElement.querySelector('input[name="harga[]"]').value = harga;
                });
            });
        });

        // Update harga saat barang dipilih
        document.querySelectorAll('select[name="barang_id[]"]').forEach(select => {
            select.addEventListener('change', function() {
                const harga = this.options[this.selectedIndex].getAttribute('data-harga');
                this.parentElement.querySelector('input[name="harga[]"]').value = harga;
            });
        });
    </script>