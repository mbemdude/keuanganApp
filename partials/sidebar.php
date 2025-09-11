        <aside class="app-sidebar bg-body-secondary shadow no-print" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="?page=home" class="brand-link">
                    <img src="assets/image/logoats3.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> 
                    <span class="brand-text fw-light">Yayasan Sa'adah</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item"> 
                            <a href="?page=home" class="nav-link active"> 
                                <i class="nav-icon bi bi-house-fill"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <?php if($role == 'Admin' || $role == 'Keuangan' || $role == 'Operator'): ?>
                        <li class="nav-item"> 
                            <a href="?page=siswa" class="nav-link"> 
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Siswa</p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if($role == 'Admin' || $role == 'Keuangan'): ?>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Keuangan
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=uang-saku" class="nav-link"> 
                                        <i class="nav-icon bi bi-wallet-fill"></i>
                                        <p>Uang Saku</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=tarif-pembayaran" class="nav-link"> 
                                        <i class="nav-icon bi bi-receipt"></i>
                                        <p>Tarif Pembayaran</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=tagihan-siswa" class="nav-link"> 
                                        <i class="nav-icon bi bi-cash-coin"></i>
                                        <p>Tagihan Siswa</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=transaksi-keuangan" class="nav-link"> 
                                        <i class="nav-icon bi bi-credit-card-2-back-fill"></i>
                                        <p>Transaksi Keuangan</p>
                                    </a> 
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <?php if($role == 'Admin' || $role == 'Kasir'): ?>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-cart-fill"></i>
                                <p>
                                    Kasir
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=supplier" class="nav-link"> 
                                        <i class="nav-icon bi bi-truck"></i>
                                        <p>Supplier</p>
                                    </a> 
                                    <a href="?page=barang-masuk" class="nav-link"> 
                                        <i class="nav-icon bi bi-box-arrow-in-down"></i>
                                        <p>Barang Masuk</p>
                                    </a> 
                                    <a href="?page=barang" class="nav-link"> 
                                        <i class="nav-icon bi bi-box-seam"></i>
                                        <p>Stock Barang</p>
                                    </a> 
                                    <a href="?page=transaksi" class="nav-link"> 
                                        <i class="nav-icon bi bi-receipt"></i>
                                        <p>Transaksi</p>
                                    </a> 
                                </li>
                            </ul>
                        </li>
                        <?php endif ?>

                        <?php if($role == 'Operator' || $role == 'Admin'): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-person-fill-gear"></i>
                                <p>
                                    Operator
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="?page=presensi" class="nav-link">
                                        <i class="nav-icon bi bi-calendar-check"></i>
                                        <p>Presensi Siswa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-clipboard-data"></i>
                                        <p>
                                            Nilai Rapor
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="?page=nilai-rapor-uts" class="nav-link">
                                                <i class="nav-icon bi bi-journal-text"></i>
                                                <p>
                                                    UTS
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="?page=nilai-rapor-uas" class="nav-link">
                                                <i class="nav-icon bi bi-journal-check"></i>
                                                <p>
                                                    UAS
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <?php endif ?>
                        
                        <?php if($role == 'Admin' || $role == 'Operator' || $role =='Keuangan'): ?>
                        <li class="nav-header">
                             <span>Master Data</span>
                        </li>
                        <?php endif ?>
                        <?php if($role == 'Admin' || $role == 'Operator'): ?>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-archive-fill"></i>
                                <p>
                                    Data Admin
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=jenjang" class="nav-link"> 
                                        <i class="nav-icon bi bi-building"></i>
                                        <p>Jenjang</p>
                                    </a>
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=kelas" class="nav-link"> 
                                        <i class="nav-icon bi bi-door-open"></i>
                                        <p>Kelas</p>
                                    </a>
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=status" class="nav-link"> 
                                        <i class="nav-icon bi bi-check-circle-fill"></i>
                                        <p>Status</p>
                                    </a>
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=tahun-ajaran" class="nav-link"> 
                                        <i class="nav-icon bi bi-calendar-range-fill"></i>
                                        <p>Tahun Ajaran</p>
                                    </a>
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=mata-pelajaran" class="nav-link"> 
                                        <i class="nav-icon bi bi-book-half"></i>
                                        <p>Mata Pelajaran</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif ?>
                        <?php if($role == 'Admin' || $role == 'Keuangan'): ?>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-archive-fill"></i>
                                <p>
                                    Data Keuangan
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=jenis-pembayaran" class="nav-link"> 
                                        <i class="nav-icon bi bi-tags-fill"></i>
                                        <p>Jenis Pembayaran</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif ?>
                        <?php if($role == 'Admin'): ?>
                        <li class="nav-header">
                             <span>Admin</span>
                        </li>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-shield-lock-fill"></i>
                                <p>
                                    Pengaturan User
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=role" class="nav-link"> 
                                        <i class="nav-icon bi bi-person-badge"></i>
                                        <p>Role</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=user" class="nav-link"> 
                                        <i class="nav-icon bi bi-person-plus"></i>
                                        <p>User</p>
                                    </a> 
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-clipboard2-fill"></i>
                                <p>
                                    Perhitungan SPK
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="?page=kriteria" class="nav-link"> 
                                        <i class="nav-icon bi bi-clipboard2-pulse"></i>
                                        <p>Kriteria</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="?page=nilai-alternatif" class="nav-link"> 
                                        <i class="nav-icon bi bi-card-checklist"></i>
                                        <p>Nilai Alternatif</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" class="nav-link"> 
                                        <i class="nav-icon bi bi-diagram-3"></i>
                                        <p>
                                            SAW
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a> 
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item"> 
                                            <a href="?page=normalisasi" class="nav-link"> 
                                                <i class="nav-icon bi bi-filter-square-fill"></i>
                                                <p>Normalisasi SAW</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=perhitungan-saw" class="nav-link"> 
                                                <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                                                <p>Perhitungan SAW</p>
                                            </a> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-diagram-3"></i>
                                        <p>
                                            AHP
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item"> 
                                            <a href="?page=perbandingan-kriteria" class="nav-link"> 
                                                <i class="nav-icon bi bi-list-columns-reverse"></i>
                                                <p>Perbandingan Kriteria</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=bobot-kriteria" class="nav-link"> 
                                                <i class="nav-icon bi bi-sliders2"></i>
                                                <p>Bobot Kriteria</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=normalisasi-ahp" class="nav-link"> 
                                                <i class="nav-icon bi bi-filter-circle"></i>
                                                <p>Normalisasi AHP</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=perhitungan-ahp" class="nav-link"> 
                                                <i class="nav-icon bi bi-award-fill"></i>
                                                <p>Perhitungan AHP</p>
                                            </a> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-diagram-3"></i>
                                        <p>
                                            WP
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item"> 
                                            <a href="?page=perbaikan-bobot-wp" class="nav-link"> 
                                                <i class="nav-icon bi bi-list-columns-reverse"></i>
                                                <p>Perbaikan Bobot</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=hasil-perhitungan-wp" class="nav-link"> 
                                                <i class="nav-icon bi bi-sliders2"></i>
                                                <p>Hasil Perhitungan</p>
                                            </a> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-diagram-3"></i>
                                        <p>
                                            TOPSIS
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item"> 
                                            <a href="?page=perbandingan-kriteria" class="nav-link"> 
                                                <i class="nav-icon bi bi-list-columns-reverse"></i>
                                                <p>Perbandingan Kriteria</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=bobot-kriteria" class="nav-link"> 
                                                <i class="nav-icon bi bi-sliders2"></i>
                                                <p>Bobot Kriteria</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=normalisasi-ahp" class="nav-link"> 
                                                <i class="nav-icon bi bi-filter-circle"></i>
                                                <p>Normalisasi AHP</p>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a href="?page=perhitungan-ahp" class="nav-link"> 
                                                <i class="nav-icon bi bi-award-fill"></i>
                                                <p>Perhitungan AHP</p>
                                            </a> 
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"> 
                            <a href="#" class="nav-link"> 
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Cetak Laporan
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> 
                                    <a href="#" onclick="printTagihanSiswaAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-file-earmark-text"></i>
                                        <p>Laporan Tagihan Siswa</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printTransactionAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-cash-coin"></i>
                                        <p>Laporan Transaksi Keuangan</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printBarangMasukAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-box-seam"></i>
                                        <p>Laporan Barang Masuk</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printTransaksiKasirAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-receipt"></i>
                                        <p>Laporan Transaksi Kasir</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printPresensiAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-person-check"></i>
                                        <p>Laporan Presensi Siswa</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printPerhitunganSawAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-bar-chart-line"></i>
                                        <p>Laporan Hasil SAW</p>
                                    </a> 
                                </li>
                                <li class="nav-item"> 
                                    <a href="#" onclick="printPerhitunganAhpAll()" class="nav-link"> 
                                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                                        <p>Laporan Hasil AHP</p>
                                    </a> 
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul> 
                </nav>
            </div> 
        </aside> 