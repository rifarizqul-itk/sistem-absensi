<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY nim ASC");
$mahasiswa_list = $stmt->fetchAll();
?>

<h2>Kelola Biodata Mahasiswa</h2>
<p>Perhatian: Menambah/mengedit biodata di sini TIDAK otomatis membuat akun login. Anda harus menambah akun di tabel `users` secara manual via SQL.</p>
<hr>
<a href="tambah_mahasiswa.php" class="btn btn-success">Tambah Mahasiswa Baru</a>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data mahasiswa berhasil disimpan!</div>';
}
if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data mahasiswa berhasil dihapus!</div>';
}
?>

<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mahasiswa_list as $mhs): ?>
            <tr>
                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                <td><?= htmlspecialchars($mhs['nama_lengkap']) ?></td>
                <td class="actions">
                    <a href="edit_mahasiswa.php?id=<?= $mhs['id_mahasiswa'] ?>" class="btn btn-secondary">Edit</a>
                    <a href="hapus_mahasiswa.php?id=<?= $mhs['id_mahasiswa'] ?>" class="btn btn-danger" onclick="return confirm('Yakin? Menghapus biodata ini akan menghapus akun login dan riwayat absensi terkait.')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>