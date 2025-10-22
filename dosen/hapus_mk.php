<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

if (!isset($_GET['id'])) {
    header("Location: kelola_mk.php");
    exit;
}

$id_mk = $_GET['id'];

try {
    // Menghapus mata kuliah juga akan menghapus data absensi terkait (ON DELETE CASCADE)
    $stmt = $pdo->prepare("DELETE FROM mata_kuliah WHERE id_mk = ?");
    $stmt->execute([$id_mk]);
    
    header("Location: kelola_mk.php?status=deleted");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo '<br><a href="kelola_mk.php">Kembali</a>';
}
?>