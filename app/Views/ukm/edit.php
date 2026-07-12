<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Edit Profil UKM</h1>
    <p class="page-subtitle">Perbarui informasi dasar Unit Kegiatan Mahasiswa.</p>
</div>

<div class="card-premium" style="max-width: 600px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Edit UKM</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('ukm/update/' . $ukm['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="nama_ukm" class="form-premium-label">Nama UKM</label>
                <input type="text" name="nama_ukm" id="nama_ukm" class="form-control form-premium-control w-100 <?= session('errors.nama_ukm') ? 'is-invalid' : '' ?>" value="<?= old('nama_ukm', $ukm['nama_ukm']) ?>" required <?= session()->get('role_id') == 1 ? 'readonly' : '' ?> placeholder="Masukkan nama UKM">
                <?php if (session('errors.nama_ukm')): ?>
                    <div class="invalid-feedback"><?= session('errors.nama_ukm') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="ketua" class="form-premium-label">Nama Ketua UKM</label>
                <input type="text" name="ketua" id="ketua" class="form-control form-premium-control w-100 <?= session('errors.ketua') ? 'is-invalid' : '' ?>" value="<?= old('ketua', $ukm['ketua']) ?>" required placeholder="Masukkan nama ketua UKM">
                <?php if (session('errors.ketua')): ?>
                    <div class="invalid-feedback"><?= session('errors.ketua') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('ukm') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-save"></i> Perbarui UKM
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
