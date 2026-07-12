<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Bagian / Divisi UKM</h1>
        <p class="page-subtitle">Kelola daftar divisi atau bagian internal dalam Unit Kegiatan Mahasiswa Anda.</p>
    </div>
    <a href="<?= base_url('bagian-ukm/create') ?>" class="btn btn-premium btn-premium-primary">
        <i class="bi bi-tag-fill"></i> Tambah Bagian Baru
    </a>
</div>

<div class="card-premium" style="max-width: 700px;">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>ID Bagian</th>
                        <th>Nama Divisi / Bagian</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($divisi)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada bagian/divisi yang ditambahkan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($divisi as $item): ?>
                            <tr>
                                <td>#<?= esc($item['id']) ?></td>
                                <td><strong><?= esc($item['nama_divisi']) ?></strong></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('bagian-ukm/edit/' . $item['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= base_url('bagian-ukm/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus divisi ini? Anggota di divisi ini mungkin perlu disesuaikan kembali.')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
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
