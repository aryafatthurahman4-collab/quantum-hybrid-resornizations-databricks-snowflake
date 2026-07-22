<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Halaman Tidak Ditemukan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0d1442, #1a237e); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .error-card { background: #fff; border-radius: 20px; padding: 48px; text-align: center; max-width: 440px; box-shadow: 0 30px 80px rgba(0,0,0,.3); }
    .error-code { font-size: 5rem; font-weight: 800; color: #1a237e; line-height: 1; margin-bottom: 8px; }
    .error-icon { width: 72px; height: 72px; background: rgba(245,158,11,.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #f59e0b; margin: 0 auto 16px; }
    h5 { font-weight: 700; margin-bottom: 8px; }
    p { color: #64748b; font-size: .9rem; margin-bottom: 24px; }
    .btn-home { background: linear-gradient(135deg, #1a237e, #283593); border: none; border-radius: 10px; padding: 10px 24px; color: #fff; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .25s; }
    .btn-home:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,35,126,.3); color: #fff; }
</style>
</head>
<body>
<div class="error-card">
    <div class="error-icon"><i class="bi bi-question-circle"></i></div>
    <div class="error-code">404</div>
    <h5>Halaman Tidak Ditemukan</h5>
    <p>Halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
    <a href="{{ url('/dashboard') }}" class="btn-home"><i class="bi bi-house"></i> Kembali ke Dashboard</a>
</div>
</body>
</html>
