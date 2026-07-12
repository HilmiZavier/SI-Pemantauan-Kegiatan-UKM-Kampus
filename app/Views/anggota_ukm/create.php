<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Tambah Anggota UKM</h1>
    <p class="page-subtitle">Silakan isi data mahasiswa baru yang bergabung dalam UKM Anda.</p>
</div>

<div class="card-premium" style="max-width: 750px;">
    <div class="card-premium-header">
        <h5 class="fw-bold m-0"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Formulir Data Anggota</h5>
    </div>
    <div class="card-premium-body">
        <form action="<?= base_url('anggota-ukm/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-premium-group">
                        <label for="nama" class="form-premium-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="form-control form-premium-control w-100 <?= session('errors.nama') ? 'is-invalid' : '' ?>" value="<?= old('nama') ?>" required placeholder="Masukkan nama lengkap">
                        <?php if (session('errors.nama')): ?>
                            <div class="invalid-feedback"><?= session('errors.nama') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="nim" class="form-premium-label">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim" id="nim" class="form-control form-premium-control w-100 <?= session('errors.nim') ? 'is-invalid' : '' ?>" value="<?= old('nim') ?>" required placeholder="Masukkan NIM mahasiswa">
                        <?php if (session('errors.nim')): ?>
                            <div class="invalid-feedback"><?= session('errors.nim') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="prodi" class="form-premium-label">Program Studi</label>
                        <input type="text" name="prodi" id="prodi" class="form-control form-premium-control w-100 <?= session('errors.prodi') ? 'is-invalid' : '' ?>" value="<?= old('prodi') ?>" required placeholder="Masukkan program studi">
                        <?php if (session('errors.prodi')): ?>
                            <div class="invalid-feedback"><?= session('errors.prodi') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="angkatan" class="form-premium-label">Angkatan (Tahun)</label>
                        <input type="number" name="angkatan" id="angkatan" class="form-control form-premium-control w-100 <?= session('errors.angkatan') ? 'is-invalid' : '' ?>" value="<?= old('angkatan', date('Y')) ?>" required placeholder="Contoh: 2024">
                        <?php if (session('errors.angkatan')): ?>
                            <div class="invalid-feedback"><?= session('errors.angkatan') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-premium-group">
                        <label for="divisi_id" class="form-premium-label">Divisi / Bagian</label>
                        <select name="divisi_id" id="divisi_id" class="form-select form-premium-control w-100" required>
                            <option value="">-- Pilih Divisi --</option>
                            <?php foreach ($divisi as $div): ?>
                                <option value="<?= $div['id'] ?>" <?= old('divisi_id') == $div['id'] ? 'selected' : '' ?>><?= esc($div['nama_divisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.divisi_id')): ?>
                            <div class="invalid-feedback d-block"><?= session('errors.divisi_id') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="jabatan" class="form-premium-label">Jabatan di UKM</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control form-premium-control w-100 <?= session('errors.jabatan') ? 'is-invalid' : '' ?>" value="<?= old('jabatan', 'Anggota') ?>" required placeholder="misal: Ketua Divisi, Sekretaris, Anggota">
                        <?php if (session('errors.jabatan')): ?>
                            <div class="invalid-feedback"><?= session('errors.jabatan') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="no_hp" class="form-premium-label">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control form-premium-control w-100 <?= session('errors.no_hp') ? 'is-invalid' : '' ?>" value="<?= old('no_hp') ?>" required placeholder="Contoh: 081234567890">
                        <?php if (session('errors.no_hp')): ?>
                            <div class="invalid-feedback"><?= session('errors.no_hp') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-premium-group">
                        <label for="email" class="form-premium-label">Email Mahasiswa</label>
                        <input type="email" name="email" id="email" class="form-control form-premium-control w-100 <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required placeholder="email@mahasiswa.com">
                        <?php if (session('errors.email')): ?>
                            <div class="invalid-feedback"><?= session('errors.email') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('anggota-ukm') ?>" class="btn btn-premium btn-premium-secondary">Batal</a>
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="bi bi-save"></i> Simpan Anggota
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
