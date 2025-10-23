<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

$stmt = $pdo->query("
    SELECT mk.id_mk, mk.kode_mk, mk.nama_mk, d.nama_dosen
    FROM mata_kuliah mk 
    LEFT JOIN dosen d ON mk.id_dosen_pengampu = d.id_dosen
    ORDER BY mk.kode_mk ASC
");
$mk_list = $stmt->fetchAll();
?>

<h2>Kelola Mata Kuliah</h2>
<hr>
<a href="tambah_mk.php" class="btn btn-success">Tambah Mata Kuliah Baru</a>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data mata kuliah berhasil disimpan!</div>';
}
if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    echo '<div class="alert alert-success" style="margin-top: 15px;">Data mata kuliah berhasil dihapus!</div>';
}
?>

<table>
    <thead>
        <tr>
            <th>Kode MK</th>
            <th>Nama Mata Kuliah</th>
            <th>Dosen Pengampu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mk_list as $mk): ?>
            <tr>
                <td><?= htmlspecialchars($mk['kode_mk']) ?></td>
                <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                <td><?= htmlspecialchars($mk['nama_dosen'] ?? 'N/A') ?></td>
                <td class="actions">
                    <a href="kelola_krs.php?id_mk=<?= $mk['id_mk'] ?>" class="btn btn-warning">Kelola Peserta</a>
                    <a href="edit_mk.php?id=<?= $mk['id_mk'] ?>" class="btn btn-secondary">Edit</a>
                    <a href="hapus_mk.php?id=<?= $mk['id_mk'] ?>" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>