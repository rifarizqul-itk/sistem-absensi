<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id_mahasiswa = ?");
        $stmt->execute([$id_mahasiswa]);
        header("Location: kelola_mahasiswa.php?status=deleted");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: kelola_mahasiswa.php");
    exit;
}
?>