<?php
// Panggil header
require __DIR__ . '/../templates/header.php';

// Cek autentikasi, harus role 'mahasiswa'
check_auth('mahasiswa');
?>

<h2>Dashboard Mahasiswa</h2>
<hr>
<p>Selamat datang, <strong><?= htmlspecialchars($current_user['nama_lengkap']) ?></strong>!</p>
<p>NIM: <?= htmlspecialchars($current_user['nim']) ?></p>
<p>Program Studi: <?= htmlspecialchars($current_user['nama_prodi'] ?? 'N/A') ?></p>


<p>Silakan pilih menu untuk mengisi absensi atau melihat riwayat absensi Anda.</p>

<ul style="list-style-type: none; padding-left: 0;">
    <li style="margin-bottom: 10px;"><a href="isi_absensi.php" class="btn btn-success">Isi Absensi Hari Ini</a></li>
    <li style="margin-bottom: 10px;"><a href="riwayat.php" class="btn btn-secondary">Lihat Riwayat Absensi</a></li>
</ul>

<?php
// Panggil footer
require __DIR__ . '/../templates/footer.php';
?>