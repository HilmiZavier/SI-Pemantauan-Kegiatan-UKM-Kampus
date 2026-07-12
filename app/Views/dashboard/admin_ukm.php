<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Dashboard Admin UKM</h1>
    <p class="page-subtitle">Selamat datang kembali! Berikut statistik perkembangan unit kegiatan mahasiswa Anda.</p>
</div>

<!-- Info Banner Profil UKM -->
<div class="glass-panel mb-4 py-4 px-4 d-flex align-items-center justify-content-between" style="border-left: 6px solid var(--primary);">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($profil_ukm['nama_ukm']) ?></h4>
        <p class="text-muted mb-0"><i class="bi bi-person-fill me-1"></i> Ketua UKM: <strong><?= esc($profil_ukm['ketua']) ?></strong></p>
    </div>
    <a href="<?= base_url('ukm/edit/' . $profil_ukm['id']) ?>" class="btn btn-premium btn-premium-primary">
        <i class="bi bi-pencil-square"></i> Edit Profil UKM
    </a>
</div>

<!-- Statistik Ringkas (Stat Cards) -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $jumlah_anggota ?></div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
            <div class="stat-icon-wrapper">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $jumlah_kegiatan ?></div>
                <div class="stat-label">Total Kegiatan</div>
            </div>
            <div class="stat-icon-wrapper">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $jumlah_proposal ?></div>
                <div class="stat-label">Proposal Diajukan</div>
            </div>
            <div class="stat-icon-wrapper" style="--primary: #06b6d4; --primary-light: rgba(6, 182, 212, 0.1); --primary-gradient: linear-gradient(135deg, #06b6d4, #22d3ee);">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div>
                <div class="stat-value"><?= $jumlah_lpj ?></div>
                <div class="stat-label">LPJ Diserahkan</div>
            </div>
            <div class="stat-icon-wrapper" style="--primary: #10b981; --primary-light: rgba(16, 185, 129, 0.1); --primary-gradient: linear-gradient(135deg, #10b981, #34d399);">
                <i class="bi bi-file-earmark-check-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Row Kedua: Status Proposal & LPJ -->
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-file-text me-2 text-primary"></i>Status Proposal Kegiatan</h5>
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

    <div class="col-md-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-file-check me-2 text-success"></i>Status LPJ Kegiatan</h5>
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

<!-- Row Ketiga: Data Terbaru -->
<div class="row g-4">
    <!-- Kegiatan Terbaru -->
    <div class="col-lg-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0">Kegiatan Terbaru</h5>
                <a href="<?= base_url('kegiatan') ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Lihat Semua</a>
            </div>
            <div class="card-premium-body p-0">
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Judul Kegiatan</th>
                                <th>Tempat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kegiatan_terbaru)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data kegiatan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($kegiatan_terbaru as $keg): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('kegiatan/show/' . $keg['id']) ?>" class="fw-bold text-decoration-none">
                                                <?= esc($keg['judul']) ?>
                                            </a>
                                        </td>
                                        <td><?= esc($keg['tempat']) ?></td>
                                        <td>
                                            <span class="badge-premium badge-<?= strtolower($keg['status']) ?>">
                                                <?= str_replace('_', ' ', esc($keg['status'])) ?>
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

    <!-- Proposal & LPJ Terbaru -->
    <div class="col-lg-6">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0">Proposal & LPJ Terbaru</h5>
            </div>
            <div class="card-premium-body p-0">
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Judul / Tipe</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($proposal_terbaru) && empty($lpj_terbaru)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada pengajuan terbaru.</td>
                                </tr>
                            <?php else: ?>
                                <!-- Loop Proposal -->
                                <?php foreach ($proposal_terbaru as $prop): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('proposal/show/' . $prop['id']) ?>" class="fw-bold text-decoration-none">
                                                <?= esc($prop['nama_kegiatan']) ?>
                                            </a>
                                            <span class="badge bg-secondary ms-1" style="font-size: 0.65rem;">PROPOSAL</span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($prop['created_at'])) ?></td>
                                        <td>
                                            <span class="badge-premium badge-<?= strtolower($prop['status']) ?>">
                                                <?= str_replace('_', ' ', esc($prop['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <!-- Loop LPJ -->
                                <?php foreach ($lpj_terbaru as $lpj_item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('lpj/show/' . $lpj_item['id']) ?>" class="fw-bold text-decoration-none">
                                                <?= esc($lpj_item['nama_kegiatan']) ?>
                                            </a>
                                            <span class="badge bg-success ms-1" style="font-size: 0.65rem;">LPJ</span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($lpj_item['created_at'])) ?></td>
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
