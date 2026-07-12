<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI Pemantauan Kegiatan UKM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('<?= base_url('images/image.png') ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            max-width: 440px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            color: #0f172a;
        }

        .login-title {
            font-weight: 800;
            letter-spacing: -1px;
            color: #1b75bb;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 16px;
            color: #0f172a;
            font-size: 0.9rem;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: #1b75bb;
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(27, 117, 187, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-primary {
            background-color: #1b75bb;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.1s ease;
        }

        .btn-primary:hover {
            background-color: #125e98;
        }

        .alert-premium {
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 0.875rem;
            padding: 14px;
        }
        
        .alert-premium-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .alert-premium-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="logo-container mb-3 text-center">
        <?php if (file_exists(FCPATH . 'images/logo Unitomo.png')): ?>
            <img src="<?= base_url('images/logo Unitomo.png') ?>" alt="Logo" style="height: 80px; max-width: 100%; object-fit: contain;">
        <?php elseif (file_exists(FCPATH . 'images/logo.png')): ?>
            <img src="<?= base_url('images/logo.png') ?>" alt="Logo" style="height: 60px; max-width: 100%; object-fit: contain;">
        <?php else: ?>
            <i class="bi bi-shield-check-fill text-primary" style="font-size: 3rem; color: #1e3a8a !important;"></i>
        <?php endif; ?>
    </div>
    
    <h3 class="mb-1 login-title">Masuk</h3>
    <p class="text-muted mb-4" style="font-size: 0.85rem;">Sistem Informasi Pemantauan Kegiatan UKM</p>

    <!-- Success message (e.g. after logout) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-premium alert-premium-success alert-dismissible fade show text-start mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Error message (e.g. wrong password) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-premium alert-premium-danger alert-dismissible fade show text-start mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Validation Errors -->
    <?php $validationErrors = session()->getFlashdata('errors'); ?>
    <?php if (!empty($validationErrors)): ?>
        <div class="alert alert-premium alert-premium-danger alert-dismissible fade show text-start mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <ul class="mb-0 ps-3">
                <?php foreach ($validationErrors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/login') ?>" method="post" class="text-start">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required autofocus placeholder="nama@unitomo.ac.id">
        </div>
        
        <div class="mb-4">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Masuk Ke Akun</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
