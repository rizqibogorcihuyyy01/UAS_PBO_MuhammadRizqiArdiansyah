<?php
/**
 * Abstract Class: Mahasiswa
 * Merepresentasikan abstraksi data mahasiswa untuk implementasi PBO menggunakan PHP.
 * 
 * Sesuai dengan spesifikasi:
 * 1. Kelas bersifat abstract.
 * 2. Atribut terenkapsulasi dengan akses modifier protected.
 * 3. Memiliki dua metode abstrak: hitungTagihanSemester() dan tampilkanSpesifikasiAkademik().
 */
abstract class Mahasiswa {
    // 2. Properti/Atribut Terenkapsulasi (Protected) dengan Type Hinting
    protected int $id_mahasiswa;
    protected string $nama_mahasiswa;
    protected string $nim;
    protected int $semester;
    protected float $tarifUktNominal;

    // Constructor untuk inisialisasi properti
    public function __construct(
        int $id_mahasiswa, 
        string $nama_mahasiswa, 
        string $nim, 
        int $semester, 
        float $tarifUktNominal
    ) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarifUktNominal = $tarifUktNominal;
    }

    // Getter dan Setter untuk mendukung prinsip enkapsulasi
    public function getIdMahasiswa(): int {
        return $this->id_mahasiswa;
    }

    public function setIdMahasiswa(int $id_mahasiswa): void {
        $this->id_mahasiswa = $id_mahasiswa;
    }

    public function getNamaMahasiswa(): string {
        return $this->nama_mahasiswa;
    }

    public function setNamaMahasiswa(string $nama_mahasiswa): void {
        $this->nama_mahasiswa = $nama_mahasiswa;
    }

    public function getNim(): string {
        return $this->nim;
    }

    public function setNim(string $nim): void {
        $this->nim = $nim;
    }

    public function getSemester(): int {
        return $this->semester;
    }

    public function setSemester(int $semester): void {
        $this->semester = $semester;
    }

    public function getTarifUktNominal(): float {
        return $this->tarifUktNominal;
    }

    public function setTarifUktNominal(float $tarifUktNominal): void {
        $this->tarifUktNominal = $tarifUktNominal;
    }

    // 3. Metode Abstrak (tanpa body/isi)
    abstract public function hitungTagihanSemester(): float;
    abstract public function tampilkanSpesifikasiAkademik(): void;
}
