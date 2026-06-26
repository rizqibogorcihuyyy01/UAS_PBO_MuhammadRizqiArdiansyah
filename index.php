<?php
/**
 * Dashboard Registrasi Pembayaran Kuliah Mahasiswa
 * File: index.php
 * Mengintegrasikan database dengan konsep Pemrograman Berbasis Objek (PBO).
 */

// Load file koneksi dan abstraksi OOP
require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikMisi.php';
require_once 'MahasiswaPrestasi.php';

// Inisialisasi variabel statistik
$total_mahasiswa = 0;
$total_mandiri = 0;
$total_bidikmisi = 0;
$total_prestasi = 0;
$total_tagihan_aktif = 0.0;

$list_mandiri = [];
$list_bidikmisi = [];
$list_prestasi = [];

if ($db_connected && $pdo !== null) {
    try {
        // Fetch seluruh data mahasiswa dari database
        $query = "SELECT * FROM tabel_mahasiswa ORDER BY nama_mahasiswa ASC";
        $stmt = $pdo->query($query);
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $total_mahasiswa++;
            
            // Instansiasi objek sesuai dengan jenis pembayaran (Polimorfisme & Pewarisan)
            if ($row['jenis_pembayaran'] === 'Mandiri') {
                $mhs = new MahasiswaMandiri(
                    (int)$row['id_mahasiswa'],
                    $row['nama_mahasiswa'],
                    $row['nim'],
                    (int)$row['semester'],
                    (float)$row['tarif_ukt_nominal'],
                    $row['golongan_ukt'] ?? '-',
                    $row['nama_wali'] ?? '-'
                );
                $list_mandiri[] = $mhs;
                $total_mandiri++;
                $total_tagihan_aktif += $mhs->hitungTagihanSemester(); // Polimorfisme hitungTagihanSemester()
            } 
            elseif ($row['jenis_pembayaran'] === 'Bidikmisi') {
                $mhs = new MahasiswaBidikMisi(
                    (int)$row['id_mahasiswa'],
                    $row['nama_mahasiswa'],
                    $row['nim'],
                    (int)$row['semester'],
                    (float)$row['tarif_ukt_nominal'],
                    $row['nomor_kip_kuliah'] ?? '-',
                    (float)($row['dana_saku_subsidi'] ?? 0.0)
                );
                $list_bidikmisi[] = $mhs;
                $total_bidikmisi++;
                $total_tagihan_aktif += $mhs->hitungTagihanSemester();
            } 
            elseif ($row['jenis_pembayaran'] === 'Prestasi') {
                $mhs = new MahasiswaPrestasi(
                    (int)$row['id_mahasiswa'],
                    $row['nama_mahasiswa'],
                    $row['nim'],
                    (int)$row['semester'],
                    (float)$row['tarif_ukt_nominal'],
                    $row['nama_instansi_beasiswa'] ?? '-',
                    (float)($row['minimal_ipk_syarat'] ?? 0.0)
                );
                $list_prestasi[] = $mhs;
                $total_prestasi++;
                $total_tagihan_aktif += $mhs->hitungTagihanSemester();
            }
        }
    } catch (\PDOException $e) {
        $db_connected = false;
        $db_error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Registrasi Pembayaran Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
    <!-- FontAwesome untuk Icon premium -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="fa-solid fa-graduation-cap text-highlight"></i>
                <span>SI-Registrasi</span>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item active">
                <a href="#dashboard"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            </li>
            <li class="menu-item">
                <a href="#mandiri-section"><i class="fa-solid fa-wallet text-highlight"></i> Jlr. Mandiri</a>
            </li>
            <li class="menu-item">
                <a href="#bidikmisi-section"><i class="fa-solid fa-hand-holding-dollar text-success"></i> Jlr. Bidikmisi</a>
            </li>
            <li class="menu-item">
                <a href="#prestasi-section"><i class="fa-solid fa-award text-warning"></i> Jlr. Prestasi</a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <p>UAS PBO &copy; 2026</p>
            <p>Muhammad Rizqi Ardiansyah</p>
        </div>
    </aside>

    <!-- Main Layout -->
    <div class="main-layout" id="dashboard">
        
        <!-- Navbar -->
        <header class="navbar">
            <div class="navbar-title">
                Registrasi & UKT Mahasiswa
            </div>
            
            <!-- Pencarian Instan -->
            <div style="position: relative; width: 300px;">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama atau NIM..." style="width: 100%; padding: 10px 16px 10px 40px; border-radius: 20px; border: 1px solid var(--border-card); background: var(--bg-secondary); color: var(--text-primary); outline: none; font-size: 13px; transition: var(--transition-smooth);">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 16px; top: 13px; color: var(--text-muted);"></i>
            </div>

            <div class="navbar-user">
                <div class="user-info">
                    <span class="user-name">M. Rizqi Ardiansyah</span>
                    <span class="user-role">TRPL 1A</span>
                </div>
                <div class="user-avatar">RA</div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="content-body">

            <!-- Cek Koneksi Database -->
            <?php if (!$db_connected): ?>
                <div class="db-alert">
                    <h4><i class="fa-solid fa-circle-exclamation"></i> Koneksi Database Gagal</h4>
                    <p>Dashboard tidak dapat terhubung ke database. Silakan pastikan Anda telah membuat database dan mengimpor tabel mahasiswa. Ikuti instruksi berikut untuk menyiapkan sistem:</p>
                    <code>
                        1. Buka MySQL client (seperti phpMyAdmin atau CLI MySQL).<br>
                        2. Jalankan perintah: <strong>CREATE DATABASE db_uas_pbo;</strong><br>
                        3. Impor berkas <strong>DB_UAS_PBO_TRPL1A_MuhmmadRizqiArdiansyah.sql</strong> ke dalam database tersebut.<br>
                        4. Sesuaikan kredensial di berkas <strong>koneksi.php</strong> jika diperlukan.<br><br>
                        Detail Error: <?php echo htmlspecialchars($db_error); ?>
                    </code>
                </div>
            <?php endif; ?>

            <!-- KPI Statistik Grid -->
            <section class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon all">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-val"><?php echo $total_mahasiswa; ?></span>
                        <span class="kpi-lbl">Total Mahasiswa</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon mandiri">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-val"><?php echo $total_mandiri; ?></span>
                        <span class="kpi-lbl">Jalur Mandiri</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon bidikmisi">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-val"><?php echo $total_bidikmisi; ?></span>
                        <span class="kpi-lbl">Jalur Bidikmisi</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon prestasi">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-val"><?php echo $total_prestasi; ?></span>
                        <span class="kpi-lbl">Jalur Prestasi</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon tagihan">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="kpi-data">
                        <span class="kpi-val" style="font-size: 16px;">Rp <?php echo number_format($total_tagihan_aktif, 0, ',', '.'); ?></span>
                        <span class="kpi-lbl">Total Tagihan Aktif</span>
                    </div>
                </div>
            </section>

            <!-- Kategori 1: Mahasiswa Mandiri -->
            <section class="section-container" id="mandiri-section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fa-solid fa-wallet text-highlight"></i>
                        <span>Daftar Registrasi Mahasiswa Jalur Mandiri</span>
                    </div>
                    <span class="badge badge-mandiri">Mandiri</span>
                </div>
                <div class="table-card">
                    <div class="table-responsive">
                        <table id="tableMandiri">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Semester</th>
                                    <th>Golongan UKT</th>
                                    <th>Wali</th>
                                    <th>Tarif UKT Asli</th>
                                    <th>Biaya Operasional</th>
                                    <th class="text-tagihan">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($list_mandiri)): ?>
                                    <tr>
                                        <td colspan="9" class="empty-state">Tidak ada data mahasiswa mandiri.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list_mandiri as $mhs): ?>
                                        <tr class="searchable-row">
                                            <td><?php echo $mhs->getIdMahasiswa(); ?></td>
                                            <td class="text-highlight"><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                                            <td style="font-weight: 600;"><?php echo htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                                            <td>Smstr <?php echo $mhs->getSemester(); ?></td>
                                            <td><span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; font-size: 12px;"><?php echo htmlspecialchars($mhs->getGolonganUkt()); ?></span></td>
                                            <td><?php echo htmlspecialchars($mhs->getNamaWali()); ?></td>
                                            <td>Rp <?php echo number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                                            <td class="text-success">Rp 100.000</td>
                                            <td class="text-tagihan">Rp <?php echo number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Kategori 2: Mahasiswa Bidikmisi -->
            <section class="section-container" id="bidikmisi-section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fa-solid fa-hand-holding-dollar text-success"></i>
                        <span>Daftar Registrasi Mahasiswa Jalur Bidikmisi</span>
                    </div>
                    <span class="badge badge-bidikmisi">Bidikmisi</span>
                </div>
                <div class="table-card">
                    <div class="table-responsive">
                        <table id="tableBidikmisi">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Semester</th>
                                    <th>Nomor KIP Kuliah</th>
                                    <th>Subsidi Dana Saku</th>
                                    <th>Tarif UKT Asli</th>
                                    <th class="text-tagihan">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($list_bidikmisi)): ?>
                                    <tr>
                                        <td colspan="8" class="empty-state">Tidak ada data mahasiswa Bidikmisi.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list_bidikmisi as $mhs): ?>
                                        <tr class="searchable-row">
                                            <td><?php echo $mhs->getIdMahasiswa(); ?></td>
                                            <td class="text-highlight"><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                                            <td style="font-weight: 600;"><?php echo htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                                            <td>Smstr <?php echo $mhs->getSemester(); ?></td>
                                            <td><span style="border: 1px solid rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.05); color: #10b981; padding: 4px 8px; border-radius: 6px; font-size: 12px;"><?php echo htmlspecialchars($mhs->getNomorKipKuliah()); ?></span></td>
                                            <td class="text-success">Rp <?php echo number_format($mhs->getDanaSakuSubsidi(), 0, ',', '.'); ?> / bln</td>
                                            <td style="text-decoration: line-through; color: var(--text-muted);">Rp <?php echo number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                                            <td class="text-success" style="font-weight: bold;">Rp 0 (Gratis)</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Kategori 3: Mahasiswa Prestasi -->
            <section class="section-container" id="prestasi-section">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fa-solid fa-award text-warning"></i>
                        <span>Daftar Registrasi Mahasiswa Jalur Prestasi</span>
                    </div>
                    <span class="badge badge-prestasi">Prestasi</span>
                </div>
                <div class="table-card">
                    <div class="table-responsive">
                        <table id="tablePrestasi">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Semester</th>
                                    <th>Instansi Pemberi Beasiswa</th>
                                    <th>Syarat IPK Min</th>
                                    <th>Tarif UKT Asli</th>
                                    <th>Beasiswa (Potongan)</th>
                                    <th class="text-tagihan">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($list_prestasi)): ?>
                                    <tr>
                                        <td colspan="9" class="empty-state">Tidak ada data mahasiswa prestasi.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list_prestasi as $mhs): ?>
                                        <tr class="searchable-row">
                                            <td><?php echo $mhs->getIdMahasiswa(); ?></td>
                                            <td class="text-highlight"><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                                            <td style="font-weight: 600;"><?php echo htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                                            <td>Smstr <?php echo $mhs->getSemester(); ?></td>
                                            <td><?php echo htmlspecialchars($mhs->getNamaInstansiBeasiswa()); ?></td>
                                            <td class="text-warning" style="font-weight: 600;"><i class="fa-solid fa-star" style="font-size: 11px; margin-right: 4px;"></i><?php echo number_format($mhs->getMinimalIpkSyarat(), 2); ?></td>
                                            <td>Rp <?php echo number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                                            <td class="text-success">75% (Subsidi)</td>
                                            <td class="text-tagihan">Rp <?php echo number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- Client-side Instant Filter Script -->
    <script>
        function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var rows = document.getElementsByClassName("searchable-row");
            
            for (var i = 0; i < rows.length; i++) {
                var nameCol = rows[i].getElementsByTagName("td")[2];
                var nimCol = rows[i].getElementsByTagName("td")[1];
                if (nameCol || nimCol) {
                    var nameText = nameCol.textContent || nameCol.innerText;
                    var nimText = nimCol.textContent || nimCol.innerText;
                    if (nameText.toLowerCase().indexOf(filter) > -1 || nimText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }

        // Active state in sidebar navigation based on scrolling
        window.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    const id = entry.target.getAttribute('id');
                    if (entry.intersectionRatio > 0.1) {
                        document.querySelectorAll('.sidebar-menu .menu-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        
                        let activeLink = document.querySelector(`.sidebar-menu .menu-item a[href="#${id}"]`);
                        if (activeLink) {
                            activeLink.parentElement.classList.add('active');
                        } else if (id === 'dashboard') {
                            document.querySelector('.sidebar-menu .menu-item:first-child').classList.add('active');
                        }
                    }
                });
            });

            // Track all sections
            document.querySelectorAll('section[id], div[id="dashboard"]').forEach((section) => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
