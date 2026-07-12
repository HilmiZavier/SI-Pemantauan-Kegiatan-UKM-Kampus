<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Ajukan Proposal</h1>
    <p class="page-subtitle">Unggah berkas proposal untuk verifikasi anggaran kegiatan.</p>
</div>

<div class="card-premium" style="max-width: 700px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-file-earmark-plus-fill me-2 text-primary"></i>Formulir Pengajuan Proposal</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('proposal/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="kegiatan_id" class="form-premium-label">Pilih Kegiatan UKM</label>
                <select name="kegiatan_id" id="kegiatan_id" class="form-select form-premium-control w-100" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatans as $keg): ?>
                        <option value="<?= $keg['id'] ?>" <?= old('kegiatan_id') == $keg['id'] ? 'selected' : '' ?>><?= esc($keg['judul']) ?> (Pelaksanaan: <?= date('d M Y', strtotime($keg['tanggal_mulai'])) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1">Hanya kegiatan dengan status "Menunggu Pengajuan Proposal" yang terdaftar di sini.</small>
                <?php if (session('errors.kegiatan_id')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.kegiatan_id') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="anggaran" class="form-premium-label">Estimasi Total Anggaran (Rupiah)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                    <input type="number" name="anggaran" id="anggaran" class="form-control form-premium-control <?= session('errors.anggaran') ? 'is-invalid' : '' ?>" style="border-radius: 0 12px 12px 0;" value="<?= old('anggaran') ?>" required placeholder="Contoh: 5000000">
                </div>
                <?php if (session('errors.anggaran')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.anggaran') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="file_proposal" class="form-premium-label">Berkas Dokumen Proposal (PDF / DOC / DOCX)</label>
                <input type="file" name="file_proposal" id="file_proposal" class="form-control form-premium-control w-100 <?= session('errors.file_proposal') ? 'is-invalid' : '' ?>" required accept=".pdf,.doc,.docx">
                <small class="text-muted d-block mt-1">Ukuran maksimal file: 5 MB.</small>
                <?php if (session('errors.file_proposal')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.file_proposal') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('proposal') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-send-fill"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
