<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $roleId = session()->get('role_id'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Proposal Kegiatan</h1>
        <p class="page-subtitle">Daftar pengajuan proposal anggaran kegiatan mahasiswa.</p>
    </div>
    <?php if ($roleId == 1): ?>
        <a href="<?= base_url('proposal/create') ?>" class="btn btn-premium btn-premium-primary">
            <i class="bi bi-file-earmark-plus-fill"></i> Ajukan Proposal Baru
        </a>
    <?php endif; ?>
</div>

<div class="card-premium">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Kegiatan / UKM</th>
                        <th>Anggaran Diajukan</th>
                        <th>File Proposal</th>
                        <th>Status Pengajuan</th>
                        <th>Waktu Pengajuan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $db = \Config\Database::connect();
                    ?>
                    <?php if (empty($proposals)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan proposal.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($proposals as $item): ?>
                            <?php
                            $ukm = $db->table('ukm')->where('id', $item['ukm_id'])->get()->getRowArray();
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('proposal/show/' . $item['id']) ?>" class="fw-bold text-decoration-none">
                                        <?= esc($item['judul']) ?>
                                    </a>
                                    <small class="text-muted d-block">UKM: <?= esc($ukm['nama_ukm']) ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">Rp <?= number_format($item['anggaran'], 0, ',', '.') ?></div>
                                </td>
                                <td>
                                    <a href="<?= base_url('uploads/proposals/' . $item['file_proposal']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh PDF
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-premium badge-<?= strtolower($item['status']) ?>">
                                        <?= str_replace('_', ' ', esc($item['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('d M Y H:i', strtotime($item['created_at'])) ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('proposal/show/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <?php if ($roleId == 1 && in_array($item['status'], ['PENGAJUAN', 'REVISI_KEMAHASISWAAN', 'REVISI_WAKIL_REKTOR_3'])): ?>
                                            <a href="<?= base_url('proposal/edit/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <?php if ($item['status'] === 'PENGAJUAN'): ?>
                                                <a href="<?= base_url('proposal/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan proposal ini?')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
