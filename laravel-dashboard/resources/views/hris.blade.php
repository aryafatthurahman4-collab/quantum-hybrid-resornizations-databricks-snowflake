<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS ITK - Sistem Informasi Manajemen Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-lg text-xl font-bold">💼</div>
            <div>
                <h1 class="text-xl font-bold text-slate-100">HRIS ITK</h1>
                <p class="text-xs text-slate-400">Sistem Informasi Manajemen Karyawan • Tailwind & Shadcn UI</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-full font-medium">System Active</span>
            <a href="/" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 transition rounded-lg text-white font-medium">⚛ Quantum Engine</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-900/40 via-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Manajemen SDM & Operational Analytics</h2>
            <p class="text-slate-400 max-w-3xl leading-relaxed">
                Platform terpadu untuk pengisian absensi, pengajuan cuti, rekapitulasi penilaian kinerja, perhitungan slip gaji digital, serta import massal data karyawan via Excel.
            </p>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div class="text-slate-400 text-sm mb-1">Total Karyawan</div>
                <div class="text-3xl font-bold text-indigo-400">45</div>
                <div class="text-xs text-slate-500 mt-2">6 Satuan Kerja</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div class="text-slate-400 text-sm mb-1">Kehadiran Hari Ini</div>
                <div class="text-3xl font-bold text-emerald-400">97.8%</div>
                <div class="text-xs text-emerald-500/80 mt-2">42 On-Time • 2 Late</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div class="text-slate-400 text-sm mb-1">Pengajuan Cuti / Izin</div>
                <div class="text-3xl font-bold text-amber-400">1</div>
                <div class="text-xs text-amber-500/80 mt-2">Menunggu Approval</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div class="text-slate-400 text-sm mb-1">Status Payroll</div>
                <div class="text-3xl font-bold text-cyan-400">Processed</div>
                <div class="text-xs text-slate-500 mt-2">Periode Juli 2026</div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Daftar Karyawan (Master Data)</h3>
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-medium text-white transition">
                    + Import Data Excel
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/60 text-slate-400 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nama Karyawan</th>
                            <th class="px-4 py-3">Jabatan</th>
                            <th class="px-4 py-3">Satuan Kerja</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Gaji Pokok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-4 py-4 font-semibold text-white">Arya Fatthurahman</td>
                            <td class="px-4 py-4">AI Engineering Lead</td>
                            <td class="px-4 py-4">Quantum Artificial Intelligence</td>
                            <td class="px-4 py-4"><span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded">Aktif</span></td>
                            <td class="px-4 py-4 text-emerald-400 font-mono">Rp 25.000.000</td>
                        </tr>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-4 py-4 font-semibold text-white">Budi Santoso</td>
                            <td class="px-4 py-4">Senior Developer</td>
                            <td class="px-4 py-4">Software Engineering</td>
                            <td class="px-4 py-4"><span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded">Aktif</span></td>
                            <td class="px-4 py-4 text-emerald-400 font-mono">Rp 18.000.000</td>
                        </tr>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-4 py-4 font-semibold text-white">Siti Rahma</td>
                            <td class="px-4 py-4">HR Operations Specialist</td>
                            <td class="px-4 py-4">Human Capital</td>
                            <td class="px-4 py-4"><span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded">Aktif</span></td>
                            <td class="px-4 py-4 text-emerald-400 font-mono">Rp 15.000.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
