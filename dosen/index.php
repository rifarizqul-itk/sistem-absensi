<?php
// Panggil header
require __DIR__ . '/../templates/header.php';

// Cek autentikasi, harus role 'dosen'
check_auth('dosen');
?>

<h2>Dashboard Dosen</h2>
<hr>
<p>Selamat datang, <strong><?= htmlspecialchars($current_user['nama_dosen']) ?></strong>!</p>
<p>Dari sini Anda dapat mengelola data mahasiswa, data mata kuliah, dan data absensi.</p>

<ul style="list-style-type: none; padding-left: 0;">
    <li style="margin-bottom: 10px;"><a href="kelola_mahasiswa.php" class="btn">Kelola Biodata Mahasiswa</a></li>
    <li style="margin-bottom: 10px;"><a href="kelola_mk.php" class="btn btn-success">Kelola Mata Kuliah</a></li>
    <li style="margin-bottom: 10px;"><a href="kelola_absensi.php" class="btn btn-secondary">Kelola Absensi</a></li>
</ul>

<?php
// Panggil footer
require __DIR__ . '/../templates/footer.php';
?>