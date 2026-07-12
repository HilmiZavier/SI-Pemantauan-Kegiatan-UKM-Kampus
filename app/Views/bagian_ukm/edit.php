<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Edit Bagian UKM</h1>
    <p class="page-subtitle">Ubah nama divisi internal UKM Anda.</p>
</div>

<div class="card-premium" style="max-width: 600px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Edit Divisi</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('bagian-ukm/update/' . $divisi['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="nama_divisi" class="form-premium-label">Nama Divisi / Bagian</label>
                <input type="text" name="nama_divisi" id="nama_divisi" class="form-control form-premium-control w-100 <?= session('errors.nama_divisi') ? 'is-invalid' : '' ?>" value="<?= old('nama_divisi', $divisi['nama_divisi']) ?>" required placeholder="Masukkan nama divisi">
                <?php if (session('errors.nama_divisi')): ?>
                    <div class="invalid-feedback"><?= session('errors.nama_divisi') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('bagian-ukm') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-save"></i> Perbarui Divisi
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
