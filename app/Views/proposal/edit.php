<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Edit Pengajuan Proposal</h1>
    <p class="page-subtitle">Perbarui data proposal anggaran yang dikembalikan untuk direvisi.</p>
</div>

<div class="card-premium" style="max-width: 700px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Perbaikan Proposal</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('proposal/update/' . $proposal['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-premium-group">
                <label class="form-premium-label">Judul Kegiatan</label>
                <?php
                $db = \Config\Database::connect();
                $kegiatan = $db->table('kegiatan')->where('id', $proposal['kegiatan_id'])->get()->getRowArray();
                ?>
                <input type="text" class="form-control form-premium-control w-100 bg-light" value="<?= esc($kegiatan['judul']) ?>" readonly>
            </div>

            <div class="form-premium-group">
                <label for="anggaran" class="form-premium-label">Estimasi Total Anggaran Baru (Rupiah)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">Rp</span>
                    <input type="number" name="anggaran" id="anggaran" class="form-control form-premium-control <?= session('errors.anggaran') ? 'is-invalid' : '' ?>" style="border-radius: 0 12px 12px 0;" value="<?= old('anggaran', $proposal['anggaran']) ?>" required placeholder="Contoh: 5000000">
                </div>
                <?php if (session('errors.anggaran')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.anggaran') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-premium-group">
                <label for="file_proposal" class="form-premium-label">Unggah Berkas Baru (Kosongkan jika tidak diganti)</label>
                <input type="file" name="file_proposal" id="file_proposal" class="form-control form-premium-control w-100 <?= session('errors.file_proposal') ? 'is-invalid' : '' ?>" accept=".pdf,.doc,.docx">
                <small class="text-muted d-block mt-1">Berkas saat ini: <a href="<?= base_url('uploads/proposals/' . $proposal['file_proposal']) ?>" target="_blank"><strong><?= esc($proposal['file_proposal']) ?></strong></a></small>
                <?php if (session('errors.file_proposal')): ?>
                    <div class="invalid-feedback d-block"><?= session('errors.file_proposal') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('proposal') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-send-check-fill"></i> Simpan & Kirim Ulang
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
