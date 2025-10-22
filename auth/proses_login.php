<?php
session_start();
require __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Cari user berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 2. Verifikasi user dan password (MENGGUNAKAN MD5)
    if ($user && $user['password_hash'] == md5($password)) {
        // Password benar! Simpan data ke session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        
        // 3. Arahkan berdasarkan role
        if ($user['role'] == 'dosen') {
            header("Location: /sistem-absensi/dosen/index.php");
        } else {
            header("Location: /sistem-absensi/mahasiswa/index.php");
        }
        exit;
    } else {
        // Username atau password salah
        header("Location: login.php?error=invalid");
        exit;
    }
} else {
    // Jika diakses langsung, tendang
    header("Location: login.php");
    exit;
}
?>