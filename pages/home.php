                    <div class="row">
                        <?php if($role == 'Kasir' ||$role == 'Admin'): ?>
                        <div class="col-lg-4 col-6">
                            <div class="small-box text-bg-primary">
                                <div class="inner d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $totalTransaksiKasir ?></h3>
                                        <p>Transaksi Kasir</p>
                                    </div>
                                    <div class="icon">
                                        <i class="bi bi-cart" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($role == 'Admin'): ?>
                        <div class="col-lg-4 col-6">
                            <div class="small-box text-bg-warning">
                                <div class="inner d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $totalUser ?></h3>
                                        <p>User</p>
                                    </div>
                                    <div class="icon">
                                        <i class="bi bi-person-plus" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($role == 'Admin' ||$role == 'Keuangan' ||$role == 'Operator'):  ?>
                        <div class="col-lg-4 col-6">
                            <div class="small-box text-bg-danger">
                                <div class="inner d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $totalSiswa ?></h3>
                                        <p>Siswa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="bi bi-people" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                    More info <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div> 