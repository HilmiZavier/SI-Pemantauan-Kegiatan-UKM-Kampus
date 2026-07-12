<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$db = \Config\Database::connect();
$proposal = $db->table('proposal_kegiatan')->where('kegiatan_id', $kegiatan['id'])->get()->getRowArray();
$lpj = $db->table('lpj_kegiatan')->where('kegiatan_id', $kegiatan['id'])->get()->getRowArray();
$roleId = session()->get('role_id');
?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <a href="<?= base_url('kegiatan') ?>" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        <h1 class="page-title">Detail Kegiatan</h1>
        <p class="page-subtitle">Rincian lengkap data kegiatan UKM.</p>
    </div>
    
    <?php if ($roleId == 1): ?>
        <div class="d-inline-flex gap-2">
            <a href="<?= base_url('kegiatan/edit/' . $kegiatan['id']) ?>" class="btn btn-premium btn-premium-secondary">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Informasi Detail Kegiatan -->
    <div class="col-lg-8">
        <div class="card-premium mb-4">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Kegiatan</h5>
                <span class="badge-premium badge-<?= strtolower($kegiatan['status']) ?>">
                    <?= str_replace('_', ' ', esc($kegiatan['status'])) ?>
                </span>
            </div>
            <div class="card-premium-body">
                <h3 class="fw-bold mb-3"><?= esc($kegiatan['judul']) ?></h3>
                <p class="text-muted" style="font-size: 0.9rem;">
                    <i class="bi bi-shield me-2"></i>UKM: <strong><?= esc($kegiatan['nama_ukm']) ?></strong>
                </p>
                
                <hr>
                
                <h5 class="fw-bold mb-2">Deskripsi Kegiatan</h5>
                <div class="text-main mb-4" style="line-height: 1.6; white-space: pre-line;">
                    <?= esc($kegiatan['deskripsi']) ?>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.725rem;">Waktu Pelaksanaan</small>
                            <div class="fw-bold mt-1">
                                <i class="bi bi-calendar-event me-2 text-muted"></i><?= date('d F Y', strtotime($kegiatan['tanggal_mulai'])) ?>
                            </div>
                            <small class="text-muted ps-4">s/d <?= date('d F Y', strtotime($kegiatan['tanggal_selesai'])) ?></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.725rem;">Tempat Pelaksanaan</small>
                            <div class="fw-bold mt-1">
                                <i class="bi bi-geo-alt-fill me-2 text-muted"></i><?= esc($kegiatan['tempat']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Alur Administrasi (Proposal & LPJ) -->
    <div class="col-lg-4">
        <div class="card-premium">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-node-plus-fill me-2 text-primary"></i>Alur Pengajuan</h5>
            </div>
            <div class="card-premium-body d-flex flex-column gap-4">
                <!-- Proposal Step -->
                <div class="border rounded-3 p-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold m-0"><i class="bi bi-file-earmark-text-fill text-info me-2"></i>Proposal</h6>
                        <?php if ($proposal): ?>
                            <span class="badge-premium badge-<?= strtolower($proposal['status']) ?>" style="font-size: 0.65rem;">
                                <?= str_replace('_', ' ', esc($proposal['status'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary" style="font-size: 0.65rem;">Belum Diajukan</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($proposal): ?>
                        <p class="text-muted small mb-2">Anggaran: <strong>Rp <?= number_format($proposal['anggaran'], 0, ',', '.') ?></strong></p>
                        <a href="<?= base_url('proposal/show/' . $proposal['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary w-100 py-2">
                            <i class="bi bi-eye"></i> Detail Proposal
                        </a>
                    <?php elseif ($roleId == 1 && $kegiatan['status'] === 'MENUNGGU_PROPOSAL'): ?>
                        <p class="text-muted small mb-3">Kegiatan disetujui, silakan ajukan proposal untuk pencairan dana kegiatan.</p>
                        <a href="<?= base_url('proposal/create') ?>" class="btn btn-sm btn-premium btn-premium-primary w-100 py-2">
                            <i class="bi bi-plus-circle"></i> Ajukan Proposal
                        </a>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Proposal belum diajukan oleh UKM.</p>
                    <?php endif; ?>
                </div>

                <!-- LPJ Step -->
                <div class="border rounded-3 p-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold m-0"><i class="bi bi-file-earmark-check-fill text-success me-2"></i>LPJ Laporan</h6>
                        <?php if ($lpj): ?>
                            <span class="badge-premium badge-<?= strtolower($lpj['status']) ?>" style="font-size: 0.65rem;">
                                <?= str_replace('_', ' ', esc($lpj['status'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary" style="font-size: 0.65rem;">Belum Diajukan</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($lpj): ?>
                        <p class="text-muted small mb-2">Realisasi: <strong>Rp <?= number_format($lpj['realisasi'], 0, ',', '.') ?></strong></p>
                        <a href="<?= base_url('lpj/show/' . $lpj['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary w-100 py-2">
                            <i class="bi bi-eye"></i> Detail LPJ
                        </a>
                    <?php elseif ($roleId == 1 && in_array($kegiatan['status'], ['MENUNGGU_LPJ', 'SEDANG_BERJALAN'])): ?>
                        <p class="text-muted small mb-3">Kegiatan telah selesai dilaksanakan, harap segera mengunggah berkas LPJ.</p>
                        <a href="<?= base_url('lpj/create') ?>" class="btn btn-sm btn-premium btn-premium-primary w-100 py-2">
                            <i class="bi bi-plus-circle"></i> Serahkan LPJ
                        </a>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Laporan LPJ belum diajukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
