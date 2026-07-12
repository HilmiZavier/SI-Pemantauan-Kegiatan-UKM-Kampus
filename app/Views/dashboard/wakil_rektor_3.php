<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Dashboard Wakil Rektor III</h1>
    <p class="page-subtitle">Selamat datang kembali, Bidang Kemahasiswaan & Alumni. Berikut ringkasan status kegiatan UKM Kampus.</p>
</div>

<!-- Statistik Ringkas (Stat Cards) -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $total_ukm ?></div>
                <div class="stat-label">Total UKM Aktif</div>
            </div>
            <div class="stat-icon-wrapper">
                <i class="bi bi-shield-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $total_kegiatan ?></div>
                <div class="stat-label">Total Kegiatan</div>
            </div>
            <div class="stat-icon-wrapper" style="--primary: #10b981; --primary-light: rgba(16, 185, 129, 0.1); --primary-gradient: linear-gradient(135deg, #10b981, #34d399);">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div>
                <div class="stat-value text-info"><?= $proposal_menunggu ?></div>
                <div class="stat-label">Proposal Butuh Persetujuan</div>
            </div>
            <div class="stat-icon-wrapper" style="--primary: #06b6d4; --primary-light: rgba(6, 182, 212, 0.1); --primary-gradient: linear-gradient(135deg, #06b6d4, #22d3ee);">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div>
                <div class="stat-value text-info"><?= $lpj_menunggu ?></div>
                <div class="stat-label">LPJ Butuh Persetujuan</div>
            </div>
            <div class="stat-icon-wrapper" style="--primary: #f59e0b; --primary-light: rgba(245, 158, 11, 0.1); --primary-gradient: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <i class="bi bi-file-earmark-check-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Row Kedua: Ringkasan Pengajuan Proposal & LPJ ke WR3 -->
<div class="row g-4 mb-5">
    <!-- Proposal Persetujuan -->
    <div class="col-md-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-file-text me-2 text-primary"></i>Statistik Proposal (Wakil Rektor III)</h5>
            </div>
            <div class="card-premium-body">
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-info mb-1"><?= $proposal_menunggu ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Menunggu</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-success mb-1"><?= $proposal_disetujui ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-warning mb-1"><?= $proposal_revisi ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Revisi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LPJ Persetujuan -->
    <div class="col-md-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-file-check me-2 text-success"></i>Statistik LPJ (Wakil Rektor III)</h5>
            </div>
            <div class="card-premium-body">
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-info mb-1"><?= $lpj_menunggu ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Menunggu</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-success mb-1"><?= $lpj_disetujui ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <h3 class="fw-bold text-warning mb-1"><?= $lpj_revisi ?></h3>
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Revisi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row Ketiga: Task Approval Terkini -->
<div class="row g-4">
    <!-- Antrean Verifikasi Proposal -->
    <div class="col-lg-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0">Antrean Verifikasi Proposal</h5>
                <a href="<?= base_url('proposal') ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Lihat Semua</a>
            </div>
            <div class="card-premium-body p-0">
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>UKM</th>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($proposal_terbaru)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada proposal yang butuh persetujuan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($proposal_terbaru as $prop): ?>
                                    <tr>
                                        <td><strong><?= esc($prop['nama_ukm']) ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('proposal/show/' . $prop['id']) ?>" class="fw-bold text-decoration-none">
                                                <?= esc($prop['nama_kegiatan']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge-premium badge-<?= strtolower($prop['status']) ?>">
                                                <?= str_replace('_', ' ', esc($prop['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrean Verifikasi LPJ -->
    <div class="col-lg-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0">Antrean Verifikasi LPJ</h5>
                <a href="<?= base_url('lpj') ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Lihat Semua</a>
            </div>
            <div class="card-premium-body p-0">
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>UKM</th>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lpj_terbaru)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada LPJ yang butuh persetujuan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lpj_terbaru as $lpj_item): ?>
                                    <tr>
                                        <td><strong><?= esc($lpj_item['nama_ukm']) ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('lpj/show/' . $lpj_item['id']) ?>" class="fw-bold text-decoration-none">
                                                <?= esc($lpj_item['nama_kegiatan']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge-premium badge-<?= strtolower($lpj_item['status']) ?>">
                                                <?= str_replace('_', ' ', esc($lpj_item['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
