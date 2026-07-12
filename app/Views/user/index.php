<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title">Kelola User</h1>
        <p class="page-subtitle">Daftar akun pengguna sistem informasi pemantauan kegiatan UKM.</p>
    </div>
    <a href="<?= base_url('user/create') ?>" class="btn btn-premium btn-premium-primary">
        <i class="bi bi-person-plus-fill"></i> Tambah User Baru
    </a>
</div>

<div class="card-premium">
    <div class="card-premium-body p-0">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Peran (Role)</th>
                        <th>Afiliasi UKM</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $roleMap = [1 => 'Admin UKM', 2 => 'Admin Kemahasiswaan', 3 => 'Wakil Rektor III'];
                    $ukmModel = new \App\Models\UkmModel();
                    $ukms = [];
                    foreach ($ukmModel->findAll() as $u) {
                        $ukms[$u['id']] = $u['nama_ukm'];
                    }
                    ?>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data user.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= esc($user['nama']) ?></div>
                                    <small class="text-muted">ID: #<?= $user['id'] ?></small>
                                </td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= isset($roleMap[$user['role_id']]) ? $roleMap[$user['role_id']] : 'Unknown' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['role_id'] == 1 && isset($ukms[$user['ukm_id']])): ?>
                                        <strong><?= esc($ukms[$user['ukm_id']]) ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('user/edit/' . $user['id']) ?>" class="btn btn-sm btn-premium btn-premium-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= base_url('user/delete/' . $user['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="btn btn-sm btn-premium btn-premium-danger py-1 px-2" title="Hapus">
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
