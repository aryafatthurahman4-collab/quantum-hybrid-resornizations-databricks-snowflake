# HRIS ITK - Sistem Informasi Manajemen Karyawan

Aplikasi Web Manajemen Karyawan berbasis Laravel dengan fitur Import Excel, Absensi, Penilaian Kinerja, dan Perhitungan Gaji. Dilengkapi dengan antarmuka modern menggunakan Tailwind CSS dan Shadcn UI/UX dengan animasi yang halus.

## 🚀 Fitur Utama

### Manajemen SDM Terintegrasi
- **Autentikasi & Otorisasi** - Multi-role system (Admin, Atasan, Karyawan) dengan hak akses terkontrol
- **Master Data** - Kelola Jabatan, Satuan Kerja, Karyawan, dan Komponen Gaji
- **Import Excel** - Import data massal dari file Excel dengan validasi otomatis
- **Absensi** - Pencatatan kehadiran, rekapitulasi, dan koreksi data
- **Pengajuan Izin/Cuti** - Workflow approval untuk izin dan cuti
- **Penugasan** - Beri tugas, pantau progres, dan evaluasi penyelesaian
- **Penilaian Kinerja** - Sistem penilaian berbasis indikator dengan perhitungan otomatis
- **Penggajian** - Perhitungan gaji otomatis dengan slip gaji digital
- **Laporan** - Berbagai laporan manajerial untuk keputusan strategis

### Teknologi Modern
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Tailwind CSS + Shadcn UI/UX
- **Database**: MySQL/MariaDB
- **Animations**: Alpine.js untuk interaktivitas dan animasi halus
- **Excel Processing**: PhpSpreadsheet
- **PDF Generation**: Laravel DOMPDF

## 📋 Persyaratan Sistem

### Local Development
- PHP 8.1 atau lebih tinggi
- Composer 2.x
- Node.js 16+ dan NPM
- MySQL 5.7+ / MariaDB 10.3+
- Web Server (Laragon/XAMPP/Apache/Nginx)

### Production (Jagoan Hosting)
- PHP 8.1+ pada server
- MySQL/MariaDB database
- SSL Certificate (HTTPS)
- Akses cPanel/SSH

## 🛠️ Instalasi Local Development

```bash
# 1. Clone repository
git clone https://github.com/aryafatthurahman4-collab/hris-itk.git
cd hris-itk

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database di .env
# DB_DATABASE=hris_db
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migrasi database
php artisan migrate

# 8. Jalankan seeder (opsional - untuk data demo)
php artisan db:seed

# 9. Build assets frontend
npm run build

# 10. Jalankan development server
php artisan serve
```

## 👥 Akun Demo

| Role     | Email            | Password  |
|----------|------------------|-----------|
| Admin    | admin@hr.com     | password  |
| Atasan   | atasan@hr.com    | password  |
| Karyawan | karyawan@hr.com  | password  |

## 🎨 Antarmuka & UI/UX

### Shadcn UI Components
- **Button** - Variants: default, secondary, destructive, outline, ghost, link
- **Card** - Container dengan header, content, dan footer
- **Input** - Form input dengan styling konsisten
- **Badge** - Status indicators dengan berbagai warna
- **Table** - Tabel responsif dengan styling modern
- **Alert** - Notifikasi sukses, warning, dan error
- **Modal** - Dialog dengan animasi smooth
- **Dropdown** - Menu dropdown dengan animasi
- **Tabs** - Tab navigation untuk konten terorganisir

### Animations
- Fade in/out transitions
- Slide animations (top, bottom, left, right)
- Scale animations untuk modals
- Smooth hover effects
- Loading states dengan skeleton screens

## 📊 Struktur Database

### 12 Tabel Utama
1. **jabatan** - Data jabatan dan level
2. **satuan_kerja** - Unit/divisi organisasi
3. **users** - Akun login dan role
4. **karyawan** - Profil lengkap karyawan
5. **absensi** - Catatan kehadiran harian
6. **pengajuan_izin** - Permohonan izin/cuti
7. **tugas_karyawan** - Data penugasan kerja
8. **penilaian_kinerja** - Hasil evaluasi kinerja
9. **komponen_gaji** - Komponen penghasilan dan potongan
10. **penggajian** - Transaksi payroll per periode
11. **detail_penggajian** - Rincian komponen gaji
12. **import_logs** - Riwayat import Excel

## 🚢 Deployment ke Jagoan Hosting

Lihat panduan lengkap deployment di [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

### Quick Deployment Steps
1. Setup database via phpMyAdmin
2. Upload files ke server (File Manager atau Git)
3. Configure `.env` file
4. Install dependencies: `composer install --no-dev`
5. Build assets: `npm run build`
6. Set permissions: `chmod -R 755 storage bootstrap/cache`
7. Run migrations: `php artisan migrate --force`
8. Clear and cache: `php artisan optimize`

### Automated Deployment
Gunakan script deployment otomatis:
```bash
chmod +x deploy.sh
./deploy.sh
```

## 🔧 Konfigurasi

### Environment Variables
```env
APP_NAME="HRIS ITK"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris_db
DB_USERNAME=root
DB_PASSWORD=
```

### Tailwind CSS Configuration
File konfigurasi: `tailwind.config.js`
- Custom color scheme (Shadcn UI)
- Animation keyframes
- Responsive breakpoints
- Custom utilities

## 📝 Development Commands

```bash
# Development
php artisan serve              # Start development server
npm run dev                    # Watch assets for changes

# Database
php artisan migrate            # Run migrations
php artisan migrate:rollback    # Rollback last migration
php artisan db:seed            # Run seeders
php artisan migrate:fresh       # Fresh migration with seed

# Cache & Optimization
php artisan cache:clear        # Clear application cache
php artisan config:cache       # Cache configuration
php artisan route:cache        # Cache routes
php artisan view:cache         # Cache views
php artisan optimize           # Optimize for production

# Testing
php artisan test               # Run tests
php artisan tinker             # Interactive REPL
```

## 🔒 Keamanan

- Password hashing dengan Laravel bcrypt
- CSRF protection pada semua form
- SQL injection prevention dengan Eloquent ORM
- XSS protection dengan Blade templating
- Role-based access control (RBAC)
- Input validation dan sanitization

## 📈 Performance Optimization

- Database indexing pada kolom yang sering dicari
- Query optimization dengan Eager Loading
- Asset minification dan bundling dengan Vite
- Route caching untuk production
- View caching untuk rendering lebih cepat
- Lazy loading untuk komponen yang berat

## 🤝 Kontribusi

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Project ini dilisensikan under MIT License - lihat file LICENSE untuk detail

## 📞 Support

Untuk bantuan dan pertanyaan:
- Documentation: [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- Laravel Docs: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Shadcn UI: https://ui.shadcn.com

## 🎯 Roadmap

- [ ] Mobile app (React Native)
- [ ] Biometric integration untuk absensi
- [ ] Advanced analytics dashboard
- [ ] Email notifications system
- [ ] Multi-language support
- [ ] API untuk integrasi sistem lain
