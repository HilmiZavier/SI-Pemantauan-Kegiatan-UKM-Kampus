<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$roleId = session()->get('role_id');
$db = \Config\Database::connect();

// Fetch UKM name and creator
$kegiatan = $db->table('kegiatan k')
               ->select('k.*, u.nama_ukm')
               ->join('ukm u', 'u.id = k.ukm_id')
               ->where('k.id', $lpj['kegiatan_id'])
               ->get()->getRowArray();
?>
<div class="page-header">
    <a href="<?= base_url('lpj') ?>" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    <h1 class="page-title">Detail LPJ Kegiatan</h1>
    <p class="page-subtitle">Rincian laporan pertanggungjawaban realisasi kegiatan.</p>
</div>

<div class="row g-4">
    <!-- Kolom Utama: Rincian LPJ -->
    <div class="col-lg-8">
        <div class="card-premium mb-4">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-file-earmark-check-fill me-2 text-primary"></i>Informasi LPJ Laporan</h5>
                <span class="badge-premium badge-<?= strtolower($lpj['status']) ?>">
                    <?= str_replace('_', ' ', esc($lpj['status'])) ?>
                </span>
            </div>
            <div class="card-premium-body">
                <h4 class="fw-bold mb-3"><?= esc($lpj['nama_kegiatan']) ?></h4>
                <p class="text-muted"><i class="bi bi-shield me-2"></i>UKM Pelaksana: <strong><?= esc($kegiatan['nama_ukm']) ?></strong></p>
                
                <hr>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light">
                            <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.725rem;">Realisasi Anggaran</small>
                            <h3 class="fw-bold text-primary mt-1 mb-0">Rp <?= number_format($lpj['realisasi'], 0, ',', '.') ?></h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light d-flex flex-column justify-content-between h-100">
                            <div>
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.725rem;">Dokumen LPJ</small>
                                <span class="fw-bold text-muted small d-block mt-1">Laporan.pdf</span>
                            </div>
                            <a href="<?= base_url('uploads/lpj/' . $lpj['file_lpj']) ?>" target="_blank" class="btn btn-premium btn-premium-primary py-2 px-3 w-100 justify-content-center mt-2">
                                <i class="bi bi-download"></i> Unduh LPJ
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light d-flex flex-column justify-content-between h-100">
                            <div>
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.725rem;">Bukti Transaksi</small>
                                <span class="fw-bold text-muted small d-block mt-1">Lampiran Bukti</span>
                            </div>
                            <a href="<?= base_url('uploads/bukti/' . $lpj['bukti_file']) ?>" target="_blank" class="btn btn-premium btn-premium-secondary py-2 px-3 w-100 justify-content-center mt-2">
                                <i class="bi bi-eye"></i> Lihat Bukti
                            </a>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-2">Rincian Kegiatan (Sistem)</h5>
                <table class="table table-bordered table-sm text-muted">
                    <tr>
                        <td width="30%" class="fw-bold bg-light ps-2 py-2">Tempat Pelaksanaan</td>
                        <td class="ps-2 py-2"><?= esc($kegiatan['tempat']) ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light ps-2 py-2">Waktu Pelaksanaan</td>
                        <td class="ps-2 py-2"><?= date('d F Y', strtotime($kegiatan['tanggal_mulai'])) ?> s/d <?= date('d F Y', strtotime($kegiatan['tanggal_selesai'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Catatan & Log Verifikasi -->
        <div class="card-premium">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat & Catatan Verifikasi LPJ</h5>
            </div>
            <div class="card-premium-body d-flex flex-column gap-3">
                <!-- Kemahasiswaan Verification -->
                <div class="border rounded-3 p-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold m-0">1. Verifikasi Kemahasiswaan</h6>
                        <span class="badge bg-<?= $lpj['status_kemahasiswaan'] === 'APPROVED' ? 'success' : ($lpj['status_kemahasiswaan'] === 'REVISI' ? 'warning' : 'secondary') ?>">
                            <?= esc($lpj['status_kemahasiswaan']) ?>
                        </span>
                    </div>
                    <?php if ($lpj['verified_at_kemahasiswaan']): ?>
                        <small class="text-muted d-block mb-2">Diverifikasi pada: <?= date('d M Y H:i', strtotime($lpj['verified_at_kemahasiswaan'])) ?></small>
                        <?php if ($lpj['catatan_kemahasiswaan']): ?>
                            <div class="p-2 bg-white rounded border border-warning">
                                <strong>Catatan:</strong> <?= esc($lpj['catatan_kemahasiswaan']) ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Belum diproses.</p>
                    <?php endif; ?>
                </div>

                <!-- WR3 Verification -->
                <div class="border rounded-3 p-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold m-0">2. Verifikasi Wakil Rektor III</h6>
                        <span class="badge bg-<?= $lpj['status_wakilrektor3'] === 'APPROVED' ? 'success' : ($lpj['status_wakilrektor3'] === 'REVISI' ? 'warning' : 'secondary') ?>">
                            <?= esc($lpj['status_wakilrektor3']) ?>
                        </span>
                    </div>
                    <?php if ($lpj['verified_at_wakilrektor3']): ?>
                        <small class="text-muted d-block mb-2">Diverifikasi pada: <?= date('d M Y H:i', strtotime($lpj['verified_at_wakilrektor3'])) ?></small>
                        <?php if ($lpj['catatan_wakilrektor3']): ?>
                            <div class="p-2 bg-white rounded border border-warning">
                                <strong>Catatan:</strong> <?= esc($lpj['catatan_wakilrektor3']) ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Belum diproses.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Samping: Formulir Tindakan Reviewer -->
    <div class="col-lg-4">
        <!-- Form untuk Admin Kemahasiswaan (Role 2) -->
        <?php if ($roleId == 2 && $lpj['status'] === 'PENGAJUAN'): ?>
            <div class="card-premium border-primary mb-4" style="border-width: 2px;">
                <div class="card-premium-header bg-primary text-white">
                    <h5 class="fw-bold m-0 text-white"><i class="bi bi-shield-check me-2"></i>Tindakan Kemahasiswaan</h5>
                </div>
                <div class="card-premium-body">
                    <p class="text-muted small">Silakan pilih tindakan untuk laporan pertanggungjawaban (LPJ) ini.</p>
                    
                    <!-- Button Approve -->
                    <form action="<?= base_url('lpj/approve/' . $lpj['id']) ?>" method="post" class="mb-3">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-premium btn-premium-primary w-100 py-3 text-center justify-content-center">
                            <i class="bi bi-check-circle-fill"></i> Setujui LPJ Laporan
                        </button>
                    </form>

                    <hr>

                    <!-- Form Revisi & Tolak -->
                    <form action="<?= base_url('lpj/revisi/' . $lpj['id']) ?>" method="post" class="mt-3">
                        <?= csrf_field() ?>
                        <div class="form-premium-group">
                            <label for="catatan_kemahasiswaan" class="form-premium-label">Catatan Revisi / Penolakan</label>
                            <textarea name="catatan_kemahasiswaan" id="catatan_kemahasiswaan" rows="4" class="form-control form-premium-control w-100" required placeholder="Tulis catatan di sini..."></textarea>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-premium btn-premium-secondary w-100 py-2 justify-content-center">
                                    <i class="bi bi-arrow-counterclockwise"></i> Minta Revisi
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" formAction="<?= base_url('lpj/tolak/' . $lpj['id']) ?>" class="btn btn-premium btn-premium-danger w-100 py-2 justify-content-center">
                                    <i class="bi bi-x-circle-fill"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form untuk Wakil Rektor III (Role 3) -->
        <?php if ($roleId == 3 && $lpj['status'] === 'VERIFIKASI_WAKIL_REKTOR_3' && $lpj['status_kemahasiswaan'] === 'APPROVED'): ?>
            <div class="card-premium border-warning mb-4" style="border-width: 2px;">
                <div class="card-premium-header bg-warning text-dark">
                    <h5 class="fw-bold m-0 text-dark"><i class="bi bi-shield-check me-2"></i>Tindakan Wakil Rektor III</h5>
                </div>
                <div class="card-premium-body">
                    <p class="text-muted small">Silakan lakukan persetujuan akhir (final approval) untuk LPJ ini.</p>
                    
                    <!-- Button Approve -->
                    <form action="<?= base_url('lpj/approve/' . $lpj['id']) ?>" method="post" class="mb-3">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-premium btn-premium-primary w-100 py-3 text-center justify-content-center" style="--primary-gradient: linear-gradient(135deg, #d97706, #fbbf24); box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4);">
                            <i class="bi bi-check-circle-fill"></i> Setujui LPJ Laporan (Final)
                        </button>
                    </form>

                    <hr>

                    <!-- Form Revisi & Tolak -->
                    <form action="<?= base_url('lpj/revisi/' . $lpj['id']) ?>" method="post" class="mt-3">
                        <?= csrf_field() ?>
                        <div class="form-premium-group">
                            <label for="catatan_wakilrektor3" class="form-premium-label">Catatan Revisi / Penolakan</label>
                            <textarea name="catatan_wakilrektor3" id="catatan_wakilrektor3" rows="4" class="form-control form-premium-control w-100" required placeholder="Tulis catatan di sini..."></textarea>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-premium btn-premium-secondary w-100 py-2 justify-content-center">
                                    <i class="bi bi-arrow-counterclockwise"></i> Minta Revisi
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" formAction="<?= base_url('lpj/tolak/' . $lpj['id']) ?>" class="btn btn-premium btn-premium-danger w-100 py-2 justify-content-center">
                                    <i class="bi bi-x-circle-fill"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
