<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');
?>

<h2>Dashboard Super Admin</h2>
<hr>
<p>Selamat datang, <strong><?= htmlspecialchars($current_user['display_name']) ?></strong>!</p>
<p>Anda memiliki hak akses penuh untuk mengelola seluruh data master sistem.</p>

<ul style="list-style-type: none; padding-left: 0;">
    <li style="margin-bottom: 10px;"><a href="kelola_mahasiswa.php" class="btn">Kelola Mahasiswa</a></li>
    <li style="margin-bottom: 10px;"><a href="kelola_dosen.php" class="btn btn-secondary">Kelola Dosen</a></li>
    <li style="margin-bottom: 10px;"><a href="kelola_mk.php" class="btn btn-success">Kelola Mata Kuliah</a></li>
    <li style="margin-bottom: 10px;"><a href="kelola_absensi.php" class="btn btn-warning">Kelola Absensi (Akses Penuh)</a></li>
</ul>

<?php require __DIR__ . '/../templates/footer.php'; ?>