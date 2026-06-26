<?php
/**
 * Koneksi Database menggunakan PDO (PHP Data Objects)
 * Menghubungkan dashboard dengan database MySQL/MariaDB.
 */

$host    = 'localhost';
$db      = 'db_uas_pbo_TRPL1A_MuhmmadRizqiArdiansyah';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $db_connected = true;
    $db_error = null;
} catch (\PDOException $e) {
    $pdo = null;
    $db_connected = false;
    $db_error = $e->getMessage();
}
