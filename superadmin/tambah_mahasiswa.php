<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin');
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama_lengkap = $_POST['nama_lengkap'];

    if (empty($nim) || empty($nama_lengkap)) {
        $error = 'Semua field wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama_lengkap) VALUES (?, ?)");
            $stmt->execute([$nim, $nama_lengkap]);
            header("Location: kelola_mahasiswa.php?status=success");
            exit;
        } catch (PDOException $e) {
            $error = 'NIM sudah terdaftar.';
        }
    }
}
?>

<h2>Tambah Mahasiswa Baru</h2>
<hr>
<a href="kelola_mahasiswa.php" class="btn btn-secondary">Kembali</a>

<?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top: 15px;"><?= $error ?></div>
<?php endif; ?>

<form action="tambah_mahasiswa.php" method="POST" style="margin-top: 15px;">
    <div class="form-group">
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim" required>
    </div>
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>