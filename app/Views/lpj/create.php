<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Serahkan LPJ</h1>
    <p class="page-subtitle">Unggah berkas Laporan Pertanggungjawaban beserta bukti transaksi kegiatan.</p>
</div>

<div class="card-premium" style="max-width: 750px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-file-earmark-check-fill me-2 text-primary"></i>Formulir Penyerahan LPJ</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('lpj/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label for="kegiatan_id" class="form-premium-label">Pilih Kegiatan UKM</label>
                <select name="kegiatan_id" id="kegiatan_id" class="form-select form-premium-control w-100" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatan as $keg): ?>
                        <option value="<?= $keg['id'] ?>" <?= old('kegiatan_id') == $keg['id'] ? 'selected' : '' ?>><?= esc($keg['judul']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1">Hanya kegiatan dengan status "Sedang Berjalan" atau "Menunggu LPJ" yang terdaftar di sini.</small>
                <?php if (session('errors.kegiatan_id')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.kegiatan_id') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="realisasi" class="form-premium-label">Total Realisasi Anggaran (Rupiah)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                    <input type="number" name="realisasi" id="realisasi" class="form-control form-premium-control <?= session('errors.realisasi') ? 'is-invalid' : '' ?>" style="border-radius: 0 12px 12px 0;" value="<?= old('realisasi') ?>" required placeholder="Contoh: 4850000">
                </div>
                <?php if (session('errors.realisasi')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.realisasi') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="file_lpj" class="form-premium-label">Berkas Dokumen Laporan LPJ (PDF Only)</label>
                <input type="file" name="file_lpj" id="file_lpj" class="form-control form-premium-control w-100 <?= session('errors.file_lpj') ? 'is-invalid' : '' ?>" required accept=".pdf">
                <small class="text-muted d-block mt-1">Ukuran maksimal file: 5 MB.</small>
                <?php if (session('errors.file_lpj')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.file_lpj') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="bukti_file" class="form-premium-label">Berkas Lampiran Bukti Transaksi / Kuitansi (PDF / JPG / JPEG / PNG)</label>
                <input type="file" name="bukti_file" id="bukti_file" class="form-control form-premium-control w-100 <?= session('errors.bukti_file') ? 'is-invalid' : '' ?>" required accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted d-block mt-1">Ukuran maksimal file: 5 MB.</small>
                <?php if (session('errors.bukti_file')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.bukti_file') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('lpj') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-send-fill"></i> Serahkan LPJ Laporan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
