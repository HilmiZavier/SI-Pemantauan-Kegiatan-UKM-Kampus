<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Anggota UKM</h1>
        <p class="page-subtitle">Daftar mahasiswa yang terdaftar aktif dalam Unit Kegiatan Mahasiswa Anda.</p>
    </div>
    <a href="<?= base_url('anggota-ukm/create') ?>" class="btn btn-premium btn-premium-primary">
        <i class="bi bi-person-plus-fill"></i> Tambah Anggota Baru
    </a>
</div>

<div class="card-premium">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>NIM / Nama</th>
                        <th>Prodi / Angkatan</th>
                        <th>Divisi (Bagian)</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $divisiModel = new \App\Models\DivisiUkmModel();
                    $divisiMap = [];
                    foreach ($divisiModel->findAll() as $div) {
                        $divisiMap[$div['id']] = $div['nama_divisi'];
                    }
                    ?>
                    <?php if (empty($anggota)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada anggota yang terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($anggota as $item): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= esc($item['nama']) ?></div>
                                    <span class="text-muted" style="font-size: 0.85rem;"><?= esc($item['nim']) ?></span>
                                </td>
                                <td>
                                    <div><?= esc($item['prodi']) ?></div>
                                    <small class="text-muted">Angkatan <?= esc($item['angkatan']) ?></small>
                                </td>
                                <td>
                                    <strong><?= isset($divisiMap[$item['divisi_id']]) ? esc($divisiMap[$item['divisi_id']]) : '-' ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= esc($item['jabatan']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;"><i class="bi bi-telephone-fill text-muted me-1"></i><?= esc($item['no_hp']) ?></div>
                                    <div style="font-size: 0.875rem;"><i class="bi bi-envelope-fill text-muted me-1"></i><?= esc($item['email']) ?></div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('anggota-ukm/edit/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= base_url('anggota-ukm/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data anggota ini?')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
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
