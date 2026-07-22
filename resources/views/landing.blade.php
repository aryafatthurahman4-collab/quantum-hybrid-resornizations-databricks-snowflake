<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS ITK - Human Resource Portal</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white min-h-screen flex flex-col justify-between relative overflow-x-hidden">
    
    <!-- Background Glow Effects -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-b from-indigo-600/20 via-violet-600/10 to-transparent blur-3xl pointer-events-none"></div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform duration-200">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <span class="font-bold text-lg tracking-tight text-white">HRIS ITK</span>
                    <span class="ml-2 inline-flex items-center rounded-full bg-indigo-950/80 px-2 py-0.5 text-[10px] font-semibold text-indigo-400 border border-indigo-800/50">Enterprise</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 h-9 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-md shadow-indigo-600/25 transition-all">
                    <i class="bi bi-speedometer2"></i> Dashboard HRIS
                </a>
                @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 h-9 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium text-xs shadow-md shadow-indigo-600/25 transition-all">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Portal
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 flex flex-col justify-center">
        <!-- Hero Tag -->
        <div class="flex justify-center mb-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-950/60 border border-indigo-800/60 text-indigo-300 text-xs font-medium backdrop-blur-md">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>System Operational & Online &mdash; hris-itk-ijk.test</span>
            </div>
        </div>

        <!-- Headline & Subtitle -->
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight mb-6">
                Sistem Informasi SDM & Payroll <span class="bg-gradient-to-r from-indigo-400 via-violet-300 to-indigo-200 bg-clip-text text-transparent">ITK Portal</span>
            </h1>
            <p class="text-slate-400 text-base sm:text-lg leading-relaxed">
                Platform terpadu pengelolaan data karyawan, presensi harian, pengajuan izin/cuti, penugasan kerja, penilaian kinerja terukur, hingga slip penggajian otomatis.
            </p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 mb-16">
            <a href="{{ route('login') }}" class="h-12 px-7 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/30 flex items-center gap-2 transition-all hover:-translate-y-0.5">
                <span>Masuk Sekarang</span>
                <i class="bi bi-arrow-right"></i>
            </a>
            <a href="http://hris-itk-ijk.test" class="h-12 px-7 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-semibold text-sm flex items-center gap-2 transition-all">
                <i class="bi bi-globe2 text-indigo-400"></i>
                <span>Akses Local Domain</span>
            </a>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center backdrop-blur-md">
                <p class="text-3xl font-extrabold text-white mb-1">100%</p>
                <p class="text-xs text-slate-400">Terintegrasi Database</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center backdrop-blur-md">
                <p class="text-3xl font-extrabold text-white mb-1">4 Format</p>
                <p class="text-xs text-slate-400">Export PDF/Excel/Word/PPTX</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center backdrop-blur-md">
                <p class="text-3xl font-extrabold text-white mb-1">3 Role</p>
                <p class="text-xs text-slate-400">Admin, Atasan & Karyawan</p>
            </div>
            <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center backdrop-blur-md">
                <p class="text-3xl font-extrabold text-white mb-1">Shadcn</p>
                <p class="text-xs text-slate-400">UI/UX Modern Design</p>
            </div>
        </div>

        <!-- Feature Grid (Shadcn Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all duration-300 group">
                <div class="h-12 w-12 rounded-xl bg-indigo-950 border border-indigo-800/50 flex items-center justify-center text-indigo-400 text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3 class="font-bold text-lg text-white mb-2">Manajemen Karyawan</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Pengelolaan lengkap profil pegawai, jabatan, unit satuan kerja, dan status kepegawaian secara real-time.</p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all duration-300 group">
                <div class="h-12 w-12 rounded-xl bg-indigo-950 border border-indigo-800/50 flex items-center justify-center text-indigo-400 text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <h3 class="font-bold text-lg text-white mb-2">Presensi & Pengajuan Izin</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Absensi presensi harian serta pengajuan izin/cuti dengan alur verifikasi dan persetujuan atasan.</p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 transition-all duration-300 group">
                <div class="h-12 w-12 rounded-xl bg-indigo-950 border border-indigo-800/50 flex items-center justify-center text-indigo-400 text-xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="bi bi-wallet-fill"></i>
                </div>
                <h3 class="font-bold text-lg text-white mb-2">Payroll & Slip Gaji</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Kalkulasi gaji berbasis komponen pendapatan dan potongan dengan fitur cetak slip rincian penggajian.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-8 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} HRIS ITK &mdash; Institut Teknologi Kalimantan. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
