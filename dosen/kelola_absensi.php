<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

// Logika UPDATE status jika ada POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_absensi'])) {
    $id_absensi = $_POST['id_absensi'];
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'];

    try {
        $stmt = $pdo->prepare("UPDATE absensi SET status = ?, keterangan = ? WHERE id_absensi = ?");
        $stmt->execute([$status, $keterangan, $id_absensi]);
        echo '<div class="alert alert-success">Status absensi berhasil diupdate!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Gagal update: ' . $e->getMessage() . '</div>';
    }
}

// Logika DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM absensi WHERE id_absensi = ?");
        $stmt->execute([$_GET['id']]);
        echo '<div class="alert alert-success">Data absensi berhasil dihapus!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Gagal hapus: ' . $e->getMessage() . '</div>';
    }
}

// Ambil filter (jika ada)
$filter_tanggal = $_GET['tanggal'] ?? date('Y-m-d'); // Default hari ini
$filter_mk = $_GET['id_mk'] ?? '';

// Ambil daftar MK untuk filter
$mk_list = $pdo->query("SELECT * FROM mata_kuliah ORDER BY nama_mk")->fetchAll();

// Bangun query dasar
$sql = "SELECT a.id_absensi, a.tanggal_absensi, a.status, a.keterangan, m.nama_lengkap, mk.nama_mk
        FROM absensi a
        JOIN mahasiswa m ON a.id_mahasiswa = m.id_mahasiswa
        JOIN mata_kuliah mk ON a.id_mk = mk.id_mk
        WHERE a.tanggal_absensi = ?";
$params = [$filter_tanggal];

if (!empty($filter_mk)) {
    $sql .= " AND a.id_mk = ?";
    $params[] = $filter_mk;
}
$sql .= " ORDER BY mk.nama_mk, m.nama_lengkap";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$absensi_list = $stmt->fetchAll();
?>

<h2>Kelola Absensi Mahasiswa</h2>
<hr>

<form action="kelola_absensi.php" method="GET" style="display: flex; gap: 10px; margin-bottom: 20px; align-items: flex-end;">
    <div class="form-group" style="margin-bottom: 0;">
        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>" class="form-control">
    </div>
    <div class="form-group" style="margin-bottom: 0;">
        <label for="id_mk">Mata Kuliah</label>
        <select name="id_mk" id="id_mk" class="form-control">
            <option value="">-- Semua Mata Kuliah --</option>
            <?php foreach ($mk_list as $mk): ?>
                <option value="<?= $mk['id_mk'] ?>" <?= $filter_mk == $mk['id_mk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mk['nama_mk']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn">Filter</button>
</form>

<table>
    <thead>
        <tr>
            <th>Mahasiswa</th>
            <th>Mata Kuliah</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($absensi_list)): ?>
            <tr>
                <td colspan="5">Tidak ada data absensi untuk filter ini.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($absensi_list as $absen): ?>
            <tr>
                <form action="kelola_absensi.php?tanggal=<?= $filter_tanggal ?>&id_mk=<?= $filter_mk ?>" method="POST">
                    <input type="hidden" name="id_absensi" value="<?= $absen['id_absensi'] ?>">
                    
                    <td><?= htmlspecialchars($absen['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($absen['nama_mk']) ?></td>
                    <td>
                        <select name="status" style="padding: 5px;">
                            <option value="Hadir" <?= $absen['status'] == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                            <option value="Izin" <?= $absen['status'] == 'Izin' ? 'selected' : '' ?>>Izin</option>
                            <option value="Sakit" <?= $absen['status'] == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                            <option value="Alpa" <?= $absen['status'] == 'Alpa' ? 'selected' : '' ?>>Alpa</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="keterangan" value="<?= htmlspecialchars($absen['keterangan'] ?? '') ?>" style="width: 100%;">
                    </td>
                    <td class="actions">
                        <button type="submit" class="btn btn-success" style="padding: 5px 8px;">Update</button>
                        <a href="kelola_absensi.php?action=delete&id=<?= $absen['id_absensi'] ?>&tanggal=<?= $filter_tanggal ?>&id_mk=<?= $filter_mk ?>" 
                           class="btn btn-danger" style="padding: 5px 8px;" onclick="return confirm('Yakin ingin menghapus data absensi ini?')">Hapus</a>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>