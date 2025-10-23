<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

$stmt = $pdo->query("SELECT * FROM dosen ORDER BY nip ASC");
$dosen_list = $stmt->fetchAll();
?>

<h2>Kelola Biodata Dosen</h2>
<p>Perhatian: Menambah/mengedit biodata di sini TIDAK otomatis membuat akun login. Anda harus menambah akun di tabel `users` secara manual via SQL.</p>
<hr>
<a href="tambah_dosen.php" class="btn btn-success">Tambah Dosen Baru</a>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data dosen berhasil disimpan!</div>';
}
if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data dosen berhasil dihapus!</div>';
}
?>

<table>
    <thead>
        <tr>
            <th>NIP</th>
            <th>Nama Lengkap</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dosen_list as $mhs): ?>
            <tr>
                <td><?= htmlspecialchars($mhs['nip']) ?></td>
                <td><?= htmlspecialchars($mhs['nama_dosen']) ?></td>
                <td class="actions">
                    <a href="edit_dosen.php?id=<?= $mhs['id_dosen'] ?>" class="btn btn-secondary">Edit</a>
                    <a href="hapus_dosen.php?id=<?= $mhs['id_dosen'] ?>" class="btn btn-danger" onclick="return confirm('Yakin? Menghapus biodata ini akan menghapus akun login terkait.')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>