<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

// Ambil daftar prodi untuk dropdown
$prodi_list = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi")->fetchAll();

$error = '';

// Logika CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $id_prodi = $_POST['id_prodi'];

    // Validasi sederhana
    if (empty($nim) || empty($nama_lengkap) || empty($id_prodi)) {
        $error = 'Semua field wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama_lengkap, id_prodi) VALUES (?, ?, ?)");
            $stmt->execute([$nim, $nama_lengkap, $id_prodi]);
            
            // Redirect ke halaman kelola dengan status sukses
            header("Location: kelola_mahasiswa.php?status=success");
            exit;
        } catch (PDOException $e) {
            // Cek jika error karena NIM duplikat (Error code 1062)
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                $error = 'NIM sudah terdaftar. Silakan gunakan NIM lain.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
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
        <input type="text" id="nim" name="nim" required value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="id_prodi">Program Studi</label>
        <select id="id_prodi" name="id_prodi" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach ($prodi_list as $prodi): ?>
                <option value="<?= $prodi['id_prodi'] ?>"><?= htmlspecialchars($prodi['nama_prodi']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>