<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');
?>

<h2>Dashboard Dosen</h2>
<hr>
<p>Selamat datang, <strong><?= htmlspecialchars($current_user['nama_dosen']) ?></strong>!</p>
<p>Silakan pilih menu untuk mengelola absensi pada mata kuliah yang Anda ampu.</p>

<ul style="list-style-type: none; padding-left: 0;">
    <li style="margin-bottom: 10px;"><a href="kelola_absensi.php" class="btn btn-success">Kelola Absensi</a></li>
</ul>

<?php require __DIR__ . '/../templates/footer.php'; ?>