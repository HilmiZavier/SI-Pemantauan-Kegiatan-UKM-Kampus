<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$ukmModel = new \App\Models\UkmModel();
$ukms = $ukmModel->findAll();
$roleModel = new \App\Models\RoleModel();
$roles = $roleModel->findAll();
?>
<div class="page-header">
    <h1 class="page-title">Edit User</h1>
    <p class="page-subtitle">Perbarui informasi akun pengguna.</p>
</div>

<div class="card-premium" style="max-width: 650px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Edit User</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('user/update/' . $user['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="nama" class="form-premium-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control form-premium-control w-100 <?= session('errors.nama') ? 'is-invalid' : '' ?>" value="<?= old('nama', $user['nama']) ?>" required placeholder="Masukkan nama lengkap">
                <?php if (session('errors.nama')): ?>
                    <div class="invalid-feedback"><?= session('errors.nama') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="email" class="form-premium-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control form-premium-control w-100 <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email', $user['email']) ?>" required placeholder="user@unitomo.ac.id">
                <?php if (session('errors.email')): ?>
                    <div class="invalid-feedback"><?= session('errors.email') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="password" class="form-premium-label">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password" class="form-control form-premium-control w-100 <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder="Minimal 6 karakter">
                <?php if (session('errors.password')): ?>
                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="role_id" class="form-premium-label">Peran Pengguna (Role)</label>
                <select name="role_id" id="role_id" class="form-select form-premium-control w-100" required>
                    <option value="">-- Pilih Peran --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= old('role_id', $user['role_id']) == $role['id'] ? 'selected' : '' ?>><?= esc($role['nama_role']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('errors.role_id')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.role_id') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group" id="ukm-select-group" style="display: none;">
                <label for="ukm_id" class="form-premium-label">Pilih UKM</label>
                <select name="ukm_id" id="ukm_id" class="form-select form-premium-control w-100">
                    <option value="">-- Pilih UKM --</option>
                    <?php foreach ($ukms as $ukm): ?>
                        <option value="<?= $ukm['id'] ?>" <?= old('ukm_id', $user['ukm_id']) == $ukm['id'] ? 'selected' : '' ?>><?= esc($ukm['nama_ukm']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('errors.ukm_id')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.ukm_id') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('user') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-save"></i> Perbarui User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('role_id');
    const ukmGroup = document.getElementById('ukm-select-group');
    const ukmSelect = document.getElementById('ukm_id');

    function toggleUkmSelect() {
        if (roleSelect.value == '1') {
            ukmGroup.style.display = 'block';
            ukmSelect.setAttribute('required', 'required');
        } else {
            ukmGroup.style.display = 'none';
            ukmSelect.removeAttribute('required');
            ukmSelect.value = '';
        }
    }

    roleSelect.addEventListener('change', toggleUkmSelect);
    toggleUkmSelect();
</script>
<?= $this->endSection() ?>
