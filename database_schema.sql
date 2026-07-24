-- Database: hr_management
-- HRIS ITK - Human Resource Information System
-- Universitas Darma Persada
-- Fakultas Teknologi Informasi

SET FOREIGN_KEY_CHECKS = 0;

-- Create Database (Commented out for cPanel hosting compatibility)
-- CREATE DATABASE IF NOT EXISTS hr_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE hr_management;



-- Table: jabatan
CREATE TABLE IF NOT EXISTS jabatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(100) NOT NULL,
    level INT DEFAULT 1 COMMENT '1=staf, 2=spv, 3=manajer, 4=direktur',
    gaji_pokok DECIMAL(12,2) DEFAULT 0.00,
    tunjangan DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: satuan_kerja
CREATE TABLE IF NOT EXISTS satuan_kerja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_divisi VARCHAR(100) NOT NULL,
    singkatan VARCHAR(50) DEFAULT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'atasan', 'karyawan') NOT NULL DEFAULT 'karyawan',
    karyawan_id BIGINT UNSIGNED DEFAULT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_karyawan_id (karyawan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: karyawan
CREATE TABLE IF NOT EXISTS karyawan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED DEFAULT NULL,
    nik VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') DEFAULT 'Laki-laki',
    tanggal_lahir DATE DEFAULT NULL,
    alamat TEXT,
    no_telp VARCHAR(20),
    tanggal_masuk DATE DEFAULT NULL,
    jabatan_id INT DEFAULT NULL,
    satuan_kerja_id INT DEFAULT NULL,
    aktif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (jabatan_id) REFERENCES jabatan(id) ON DELETE SET NULL,
    FOREIGN KEY (satuan_kerja_id) REFERENCES satuan_kerja(id) ON DELETE SET NULL,
    INDEX idx_nik (nik),
    INDEX idx_jabatan_id (jabatan_id),
    INDEX idx_satuan_kerja_id (satuan_kerja_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: komponen_gaji
CREATE TABLE IF NOT EXISTS komponen_gaji (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_komponen VARCHAR(100) NOT NULL,

    jenis ENUM('earning', 'potongan') NOT NULL,
    tipe ENUM('tetap', 'variabel') NOT NULL,
    nominal_default DECIMAL(12,2) DEFAULT 0.00,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_jenis (jenis),
    INDEX idx_tipe (tipe)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: import_logs
CREATE TABLE IF NOT EXISTS import_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    nama_file VARCHAR(150) NOT NULL,
    jumlah_data INT DEFAULT 0,
    status_import ENUM('berhasil', 'sebagian', 'gagal') DEFAULT 'gagal',
    tanggal_import DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_tanggal_import (tanggal_import)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: absensi
CREATE TABLE IF NOT EXISTS absensi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME DEFAULT NULL,
    jam_keluar TIME DEFAULT NULL,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') DEFAULT 'hadir',
    keterlambatan_menit INT DEFAULT 0,
    lembur_menit INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    INDEX idx_karyawan_id (karyawan_id),
    INDEX idx_tanggal (tanggal),
    INDEX idx_status (status),
    UNIQUE KEY unique_attendance (karyawan_id, tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pengajuan_izin
CREATE TABLE IF NOT EXISTS pengajuan_izin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    jenis ENUM('cuti', 'izin', 'sakit') NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    alasan TEXT,
    status_pengajuan ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    INDEX idx_karyawan_id (karyawan_id),
    INDEX idx_status_pengajuan (status_pengajuan),
    INDEX idx_tanggal (tanggal_mulai, tanggal_selesai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tugas_karyawan
CREATE TABLE IF NOT EXISTS tugas_karyawan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    manajer_id BIGINT UNSIGNED NOT NULL,
    judul_tugas VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    deadline DATE DEFAULT NULL,
    status_tugas ENUM('belum', 'proses', 'selesai', 'revisi') DEFAULT 'belum',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (manajer_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    INDEX idx_karyawan_id (karyawan_id),
    INDEX idx_manajer_id (manajer_id),
    INDEX idx_status_tugas (status_tugas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: penilaian_kinerja
CREATE TABLE IF NOT EXISTS penilaian_kinerja (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    manajer_id BIGINT UNSIGNED NOT NULL,
    periode VARCHAR(20) NOT NULL COMMENT 'Format: 2026-Q2, 2026-S1',
    skor_kualitas DECIMAL(4,2) DEFAULT 0.00,
    skor_kuantitas DECIMAL(4,2) DEFAULT 0.00,
    nilai_akhir DECIMAL(4,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (manajer_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    INDEX idx_karyawan_id (karyawan_id),
    INDEX idx_manajer_id (manajer_id),
    INDEX idx_periode (periode),
    UNIQUE KEY unique_evaluation (karyawan_id, periode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: penggajian
CREATE TABLE IF NOT EXISTS penggajian (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    periode CHAR(7) NOT NULL COMMENT 'Format: YYYY-MM',
    total_earning DECIMAL(14,2) DEFAULT 0.00,
    total_potongan DECIMAL(14,2) DEFAULT 0.00,
    take_home_pay DECIMAL(14,2) DEFAULT 0.00,
    tanggal_pembayaran DATE DEFAULT NULL,
    status_approval ENUM('draft', 'menunggu', 'disetujui', 'dibayar') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    INDEX idx_karyawan_id (karyawan_id),
    INDEX idx_periode (periode),
    INDEX idx_status_approval (status_approval),
    UNIQUE KEY unique_payroll (karyawan_id, periode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: detail_penggajian
CREATE TABLE IF NOT EXISTS detail_penggajian (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    penggajian_id BIGINT UNSIGNED NOT NULL,
    komponen_gaji_id INT NOT NULL,
    nominal DECIMAL(12,2) DEFAULT 0.00,
    keterangan VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penggajian_id) REFERENCES penggajian(id) ON DELETE CASCADE,
    FOREIGN KEY (komponen_gaji_id) REFERENCES komponen_gaji(id) ON DELETE CASCADE,
    INDEX idx_penggajian_id (penggajian_id),
    INDEX idx_komponen_gaji_id (komponen_gaji_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Sample Data for Jabatan
INSERT INTO jabatan (nama_jabatan, level, gaji_pokok, tunjangan) VALUES
('Administrator', 4, 5000000, 1000000),
('Manager', 3, 8000000, 2000000),
('Senior Programmer', 2, 8500000, 1500000),
('Programmer', 1, 6000000, 800000),
('Staff IT', 1, 4500000, 500000),
('Staff HRD', 1, 4000000, 500000);

-- Insert Sample Data for Satuan Kerja
INSERT INTO satuan_kerja (nama_divisi, singkatan, keterangan) VALUES
('Divisi Pengembangan Aplikasi', 'DEV', 'Mengelola pengembangan aplikasi web dan mobile'),
('Divisi Infrastruktur dan Jaringan', 'INFRA', 'Mengelola server dan jaringan'),
('Divisi SDM dan Umum', 'HR', 'Mengelola sumber daya manusia dan administrasi'),
('Divisi Pemasaran dan Penjualan', 'MARKETING', 'Mengelola pemasaran dan penjualan');

-- Insert Sample Data for Komponen Gaji
INSERT INTO komponen_gaji (nama_komponen, jenis, tipe, nominal_default, keterangan) VALUES
('Tunjangan Makan', 'earning', 'tetap', 600000, 'Tunjangan makan harian'),
('Tunjangan Transport', 'earning', 'tetap', 500000, 'Tunjangan transportasi'),
('Lembur', 'earning', 'variabel', 0, 'Pembayaran lembur per jam'),
('Bonus Kinerja', 'earning', 'variabel', 0, 'Bonus berdasarkan penilaian kinerja'),
('Potongan Keterlambatan', 'potongan', 'variabel', 0, 'Potongan karena keterlambatan'),
('BPJS Kesehatan', 'potongan', 'tetap', 0, 'Potongan BPJS Kesehatan 1%'),
('BPJS Ketenagakerjaan', 'potongan', 'tetap', 0, 'Potongan BPJS TK 2%'),
('Pajak PPh 21', 'potongan', 'variabel', 0, 'Potongan pajak penghasilan');

-- Insert Sample Users (password: password - bcrypt hash)
INSERT INTO users (name, email, password, role) VALUES
('Admin HRIS', 'admin@hr.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Manager HRD', 'atasan@hr.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'atasan'),
('Karyawan Staff', 'karyawan@hr.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan');

-- Insert Sample Karyawan
INSERT INTO karyawan (user_id, nik, nama_lengkap, jenis_kelamin, tanggal_lahir, no_telp, tanggal_masuk, jabatan_id, satuan_kerja_id, aktif) VALUES
(1, 'ADM001', 'Administrator', 'Laki-laki', '1990-01-01', '081234567890', '2020-01-01', 1, 3, 1),
(2, 'MGR001', 'Manager HRD', 'Laki-laki', '1985-05-15', '081234567891', '2019-03-01', 2, 3, 1),
(3, 'STF001', 'Karyawan Staff', 'Laki-laki', '1995-08-20', '081234567892', '2021-06-01', 5, 1, 1);

-- Update users with karyawan_id
UPDATE users SET karyawan_id = 1 WHERE id = 1;
UPDATE users SET karyawan_id = 2 WHERE id = 2;
UPDATE users SET karyawan_id = 3 WHERE id = 3;

-- Display summary
-- SELECT 'Database created successfully!' AS Status;

SET FOREIGN_KEY_CHECKS = 1;


