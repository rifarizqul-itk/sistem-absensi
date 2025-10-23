<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');

if (!isset($_GET['id_mk'])) {
    header("Location: kelola_mk.php");
    exit;
}
$id_mk = $_GET['id_mk'];

// Ambil info MK
$stmt_mk = $pdo->prepare("SELECT * FROM mata_kuliah WHERE id_mk = ?");
$stmt_mk->execute([$id_mk]);
$mk = $stmt_mk->fetch();

if (!$mk) {
    header("Location: kelola_mk.php");
    exit;
}

$error = '';
$success = '';

// Logika Tambah Peserta
if (isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    if (!empty($id_mahasiswa)) {
        try {
            $stmt_add = $pdo->prepare("INSERT INTO peserta_mk (id_mahasiswa, id_mk) VALUES (?, ?)");
            $stmt_add->execute([$id_mahasiswa, $id_mk]);
            $success = "Mahasiswa berhasil ditambahkan ke mata kuliah.";
        } catch (PDOException $e) {
            $error = "Mahasiswa sudah terdaftar di mata kuliah ini.";
        }
    }
}

// Logika Hapus Peserta
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id_mhs'])) {
    $id_mahasiswa = $_GET['id_mhs'];
    $stmt_del = $pdo->prepare("DELETE FROM peserta_mk WHERE id_mahasiswa = ? AND id_mk = ?");
    $stmt_del->execute([$id_mahasiswa, $id_mk]);
    $success = "Mahasiswa berhasil dihapus dari mata kuliah.";
    // Redirect untuk membersihkan URL
    header("Location: kelola_krs.php?id_mk=$id_mk&status=deleted");
    exit;
}

if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $success = "Mahasiswa berhasil dihapus dari mata kuliah.";
}


// Ambil 2 daftar mahasiswa:
// 1. Yang sudah terdaftar
$stmt_terdaftar = $pdo->prepare("
    SELECT m.id_mahasiswa, m.nim, m.nama_lengkap 
    FROM mahasiswa m
    JOIN peserta_mk p ON m.id_mahasiswa = p.id_mahasiswa
    WHERE p.id_mk = ?
    ORDER BY m.nama_lengkap
");
$stmt_terdaftar->execute([$id_mk]);
$mahasiswa_terdaftar = $stmt_terdaftar->fetchAll();

// 2. Yang belum terdaftar
$stmt_belum = $pdo->prepare("
    SELECT m.id_mahasiswa, m.nim, m.nama_lengkap
    FROM mahasiswa m
    WHERE m.id_mahasiswa NOT IN (
        SELECT id_mahasiswa FROM peserta_mk WHERE id_mk = ?
    )
    ORDER BY m.nama_lengkap
");
$stmt_belum->execute([$id_mk]);
$mahasiswa_belum_terdaftar = $stmt_belum->fetchAll();

?>

<h2>Kelola Peserta Mata Kuliah</h2>
<h3><?= htmlspecialchars($mk['nama_mk']) ?> (<?= htmlspecialchars($mk['kode_mk']) ?>)</h3>
<hr>
<a href="kelola_mk.php" class="btn btn-secondary">Kembali ke Daftar MK</a>

<?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top: 15px;"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success" style="margin-top: 15px;"><?= $success ?></div>
<?php endif; ?>

<h3 style="margin-top: 20px;">Tambahkan Mahasiswa</h3>
<form action="kelola_krs.php?id_mk=<?= $id_mk ?>" method="POST" style="display: flex; gap: 10px;">
    <input type="hidden" name="action" value="tambah">
    <div class="form-group" style="flex-grow: 1; margin-bottom: 0;">
        <select name="id_mahasiswa" required>
            <option value="">-- Pilih Mahasiswa yang Belum Terdaftar --</option>
            <?php foreach ($mahasiswa_belum_terdaftar as $mhs): ?>
                <option value="<?= $mhs['id_mahasiswa'] ?>">
                    <?= htmlspecialchars($mhs['nama_lengkap']) ?> (<?= htmlspecialchars($mhs['nim']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Tambahkan</button>
</form>

<h3 style="margin-top: 20px;">Mahasiswa Terdaftar (<?= count($mahasiswa_terdaftar) ?>)</h3>
<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mahasiswa_terdaftar)): ?>
            <tr>
                <td colspan="3">Belum ada mahasiswa terdaftar.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($mahasiswa_terdaftar as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama_lengkap']) ?></td>
                    <td class="actions">
                        <a href="kelola_krs.php?id_mk=<?= $id_mk ?>&action=hapus&id_mhs=<?= $mhs['id_mahasiswa'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus mahasiswa ini dari mata kuliah?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>