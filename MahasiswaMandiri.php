<?php
require_once 'Mahasiswa.php';

/**
 * Subclass: MahasiswaMandiri
 * Merepresentasikan mahasiswa dengan skema pembiayaan Mandiri.
 */
class MahasiswaMandiri extends Mahasiswa {
    // Properti tambahan khusus kategori Mandiri
    private string $golonganUkt;
    private string $namaWali;

    // Constructor
    public function __construct(
        int $id_mahasiswa, 
        string $nama_mahasiswa, 
        string $nim, 
        int $semester, 
        float $tarifUktNominal,
        string $golonganUkt,
        string $namaWali
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->golonganUkt = $golonganUkt;
        $this->namaWali = $namaWali;
    }

    // Getter dan Setter
    public function getGolonganUkt(): string {
        return $this->golonganUkt;
    }

    public function setGolonganUkt(string $golonganUkt): void {
        $this->golonganUkt = $golonganUkt;
    }

    public function getNamaWali(): string {
        return $this->namaWali;
    }

    public function setNamaWali(string $namaWali): void {
        $this->namaWali = $namaWali;
    }

    /**
     * Implementasi hitungTagihanSemester
     * Mahasiswa mandiri membayar penuh sesuai tarif UKT nominal ditambah biaya operasional Rp100.000.
     */
    public function hitungTagihanSemester(): float {
        return $this->tarifUktNominal + 100000.00;
    }

    /**
     * Implementasi tampilkanSpesifikasiAkademik
     */
    public function tampilkanSpesifikasiAkademik(): void {
        echo "============================================\n";
        echo "SPESIFIKASI AKADEMIK - MAHASISWA MANDIRI\n";
        echo "============================================\n";
        echo "ID Mahasiswa      : " . $this->id_mahasiswa . "\n";
        echo "Nama Mahasiswa    : " . $this->nama_mahasiswa . "\n";
        echo "NIM               : " . $this->nim . "\n";
        echo "Semester          : " . $this->semester . "\n";
        echo "Golongan UKT      : " . $this->golonganUkt . "\n";
        echo "Nama Wali         : " . $this->namaWali . "\n";
        echo "Tarif UKT Nominal : Rp " . number_format($this->tarifUktNominal, 2, ',', '.') . "\n";
        echo "Tagihan Semester  : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . "\n";
        echo "--------------------------------------------\n\n";
    }

    /**
     * Metode untuk melakukan query SELECT-WHERE spesifik tipe Mandiri
     * Mengambil data mahasiswa mandiri berdasarkan golongan UKT tertentu.
     */
    public static function getByGolonganUkt(PDO $db, string $golongan): array {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'Mandiri' AND golongan_ukt = :golongan";
        $stmt = $db->prepare($query);
        $stmt->execute(['golongan' => $golongan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
