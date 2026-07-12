<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Edit Penyerahan LPJ</h1>
    <p class="page-subtitle">Perbarui data laporan pertanggungjawaban kegiatan Anda.</p>
</div>

<div class="card-premium" style="max-width: 750px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Perbaikan LPJ</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('lpj/update/' . $lpj['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label class="form-premium-label">Pilih Kegiatan UKM</label>
                <select name="kegiatan_id" id="kegiatan_id" class="form-select form-premium-control w-100" required>
                    <?php foreach ($kegiatan as $keg): ?>
                        <option value="<?= $keg['id'] ?>" <?= old('kegiatan_id', $lpj['kegiatan_id']) == $keg['id'] ? 'selected' : '' ?>><?= esc($keg['judul']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('errors.kegiatan_id')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.kegiatan_id') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="realisasi" class="form-premium-label">Total Realisasi Anggaran Baru (Rupiah)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                    <input type="number" name="realisasi" id="realisasi" class="form-control form-premium-control <?= session('errors.realisasi') ? 'is-invalid' : '' ?>" style="border-radius: 0 12px 12px 0;" value="<?= old('realisasi', $lpj['realisasi']) ?>" required placeholder="Contoh: 4850000">
                </div>
                <?php if (session('errors.realisasi')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.realisasi') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="file_lpj" class="form-premium-label">Dokumen Laporan LPJ Baru (PDF, Kosongkan jika tidak diganti)</label>
                <input type="file" name="file_lpj" id="file_lpj" class="form-control form-premium-control w-100 <?= session('errors.file_lpj') ? 'is-invalid' : '' ?>" accept=".pdf">
                <small class="text-muted d-block mt-1">Berkas saat ini: <a href="<?= base_url('uploads/lpj/' . $lpj['file_lpj']) ?>" target="_blank"><strong><?= esc($lpj['file_lpj']) ?></strong></a></small>
                <?php if (session('errors.file_lpj')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.file_lpj') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="bukti_file" class="form-premium-label">Bukti Transaksi Baru (PDF / Image, Kosongkan jika tidak diganti)</label>
                <input type="file" name="bukti_file" id="bukti_file" class="form-control form-premium-control w-100 <?= session('errors.bukti_file') ? 'is-invalid' : '' ?>" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted d-block mt-1">Berkas saat ini: <a href="<?= base_url('uploads/bukti/' . $lpj['bukti_file']) ?>" target="_blank"><strong><?= esc($lpj['bukti_file']) ?></strong></a></small>
                <?php if (session('errors.bukti_file')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.bukti_file') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('lpj') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-send-check-fill"></i> Simpan & Kirim Ulang
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
