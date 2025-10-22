<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

if (!isset($_GET['id'])) {
    header("Location: kelola_mahasiswa.php");
    exit;
}

$id_mahasiswa = $_GET['id'];

try {
    // Karena kita set ON DELETE CASCADE di database.sql,
    // saat mahasiswa dihapus, data di tabel 'users' dan 'absensi'
    // yang terkait akan otomatis ikut terhapus.
    $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id_mahasiswa = ?");
    $stmt->execute([$id_mahasiswa]);
    
    header("Location: kelola_mahasiswa.php?status=deleted");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo '<br><a href="kelola_mahasiswa.php">Kembali</a>';
}
?>