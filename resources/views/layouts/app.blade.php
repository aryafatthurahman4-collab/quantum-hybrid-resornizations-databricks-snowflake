<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml">
    <title>@yield('title', 'Dashboard') | HRIS ITK</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    @auth
    <div class="min-h-screen flex bg-slate-50/60 dark:bg-slate-950">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 lg:hidden" 
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-xl lg:shadow-none">
            
            <!-- Sidebar Header / Logo -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-700 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                        <i class="bi bi-person-badge-fill text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-lg tracking-tight bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">HRIS ITK</span>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-800/50">v1.0</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Human Resource Portal</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            @php
                $route = Route::currentRouteName();
                $user = Auth::user();
                $role = $user->role;
            @endphp

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6 custom-scrollbar">
                <!-- Overview Section -->
                <div>
                    <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Overview</div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="bi bi-grid-1x2-fill text-base"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Master Data (Admin Only) -->
                @if($role === 'admin')
                <div>
                    <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Master Data</div>
                    <div class="space-y-1">
                        <a href="{{ route('jabatan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'jabatan') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-briefcase text-base"></i>
                            <span>Jabatan</span>
                        </a>
                        <a href="{{ route('satuan-kerja.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'satuan-kerja') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-building text-base"></i>
                            <span>Satuan Kerja</span>
                        </a>
                        <a href="{{ route('karyawan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'karyawan') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-people text-base"></i>
                            <span>Karyawan</span>
                        </a>
                        <a href="{{ route('komponen-gaji.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'komponen-gaji') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-cash-stack text-base"></i>
                            <span>Komponen Gaji</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Transaksi Section -->
                <div>
                    <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Aktivitas & Transaksi</div>
                    <div class="space-y-1">
                        <a href="{{ route('absensi.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'absensi.index') || str_starts_with($route, 'absensi.create') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-calendar-check text-base"></i>
                            <span>Absensi Presensi</span>
                        </a>

                        @if(in_array($role, ['admin', 'atasan']))
                        <a href="{{ route('absensi.rekap') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'absensi.rekap') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-file-earmark-bar-graph text-base"></i>
                            <span>Rekap Absensi</span>
                        </a>
                        @endif

                        <a href="{{ route('pengajuan-izin.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'pengajuan-izin') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-envelope-open text-base"></i>
                            <span>Izin / Cuti</span>
                        </a>

                        <a href="{{ route('tugas.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'tugas') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-check2-square text-base"></i>
                            <span>Penugasan Kerja</span>
                        </a>

                        <a href="{{ route('penilaian.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'penilaian') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-star text-base"></i>
                            <span>Penilaian Kinerja</span>
                        </a>

                        <a href="{{ route('penggajian.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'penggajian') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-wallet2 text-base"></i>
                            <span>Penggajian (Payroll)</span>
                        </a>
                    </div>
                </div>

                <!-- Tools & Reports (Admin / Atasan) -->
                @if(in_array($role, ['admin', 'atasan']))
                <div>
                    <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Laporan & Utility</div>
                    <div class="space-y-1">
                        <a href="{{ route('import.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'import') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-file-earmark-arrow-up text-base"></i>
                            <span>Import Data Excel</span>
                        </a>
                        <a href="{{ route('laporan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($route, 'laporan') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                            <i class="bi bi-pie-chart text-base"></i>
                            <span>Pusat Laporan</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            <!-- User Badge Box -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/50">
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 capitalize flex items-center gap-1">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $user->role }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navbar -->
            <header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 sticky top-0 z-30 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle Button -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            title="Toggle Dark Mode">
                        <i x-show="!darkMode" class="bi bi-moon-stars text-lg"></i>
                        <i x-show="darkMode" class="bi bi-sun text-lg text-amber-400"></i>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" class="p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors relative">
                            <i class="bi bi-bell text-lg"></i>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-indigo-600 ring-2 ring-white dark:ring-slate-900"></span>
                        </button>
                        <div x-show="notifOpen" @click.away="notifOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200 dark:border-slate-800 p-4 z-50">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800 mb-3">
                                <span class="font-bold text-sm text-slate-900 dark:text-white">Notifikasi</span>
                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Sistem HRIS</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex gap-3 text-xs">
                                    <div class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800 dark:text-slate-200">Sistem Aktif</p>
                                        <p class="text-slate-500 dark:text-slate-400">Database & Portal HRIS berjalan normal.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ userMenuOpen: false }" class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-bold text-sm flex items-center justify-center shadow-md shadow-indigo-500/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-bold text-slate-900 dark:text-white leading-none mb-1">{{ $user->name }}</p>
                                <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider leading-none">{{ $role }}</p>
                            </div>
                            <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <!-- Menu dropdown -->
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200 dark:border-slate-800 py-1 z-50 divide-y divide-slate-100 dark:divide-slate-800">
                            <div class="px-4 py-3">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <i class="bi bi-speedometer2"></i> Dashboard HRIS
                                </a>
                            </div>
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Notifications Flash Alerts -->
                @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 p-4 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-300 flex-shrink-0">
                            <i class="bi bi-check-lg text-lg"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div class="mb-6 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 p-4 text-amber-800 dark:text-amber-200 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-amber-100 dark:bg-amber-900 flex items-center justify-center text-amber-600 dark:text-amber-300 flex-shrink-0">
                            <i class="bi bi-exclamation-triangle text-lg"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('warning') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 p-4 text-rose-800 dark:text-rose-200 flex items-center justify-between shadow-sm animate-in">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-rose-100 dark:bg-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-300 flex-shrink-0">
                            <i class="bi bi-x-circle text-lg"></i>
                        </div>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>
