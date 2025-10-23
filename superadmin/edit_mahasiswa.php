<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');
$error = '';

if (!isset($_GET['id'])) {
    header("Location: kelola_mahasiswa.php");
    exit;
}
$id_mahasiswa = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama_lengkap = $_POST['nama_lengkap'];

    if (empty($nim) || empty($nama_lengkap)) {
        $error = 'Semua field wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = ?, nama_lengkap = ? WHERE id_mahasiswa = ?");
            $stmt->execute([$nim, $nama_lengkap, $id_mahasiswa]);
            header("Location: kelola_mahasiswa.php?status=success");
            exit;
        } catch (PDOException $e) {
            $error = 'NIM sudah terdaftar.';
        }
    }
    // Jika error, ambil data dari POST untuk ditampilkan lagi
    $mhs = $_POST;
} else {
    // Ambil data dari DB
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa = ?");
    $stmt->execute([$id_mahasiswa]);
    $mhs = $stmt->fetch();
    if (!$mhs) {
        header("Location: kelola_mahasiswa.php");
        exit;
    }
}
?>

<h2>Edit Mahasiswa: <?= htmlspecialchars($mhs['nama_lengkap']) ?></h2>
<hr>
<a href="kelola_mahasiswa.php" class="btn btn-secondary">Kembali</a>

<?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top: 15px;"><?= $error ?></div>
<?php endif; ?>

<form action="edit_mahasiswa.php?id=<?= $id_mahasiswa ?>" method="POST" style="margin-top: 15px;">
    <div class="form-group">
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim" required value="<?= htmlspecialchars($mhs['nim']) ?>">
    </div>
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?= htmlspecialchars($mhs['nama_lengkap']) ?>">
    </div>
    <button type="submit" class="btn btn-success">Update Data</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>