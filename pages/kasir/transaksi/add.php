<?php 
if (isset($_POST['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    try {
        $db->beginTransaction();

        // Validate if transaction already exists
        $validationSql = "SELECT * FROM transaksi WHERE id = :id";
        $stmtValidation = $db->prepare($validationSql);
        $stmtValidation->bindParam(':id', $_POST['id']);
        $stmtValidation->execute();

        if ($stmtValidation->rowCount() > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5>Gagal</h5>
                Data transaksi sudah ada
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        } else {
            // Retrieve current saldo
            $saldoSql = "SELECT saldo FROM uang_saku WHERE id = :uang_saku_id";
            $stmtSaldo = $db->prepare($saldoSql);
            $stmtSaldo->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
            $stmtSaldo->execute();
            $saldo = $stmtSaldo->fetch(PDO::FETCH_ASSOC)['saldo'];

            // Calculate total harga
            $totalHarga = $_POST['jumlah'] * $_POST['harga'];

            // Check if saldo is sufficient
            if ($saldo >= $totalHarga) {
                // Update saldo
                $updateSaldoSql = "UPDATE uang_saku SET saldo = saldo - :totalHarga WHERE id = :uang_saku_id";
                $stmtUpdateSaldo = $db->prepare($updateSaldoSql);
                $stmtUpdateSaldo->bindParam(':totalHarga', $totalHarga);
                $stmtUpdateSaldo->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
                $stmtUpdateSaldo->execute();

                // Insert new transaction
                $insertSql = "INSERT INTO transaksi (tanggal, uang_saku_id, barang_id, jumlah, harga, user_id) VALUES (:tanggal, :uang_saku_id, :barang_id, :jumlah, :harga, :user_id)";
                $stmt = $db->prepare($insertSql);
                $stmt->bindParam(':tanggal', $_POST['tanggal']);
                $stmt->bindParam(':uang_saku_id', $_POST['uang_saku_id']);
                $stmt->bindParam(':barang_id', $_POST['barang_id']);
                $stmt->bindParam(':jumlah', $_POST['jumlah']);
                $stmt->bindParam(':harga', $_POST['harga']);
                $stmt->bindParam(':user_id', $_POST['user_id']);

                if ($stmt->execute()) {
                    $db->commit();
                    $_SESSION['hasil'] = true;
                    $_SESSION['pesan'] = "Berhasil simpan data";
                } else {
                    $db->rollBack();
                    $_SESSION['hasil'] = false;
                    $_SESSION['pesan'] = "Gagal simpan data";
                }
            } else {
                $db->rollBack();
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Gagal</h5>
                    Saldo tidak cukup
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=transaksi'>";
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
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <input type="hidden" name="tanggal" value="<?php echo date("Y/m/d H:i:s") ?>">
                    <label for="uang_saku_id">Nama Santri</label>
                    <select name="uang_saku_id" class="form-select">
                        <option value="">- Pilih -</option>
                        <?php 
                        $database = new Database();
                        $db = $database->getConnection();

                        $selectUangSakuSQL = "SELECT uang_saku.id, siswa.nama FROM uang_saku INNER JOIN siswa ON uang_saku.siswa_id = siswa.id";
                        $stmtUangSaku = $db->prepare($selectUangSakuSQL);
                        $stmtUangSaku->execute();

                        while ($rowUangSaku = $stmtUangSaku->fetch(PDO::FETCH_ASSOC)){
                            echo "<option value='{$rowUangSaku['id']}'>{$rowUangSaku['nama']}</option>";
                        }
                        ?>
                    </select>
                    <label for="barang_id">Barang</label>
                    <select name="barang_id" id="barang_id" class="form-select" onchange="updateHarga()">
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
                    <input type="text" name="jumlah" class="form-control">
                    <input type="hidden" name="harga" id="harga" class="form-control">
                    <label for="user_id">Petugas</label>
                    <select name="user_id" class="form-select">
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
                <div class="mt-2">
                    <a href="?page=transaksi" class="btn btn-danger">Batal</a>
                    <button type="submit" name="button_create" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function updateHarga() {
        const barangSelect = document.getElementById('barang_id');
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        document.getElementById('harga').value = harga;
    }
</script>
