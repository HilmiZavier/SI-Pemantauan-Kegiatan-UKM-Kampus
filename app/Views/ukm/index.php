<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $roleId = session()->get('role_id'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Unit Kegiatan Mahasiswa (UKM)</h1>
        <p class="page-subtitle">Daftar Unit Kegiatan Mahasiswa yang terdaftar di kampus.</p>
    </div>
    <?php if ($roleId != 1): ?>
        <a href="<?= base_url('ukm/create') ?>" class="btn btn-premium btn-premium-primary">
            <i class="bi bi-plus-circle-fill"></i> Tambah UKM Baru
        </a>
    <?php endif; ?>
</div>

<div class="card-premium">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Nama UKM</th>
                        <th>Nama Ketua</th>
                        <th>Tanggal Terdaftar</th>
                        <?php if ($roleId != 1): ?>
                            <th class="text-end">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ukm)): ?>
                        <tr>
                            <td colspan="<?= $roleId != 1 ? 4 : 3 ?>" class="text-center text-muted py-4">Belum ada data UKM.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ukm as $item): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= esc($item['nama_ukm']) ?></div>
                                    <small class="text-muted">ID: #<?= $item['id'] ?></small>
                                </td>
                                <td><?= esc($item['ketua']) ?></td>
                                <td><?= date('d F Y', strtotime($item['created_at'])) ?></td>
                                <?php if ($roleId != 1): ?>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="<?= base_url('ukm/edit/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="<?= base_url('ukm/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus UKM ini beserta semua datanya?')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
