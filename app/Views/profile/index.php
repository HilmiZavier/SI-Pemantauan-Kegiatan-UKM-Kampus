<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Profil Saya</h1>
    <p class="page-subtitle">Kelola informasi akun Anda dan perbarui foto profil.</p>
</div>

<div class="row g-4">
    <!-- Rincian Informasi User -->
    <div class="col-md-7">
        <div class="card-premium h-100">
            <div class="card-premium-header">
                <h5 class="fw-bold m-0"><i class="bi bi-person-fill me-2 text-primary"></i>Informasi Akun</h5>
            </div>
            <div class="card-premium-body">
                <div class="form-premium-group mb-3">
                    <label class="form-premium-label">Nama Lengkap</label>
                    <input type="text" class="form-control form-premium-control w-100 bg-light" value="<?= esc($user['nama']) ?>" readonly>
                </div>

                <div class="form-premium-group mb-3">
                    <label class="form-premium-label">Alamat Email</label>
                    <input type="text" class="form-control form-premium-control w-100 bg-light" value="<?= esc($user['email']) ?>" readonly>
                </div>

                <div class="form-premium-group mb-3">
                    <label class="form-premium-label">Peran Pengguna (Role)</label>
                    <?php
                    $roleNames = [1 => 'Admin UKM', 2 => 'Admin Kemahasiswaan', 3 => 'Wakil Rektor III'];
                    $roleName = isset($roleNames[$user['role_id']]) ? $roleNames[$user['role_id']] : 'Guest';
                    ?>
                    <input type="text" class="form-control form-premium-control w-100 bg-light" value="<?= $roleName ?>" readonly>
                </div>

                <?php if ($user['role_id'] == 1 && $user['ukm_id']): ?>
                    <?php
                    $db = \Config\Database::connect();
                    $ukm = $db->table('ukm')->where('id', $user['ukm_id'])->get()->getRowArray();
                    ?>
                    <div class="form-premium-group mb-3">
                        <label class="form-premium-label">Afiliasi UKM</label>
                        <input type="text" class="form-control form-premium-control w-100 bg-light" value="<?= esc($ukm['nama_ukm']) ?>" readonly>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Foto Profil -->
    <div class="col-md-5">
        <div class="card-premium h-100 text-center">
            <div class="card-premium-header text-start">
                <h5 class="fw-bold m-0"><i class="bi bi-image me-2 text-primary"></i>Foto Profil</h5>
            </div>
            <div class="card-premium-body d-flex flex-column align-items-center justify-content-center py-5">
                <!-- Foto Profile Container -->
                <div class="mb-4" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 4px solid var(--primary-light); box-shadow: 0 4px 10px rgba(0,0,0,0.05); background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: 800;">
                    <?php if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/profile/' . $user['foto'])): ?>
                        <img src="<?= base_url('uploads/profile/' . $user['foto'] . '?v=' . time()) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <?= strtoupper(substr(esc($user['nama']), 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <!-- Form Upload Foto -->
                <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data" class="w-100 px-3">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3 text-start">
                        <label for="photo" class="form-premium-label text-center mb-2">Unggah Foto Baru (JPG, JPEG, PNG, Maks 2MB)</label>
                        <input type="file" name="photo" id="photo" class="form-control form-premium-control w-100" accept=".jpg,.jpeg,.png" required>
                        <?php if (session('errors.photo')): ?>
                            <div class="invalid-feedback d-block text-center mt-1"><?= session('errors.photo') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-3">
                        <button type="submit" class="btn btn-premium btn-premium-primary w-100 py-2 justify-content-center">
                            <i class="bi bi-upload"></i> Unggah Foto
                        </button>
                        
                        <?php if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/profile/' . $user['foto'])): ?>
                            <a href="<?= base_url('profile/delete') ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')" class="btn btn-premium btn-premium-secondary text-danger border-danger w-100 py-2 justify-content-center">
                                <i class="bi bi-trash"></i> Hapus Foto
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
