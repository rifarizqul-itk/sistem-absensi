<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$current_user = null;
$current_role = null;

if (isset($_SESSION['id_user'])) {
    $current_role = $_SESSION['role'];
    
    // Ambil data spesifik berdasarkan role
    if ($current_role == 'dosen') {
        $stmt = $pdo->prepare("SELECT d.* FROM users u JOIN dosen d ON u.id_dosen = d.id_dosen WHERE u.id_user = ?");
        $stmt->execute([$_SESSION['id_user']]);
        $current_user = $stmt->fetch();
        $current_user['display_name'] = $current_user['nama_dosen'];

    } elseif ($current_role == 'mahasiswa') {
        $stmt = $pdo->prepare("SELECT m.* FROM users u JOIN mahasiswa m ON u.id_mahasiswa = m.id_mahasiswa WHERE u.id_user = ?");
        $stmt->execute([$_SESSION['id_user']]);
        $current_user = $stmt->fetch();
        $current_user['display_name'] = $current_user['nama_lengkap'];

    } elseif ($current_role == 'superadmin') {
        $current_user['display_name'] = 'Admin';
    }
}

// Fungsi untuk mengecek otentikasi dan role
function check_auth($role = null) {
    global $current_role;
    if (!$current_role) {
        header("Location: ../auth/login.php?error=not_logged_in");
        exit;
    }
    if ($role && $current_role != $role) {
        // Jika Super Admin, dia bisa akses semua
        if ($current_role == 'superadmin') {
            return;
        }
        header("Location: ../auth/login.php?error=unauthorized");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav>
    <div>
        <?php if ($current_role == 'superadmin'): ?>
            <a href="../superadmin/index.php">Dashboard</a>
            <a href="../superadmin/mk_read.php">Kelola Mata Kuliah</a>
            <a href="../superadmin/kelola_absensi.php">Kelola Absensi</a>
        <?php elseif ($current_role == 'dosen'): ?>
            <a href="../dosen/index.php">Dashboard</a>
            <a href="../dosen/kelola_absensi.php">Kelola Absensi</a>
        <?php elseif ($current_role == 'mahasiswa'): ?>
            <a href="../mahasiswa/index.php">Dashboard</a>
            <a href="../mahasiswa/isi_absensi.php">Isi Absensi</a>
            <a href="../mahasiswa/riwayat.php">Riwayat Absensi</a>
        <?php else: ?>
            <a href="../auth/login.php">Login</a>
        <?php endif; ?>
    </div>
    <div>
        <?php if ($current_user): ?>
            <span class="user-info">Halo, <?= htmlspecialchars($current_user['display_name']) ?> (<?= $current_role ?>)</span>
            <a href="../auth/logout.php" class="logout">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">