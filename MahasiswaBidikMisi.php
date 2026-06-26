<?php
require_once 'Mahasiswa.php';

/**
 * Subclass: MahasiswaBidikMisi
 * Merepresentasikan mahasiswa dengan skema pembiayaan Bidikmisi / KIP Kuliah.
 */
class MahasiswaBidikMisi extends Mahasiswa {
    // Properti tambahan khusus kategori Bidikmisi
    private string $nomorKipKuliah;
    private float $danaSakuSubsidi;

    // Constructor
    public function __construct(
        int $id_mahasiswa, 
        string $nama_mahasiswa, 
        string $nim, 
        int $semester, 
        float $tarifUktNominal,
        string $nomorKipKuliah,
        float $danaSakuSubsidi
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->nomorKipKuliah = $nomorKipKuliah;
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    // Getter dan Setter
    public function getNomorKipKuliah(): string {
        return $this->nomorKipKuliah;
    }

    public function setNomorKipKuliah(string $nomorKipKuliah): void {
        $this->nomorKipKuliah = $nomorKipKuliah;
    }

    public function getDanaSakuSubsidi(): float {
        return $this->danaSakuSubsidi;
    }

    public function setDanaSakuSubsidi(float $danaSakuSubsidi): void {
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    /**
     * Implementasi hitungTagihanSemester
     * Mahasiswa Bidikmisi digratiskan penuh (tagihan = Rp 0) karena ditanggung negara melalui skema KIP-Kuliah.
     */
    public function hitungTagihanSemester(): float {
        return 0.0;
    }

    /**
     * Implementasi tampilkanSpesifikasiAkademik
     */
    public function tampilkanSpesifikasiAkademik(): void {
        echo "============================================\n";
        echo "SPESIFIKASI AKADEMIK - MAHASISWA BIDIKMISI\n";
        echo "============================================\n";
        echo "ID Mahasiswa      : " . $this->id_mahasiswa . "\n";
        echo "Nama Mahasiswa    : " . $this->nama_mahasiswa . "\n";
        echo "NIM               : " . $this->nim . "\n";
        echo "Semester          : " . $this->semester . "\n";
        echo "Nomor KIP Kuliah  : " . $this->nomorKipKuliah . "\n";
        echo "Dana Saku Subsidi : Rp " . number_format($this->danaSakuSubsidi, 2, ',', '.') . "\n";
        echo "Tarif UKT Nominal : Rp " . number_format($this->tarifUktNominal, 2, ',', '.') . "\n";
        echo "Tagihan Semester  : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (Subsidi Penuh)\n";
        echo "--------------------------------------------\n\n";
    }

    /**
     * Metode untuk melakukan query SELECT-WHERE spesifik tipe Bidikmisi
     * Mengambil data mahasiswa Bidikmisi yang memiliki dana saku di atas nominal tertentu.
     */
    public static function getByDanaSakuMinimal(PDO $db, float $minDanaSaku): array {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'Bidikmisi' AND dana_saku_subsidi >= :minDanaSaku";
        $stmt = $db->prepare($query);
        $stmt->execute(['minDanaSaku' => $minDanaSaku]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
