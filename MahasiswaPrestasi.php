<?php
require_once 'Mahasiswa.php';

/**
 * Subclass: MahasiswaPrestasi
 * Merepresentasikan mahasiswa dengan skema pembiayaan Beasiswa Prestasi.
 */
class MahasiswaPrestasi extends Mahasiswa {
    // Properti tambahan khusus kategori Prestasi
    private string $namaInstansiBeasiswa;
    private float $minimalIpkSyarat;

    // Constructor
    public function __construct(
        int $id_mahasiswa, 
        string $nama_mahasiswa, 
        string $nim, 
        int $semester, 
        float $tarifUktNominal,
        string $namaInstansiBeasiswa,
        float $minimalIpkSyarat
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        $this->minimalIpkSyarat = $minimalIpkSyarat;
    }

    // Getter dan Setter
    public function getNamaInstansiBeasiswa(): string {
        return $this->namaInstansiBeasiswa;
    }

    public function setNamaInstansiBeasiswa(string $namaInstansiBeasiswa): void {
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
    }

    public function getMinimalIpkSyarat(): float {
        return $this->minimalIpkSyarat;
    }

    public function setMinimalIpkSyarat(float $minimalIpkSyarat): void {
        $this->minimalIpkSyarat = $minimalIpkSyarat;
    }

    /**
     * Implementasi hitungTagihanSemester
     * Mahasiswa beasiswa prestasi membayar tarif UKT yang tercatat (biasanya disesuaikan/dikurangi oleh beasiswa).
     */
    public function hitungTagihanSemester(): float {
        return $this->tarifUktNominal;
    }

    /**
     * Implementasi tampilkanSpesifikasiAkademik
     */
    public function tampilkanSpesifikasiAkademik(): void {
        echo "============================================\n";
        echo "SPESIFIKASI AKADEMIK - MAHASISWA PRESTASI\n";
        echo "============================================\n";
        echo "ID Mahasiswa            : " . $this->id_mahasiswa . "\n";
        echo "Nama Mahasiswa          : " . $this->nama_mahasiswa . "\n";
        echo "NIM                     : " . $this->nim . "\n";
        echo "Semester                : " . $this->semester . "\n";
        echo "Instansi Beasiswa       : " . $this->namaInstansiBeasiswa . "\n";
        echo "Syarat Minimal IPK      : " . number_format($this->minimalIpkSyarat, 2) . "\n";
        echo "Tarif UKT Nominal       : Rp " . number_format($this->tarifUktNominal, 2, ',', '.') . "\n";
        echo "Tagihan Semester        : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . "\n";
        echo "--------------------------------------------\n\n";
    }

    /**
     * Metode untuk melakukan query SELECT-WHERE spesifik tipe Prestasi
     * Mengambil data mahasiswa prestasi berdasarkan instansi pemberi beasiswa tertentu.
     */
    public static function getByInstansiBeasiswa(PDO $db, string $instansi): array {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'Prestasi' AND nama_instansi_beasiswa = :instansi";
        $stmt = $db->prepare($query);
        $stmt->execute(['instansi' => $instansi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
