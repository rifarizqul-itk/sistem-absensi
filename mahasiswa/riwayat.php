<?php
require __DIR__ . '/../templates/header.php';
check_auth('mahasiswa');

$id_mahasiswa = $current_user['id_mahasiswa'];

// Ambil semua riwayat absensi mahasiswa ini
$stmt = $pdo->prepare("
    SELECT a.tanggal_absensi, mk.nama_mk, a.status, a.keterangan
    FROM absensi a
    JOIN mata_kuliah mk ON a.id_mk = mk.id_mk
    WHERE a.id_mahasiswa = ?
    ORDER BY a.tanggal_absensi DESC, mk.nama_mk ASC
");
$stmt->execute([$id_mahasiswa]);
$riwayat = $stmt->fetchAll();
?>

<h2>Riwayat Absensi Saya</h2>
<hr>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Mata Kuliah</th>
            <th>Status Kehadiran</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($riwayat)): ?>
            <tr>
                <td colspan="4">Belum ada riwayat absensi.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($riwayat as $item): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($item['tanggal_absensi']))) ?></td>
                    <td><?= htmlspecialchars($item['nama_mk']) ?></td>
                    <td><?= htmlspecialchars($item['status']) ?></td>
                    <td><?= htmlspecialchars($item['keterangan'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>