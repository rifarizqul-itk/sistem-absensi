<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: kelola_mahasiswa.php");
    exit;
}
$id_mahasiswa = $_GET['id'];

// Ambil daftar prodi untuk dropdown
$prodi_list = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi")->fetchAll();

$error = '';
$mhs = null;

// Logika UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $id_prodi = $_POST['id_prodi'];

    if (empty($nim) || empty($nama_lengkap) || empty($id_prodi)) {
        $error = 'Semua field wajib diisi.';
        // Muat ulang data mhs untuk ditampilkan di form
        $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa = ?");
        $stmt->execute([$id_mahasiswa]);
        $mhs = $stmt->fetch();
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = ?, nama_lengkap = ?, id_prodi = ? WHERE id_mahasiswa = ?");
            $stmt->execute([$nim, $nama_lengkap, $id_prodi, $id_mahasiswa]);
            
            header("Location: kelola_mahasiswa.php?status=success");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                $error = 'NIM sudah terdaftar. Silakan gunakan NIM lain.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
            // Muat ulang data mhs untuk ditampilkan di form
            $mhs = $_POST; // Ambil dari post agar perubahan yang gagal tetap terlihat
            $mhs['id_mahasiswa'] = $id_mahasiswa; // tambahkan id lagi
        }
    }
} else {
    // Logika GET (Ambil data untuk ditampilkan di form)
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa = ?");
    $stmt->execute([$id_mahasiswa]);
    $mhs = $stmt->fetch();

    if (!$mhs) {
        header("Location: kelola_mahasiswa.php");
        exit;
    }
}
?>

<h2>Edit Mahasiswa: <?= htmlspecialchars($mhs['nama_lengkap'] ?? '') ?></h2>
<hr>
<a href="kelola_mahasiswa.php" class="btn btn-secondary">Kembali</a>

<?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top: 15px;"><?= $error ?></div>
<?php endif; ?>

<form action="edit_mahasiswa.php?id=<?= $id_mahasiswa ?>" method="POST" style="margin-top: 15px;">
    <div class="form-group">
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim" required value="<?= htmlspecialchars($mhs['nim'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?= htmlspecialchars($mhs['nama_lengkap'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="id_prodi">Program Studi</label>
        <select id="id_prodi" name="id_prodi" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach ($prodi_list as $prodi): ?>
                <option value="<?= $prodi['id_prodi'] ?>" 
                    <?php if (isset($mhs['id_prodi']) && $mhs['id_prodi'] == $prodi['id_prodi']) echo 'selected'; ?>
                >
                    <?= htmlspecialchars($prodi['nama_prodi']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Update Data</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>