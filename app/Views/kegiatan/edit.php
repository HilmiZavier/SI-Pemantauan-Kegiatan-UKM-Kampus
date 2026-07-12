<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Edit Kegiatan</h1>
    <p class="page-subtitle">Perbarui rencana atau status kegiatan UKM Anda.</p>
</div>

<div class="card-premium" style="max-width: 750px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Edit Kegiatan</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('kegiatan/update/' . $kegiatan['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="judul" class="form-premium-label">Judul Kegiatan</label>
                <input type="text" name="judul" id="judul" class="form-control form-premium-control w-100 <?= session('errors.judul') ? 'is-invalid' : '' ?>" value="<?= old('judul', $kegiatan['judul']) ?>" required placeholder="Masukkan nama kegiatan">
                <?php if (session('errors.judul')): ?>
                    <div class="invalid-feedback"><?= session('errors.judul') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="deskripsi" class="form-premium-label">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control form-premium-control w-100 <?= session('errors.deskripsi') ? 'is-invalid' : '' ?>" required placeholder="Masukkan deskripsi kegiatan"><?= old('deskripsi', $kegiatan['deskripsi']) ?></textarea>
                <?php if (session('errors.deskripsi')): ?>
                    <div class="invalid-feedback"><?= session('errors.deskripsi') ?></div>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-premium-group">
                        <label for="tanggal_mulai" class="form-premium-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control form-premium-control w-100 <?= session('errors.tanggal_mulai') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_mulai', date('Y-m-d', strtotime($kegiatan['tanggal_mulai']))) ?>" required>
                        <?php if (session('errors.tanggal_mulai')): ?>
                            <div class="invalid-feedback"><?= session('errors.tanggal_mulai') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-premium-group">
                        <label for="tanggal_selesai" class="form-premium-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control form-premium-control w-100 <?= session('errors.tanggal_selesai') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_selesai', date('Y-m-d', strtotime($kegiatan['tanggal_selesai']))) ?>" required>
                        <?php if (session('errors.tanggal_selesai')): ?>
                            <div class="invalid-feedback"><?= session('errors.tanggal_selesai') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-premium-group">
                <label for="tempat" class="form-premium-label">Tempat Pelaksanaan</label>
                <input type="text" name="tempat" id="tempat" class="form-control form-premium-control w-100 <?= session('errors.tempat') ? 'is-invalid' : '' ?>" value="<?= old('tempat', $kegiatan['tempat']) ?>" required placeholder="Masukkan tempat pelaksanaan">
                <?php if (session('errors.tempat')): ?>
                    <div class="invalid-feedback"><?= session('errors.tempat') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="status" class="form-premium-label">Status Kegiatan</label>
                <select name="status" id="status" class="form-select form-premium-control w-100" required>
                    <?php
                    $statuses = [
                        'MENUNGGU_PROPOSAL' => 'Menunggu Pengajuan Proposal',
                        'PROPOSAL_DISETUJUI' => 'Proposal Disetujui',
                        'SEDANG_BERJALAN' => 'Sedang Berjalan',
                        'MENUNGGU_LPJ' => 'Menunggu LPJ Laporan',
                        'SELESAI' => 'Selesai',
                        'DIBATALKAN' => 'Dibatalkan'
                    ];
                    ?>
                    <?php foreach ($statuses as $val => $label): ?>
                        <option value="<?= $val ?>" <?= old('status', $kegiatan['status']) === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('errors.status')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.status') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('kegiatan') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
