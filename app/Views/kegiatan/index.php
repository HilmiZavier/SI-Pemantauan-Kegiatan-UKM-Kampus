<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $roleId = session()->get('role_id'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Kegiatan UKM</h1>
        <p class="page-subtitle">Daftar agenda kegiatan dan program kerja Unit Kegiatan Mahasiswa.</p>
    </div>
    <?php if ($roleId == 1): ?>
        <a href="<?= base_url('kegiatan/create') ?>" class="btn btn-premium btn-premium-primary">
            <i class="bi bi-calendar-plus-fill"></i> Tambah Kegiatan
        </a>
    <?php endif; ?>
</div>

<div class="card-premium">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>UKM</th>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Tempat</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kegiatan)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada kegiatan yang terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($kegiatan as $item): ?>
                            <tr>
                                <td><strong><?= esc($item['nama_ukm']) ?></strong></td>
                                <td>
                                    <a href="<?= base_url('kegiatan/show/' . $item['id']) ?>" class="fw-bold text-decoration-none">
                                        <?= esc($item['judul']) ?>
                                    </a>
                                </td>
                                <td>
                                    <div><?= date('d M Y', strtotime($item['tanggal_mulai'])) ?></div>
                                    <small class="text-muted">s/d <?= date('d M Y', strtotime($item['tanggal_selesai'])) ?></small>
                                </td>
                                <td><?= esc($item['tempat']) ?></td>
                                <td>
                                    <span class="badge-premium badge-<?= strtolower($item['status']) ?>">
                                        <?= str_replace('_', ' ', esc($item['status'])) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('kegiatan/show/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <?php if ($roleId == 1): ?>
                                            <a href="<?= base_url('kegiatan/edit/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="<?= base_url('kegiatan/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta proposal dan LPJ terkait?')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
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
