-- Rancang Bangun Tabel Terpusat (Single Table Inheritance)
-- File: DB_UAS_PBO_TRPL1A_MuhmmadRizqiArdiansyah.sql
-- Mata Kuliah: Pemrograman Berbasis Objek (PBO)

CREATE TABLE tabel_mahasiswa (
    -- 1. Atribut Global (Induk)
    id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
    nama_mahasiswa VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    tarif_ukt_nominal DECIMAL(12, 2) NOT NULL,
    jenis_pembayaran ENUM('Mandiri', 'Bidikmisi', 'Prestasi') NOT NULL,

    -- 2. Atribut Spesifik (Anak - Set menjadi Nullable)
    -- Atribut Khusus Mahasiswa Mandiri
    golongan_ukt VARCHAR(10) DEFAULT NULL,
    nama_wali VARCHAR(255) DEFAULT NULL,

    -- Atribut Khusus Mahasiswa Bidikmisi
    nomor_kip_kuliah VARCHAR(50) DEFAULT NULL,
    dana_saku_subsidi DECIMAL(12, 2) DEFAULT NULL,

    -- Atribut Khusus Mahasiswa Prestasi
    nama_instansi_beasiswa VARCHAR(255) DEFAULT NULL,
    minimal_ipk_syarat DECIMAL(3, 2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Data Sampel (Minimal 20 Baris, Minimal 2 Data per Jenis Pembayaran)
INSERT INTO tabel_mahasiswa (
    nama_mahasiswa, 
    nim, 
    tarif_ukt_nominal, 
    jenis_pembayaran, 
    golongan_ukt, 
    nama_wali, 
    nomor_kip_kuliah, 
    dana_saku_subsidi, 
    nama_instansi_beasiswa, 
    minimal_ipk_syarat
) VALUES 
-- Kategori 1: Mahasiswa Mandiri (7 Baris)
('Rizqi Ardiansyah', '202401001', 7500000.00, 'Mandiri', 'Golongan 5', 'Budi Ardiansyah', NULL, NULL, NULL, NULL),
('Ahmad Fauzi', '202401002', 6000000.00, 'Mandiri', 'Golongan 4', 'Siti Rahma', NULL, NULL, NULL, NULL),
('Putri Lestari', '202401003', 9000000.00, 'Mandiri', 'Golongan 6', 'Hendra Lestari', NULL, NULL, NULL, NULL),
('Rian Hidayat', '202401004', 4500000.00, 'Mandiri', 'Golongan 3', 'Joko Hidayat', NULL, NULL, NULL, NULL),
('Siti Aminah', '202401005', 7500000.00, 'Mandiri', 'Golongan 5', 'Ahmad Subagyo', NULL, NULL, NULL, NULL),
('Fikri Haikal', '202401006', 6000000.00, 'Mandiri', 'Golongan 4', 'Taufik Haikal', NULL, NULL, NULL, NULL),
('Dewi Sartika', '202401007', 9000000.00, 'Mandiri', 'Golongan 6', 'Wawan Setiawan', NULL, NULL, NULL, NULL),

-- Kategori 2: Mahasiswa Bidikmisi (7 Baris)
('Eko Prasetyo', '202401008', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-001', 950000.00, NULL, NULL),
('Lani Wijaya', '202401009', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-002', 950000.00, NULL, NULL),
('Bagus Setiawan', '202401010', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-003', 950000.00, NULL, NULL),
('Fitri Handayani', '202401011', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-004', 950000.00, NULL, NULL),
('Deni Ramadhan', '202401012', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-005', 950000.00, NULL, NULL),
('Sri Wahyuni', '202401013', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-006', 950000.00, NULL, NULL),
('Andi Wijaya', '202401014', 0.00, 'Bidikmisi', NULL, NULL, 'KIP-2024-007', 950000.00, NULL, NULL),

-- Kategori 3: Mahasiswa Prestasi (6 Baris)
('Amelia Putri', '202401015', 2000000.00, 'Prestasi', NULL, NULL, NULL, NULL, 'Dinas Pendidikan', 3.50),
('Guntur Wibowo', '202401016', 1500000.00, 'Prestasi', NULL, NULL, NULL, NULL, 'Yayasan Supersemar', 3.25),
('Mega Utami', '202401017', 0.00, 'Prestasi', NULL, NULL, NULL, NULL, 'PT Djarum', 3.75),
('Yusuf Mahendra', '202401018', 2500000.00, 'Prestasi', NULL, NULL, NULL, NULL, 'Bank Indonesia', 3.40),
('Indah Permata', '202401019', 1000000.00, 'Prestasi', NULL, NULL, NULL, NULL, 'Kementerian Pemuda dan Olahraga', 3.30),
('Roni Syahputra', '202401020', 0.00, 'Prestasi', NULL, NULL, NULL, NULL, 'Pertamina Foundation', 3.60);
