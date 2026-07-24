<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <title>Login | HRIS ITK System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-4 relative overflow-hidden selection:bg-indigo-500 selection:text-white">
    <!-- Glowing background blur accents -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-600/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 animate-in">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 group mb-4">
                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-white">HRIS ITK</h1>
            <p class="text-xs text-slate-400 mt-1">Human Resource Information & Payroll System</p>
            <span class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-[11px] font-mono text-indigo-400">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                hris-itk-ijk.test
            </span>
        </div>

        <!-- Shadcn UI Glass Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            <h2 class="text-lg font-bold text-white mb-1">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-400 mb-6">Masukkan kredensial Anda untuk mengakses portal HRIS.</p>

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-950/60 border border-rose-800/80 text-rose-300 text-xs flex items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-lg text-rose-400 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4" id="loginForm">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <input type="email" name="email" id="emailInput" 
                               class="w-full h-11 pl-10 pr-4 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                               placeholder="admin@hr.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-300">Kata Sandi (Password)</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="bi bi-lock"></i>
                        </div>
                        <input type="password" name="password" id="passwordInput" 
                               class="w-full h-11 pl-10 pr-4 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" id="submitBtn"
                        class="w-full h-11 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition-all duration-200 flex items-center justify-center gap-2 group">
                    <span id="btnText" class="flex items-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </button>
            </form>

            <!-- Demo Preset Selector -->
            <div class="mt-6 pt-6 border-t border-slate-800">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">Klik Cepat Akun Demo</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillDemo('admin@hr.com', 'password')"
                            class="p-2 rounded-lg bg-slate-800/80 hover:bg-indigo-950/60 border border-slate-700/60 hover:border-indigo-500/50 text-center transition-all text-xs group">
                        <p class="font-bold text-white group-hover:text-indigo-300">Admin</p>
                        <p class="text-[10px] text-slate-400 truncate">admin@hr.com</p>
                    </button>

                    <button type="button" onclick="fillDemo('atasan@hr.com', 'password')"
                            class="p-2 rounded-lg bg-slate-800/80 hover:bg-indigo-950/60 border border-slate-700/60 hover:border-indigo-500/50 text-center transition-all text-xs group">
                        <p class="font-bold text-white group-hover:text-indigo-300">Atasan</p>
                        <p class="text-[10px] text-slate-400 truncate">atasan@hr.com</p>
                    </button>

                    <button type="button" onclick="fillDemo('karyawan@hr.com', 'password')"
                            class="p-2 rounded-lg bg-slate-800/80 hover:bg-indigo-950/60 border border-slate-700/60 hover:border-indigo-500/50 text-center transition-all text-xs group">
                        <p class="font-bold text-white group-hover:text-indigo-300">Karyawan</p>
                        <p class="text-[10px] text-slate-400 truncate">karyawan@hr.com</p>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} HRIS ITK &mdash; Institute Technology Computer
        </p>
    </div>

    <script>
        function fillDemo(email, pass) {
            document.getElementById('emailInput').value = email;
            document.getElementById('passwordInput').value = pass;
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `<i class="bi bi-arrow-repeat animate-spin text-lg"></i> Processing...`;
        });
    </script>
</body>
</html>