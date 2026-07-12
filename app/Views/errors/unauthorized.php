<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - SI Pemantauan Kegiatan UKM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-card {
            max-width: 550px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            text-align: center;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 24px;
        }

        .error-title {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .error-desc {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-premium {
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-premium:hover {
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="error-card">
    <div class="icon-box">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h1 class="error-title">Akses Ditolak</h1>
    <p class="error-desc">Maaf, Anda tidak memiliki kewenangan atau hak akses untuk melihat halaman yang Anda minta. Silakan kembali ke dashboard yang sesuai dengan peran Anda.</p>
    
    <a href="<?= base_url('/') ?>" class="btn-premium">
        <i class="bi bi-house-door-fill"></i> Kembali ke Dashboard
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
