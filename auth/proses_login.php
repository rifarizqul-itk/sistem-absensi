<?php
session_start();
require __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    // 1. Cari user berdasarkan login_id (NIM/NIP/Username)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = ?");
    $stmt->execute([$login_id]);
    $user = $stmt->fetch();

    // 2. Verifikasi user dan password (MENGGUNAKAN MD5)
    if ($user && $user['password_hash'] == md5($password)) {
        // Password benar! Simpan data ke session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        
        // 3. Arahkan berdasarkan role
        if ($user['role'] == 'superadmin') {
            header("Location: ../superadmin/index.php");
        } elseif ($user['role'] == 'dosen') {
            header("Location: ../dosen/index.php");
        } else {
            header("Location: ../mahasiswa/index.php");
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