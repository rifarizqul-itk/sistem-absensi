<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

if (isset($_GET['id'])) {
    $id_mk = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM mata_kuliah WHERE id_mk = ?");
        $stmt->execute([$id_mk]);
        header("Location: mk_read.php?status=deleted");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: mk_read.php");
    exit;
}
?>