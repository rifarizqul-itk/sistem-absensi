<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

// Ambil semua data mahasiswa beserta nama prodinya
$stmt = $pdo->query("
    SELECT m.id_mahasiswa, m.nim, m.nama_lengkap, p.nama_prodi 
    FROM mahasiswa m 
    LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
    ORDER BY m.nim ASC
");
$mahasiswa_list = $stmt->fetchAll();
?>

<h2>Kelola Biodata Mahasiswa</h2>
<hr>
<a href="tambah_mahasiswa.php" class="btn btn-success">Tambah Mahasiswa Baru</a>

<?php
// Tampilkan pesan sukses jika ada
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
            <th>Program Studi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mahasiswa_list)): ?>
            <tr>
                <td colspan="4">Belum ada data mahasiswa.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($mahasiswa_list as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama_prodi'] ?? 'N/A') ?></td>
                    <td class="actions">
                        <a href="edit_mahasiswa.php?id=<?= $mhs['id_mahasiswa'] ?>" class="btn btn-secondary">Edit</a>
                        <a href="hapus_mahasiswa.php?id=<?= $mhs['id_mahasiswa'] ?>" class="btn btn-danger" onclick="return confirm('PERINGATAN: Menghapus biodata ini akan MENGHAPUS AKUN LOGIN dan SEMUA RIWAYAT ABSENSI mahasiswa terkait. Yakin?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>