<?php
/**
 * Class Database
 * Menangani koneksi database MySQL menggunakan PDO dengan paradigma OOP (Object-Oriented Programming).
 * File: koneksi.php
 */
class Database {
    // Properti terenkapsulasi (private) untuk kredensial database
    private string $host = 'localhost';
    private string $db = 'db_uas_pbo_TRPL1A_MuhmmadRizqiArdiansyah';
    private string $user = 'root';
    private string $pass = '';
    private string $charset = 'utf8mb4';
    
    private ?PDO $pdo = null;
    private bool $connected = false;
    private ?string $error = null;

    // Constructor untuk menginisialisasi koneksi secara otomatis saat objek dibuat
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->connected = true;
        } catch (PDOException $e) {
            $this->pdo = null;
            $this->connected = false;
            $this->error = $e->getMessage();
        }
    }

    // Getter untuk mengambil objek koneksi PDO
    public function getConnection(): ?PDO {
        return $this->pdo;
    }

    // Getter untuk mengecek status koneksi
    public function isConnected(): bool {
        return $this->connected;
    }

    // Getter untuk mengambil pesan kesalahan jika koneksi gagal
    public function getError(): ?string {
        return $this->error;
    }
}

// Instansiasi objek Database untuk digunakan oleh file dashboard (index.php)
$database = new Database();
$pdo = $database->getConnection();
$db_connected = $database->isConnected();
$db_error = $database->getError();
