<?php
require __DIR__ . '/../templates/header.php';
check_auth('mahasiswa');

// Ambil id_mahasiswa dan id_prodi dari user yang login
$id_mahasiswa = $current_user['id_mahasiswa'];
$id_prodi = $current_user['id_prodi'];
$today = date('Y-m-d'); // Tanggal hari ini

$error = '';
$success = '';

// Logika CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mk = $_POST['id_mk'];
    
    if (empty($id_mk)) {
        $error = 'Anda harus memilih mata kuliah.';
    } else {
        try {
            // Coba masukkan data absensi
            $stmt = $pdo->prepare("INSERT INTO absensi (id_mahasiswa, id_mk, tanggal_absensi, status) VALUES (?, ?, ?, 'Hadir')");
            $stmt->execute([$id_mahasiswa, $id_mk, $today]);
            $success = 'Absensi berhasil dicatat!';
        } catch (PDOException $e) {
            // Cek jika error karena duplikat (sudah absen)
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                $error = 'Anda sudah absen untuk mata kuliah ini hari ini.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Ambil daftar mata kuliah yang sesuai dengan prodi mahasiswa
$stmt = $pdo->prepare("SELECT * FROM mata_kuliah WHERE id_prodi = ? ORDER BY nama_mk");
$stmt->execute([$id_prodi]);
$mk_list = $stmt->fetchAll();

// Ambil data absensi hari ini untuk mahasiswa ini (untuk dicek)
$stmt = $pdo->prepare("
    SELECT mk.nama_mk 
    FROM absensi a 
    JOIN mata_kuliah mk ON a.id_mk = mk.id_mk
    WHERE a.id_mahasiswa = ? AND a.tanggal_absensi = ?
");
$stmt->execute([$id_mahasiswa, $today]);
$absen_hari_ini = $stmt->fetchAll();

?>

<h2>Isi Absensi Hari Ini (<?= htmlspecialchars(date('d F Y')) ?>)</h2>
<hr>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form action="isi_absensi.php" method="POST">
    <div class="form-group">
        <label for="id_mk">Pilih Mata Kuliah</label>
        <select id="id_mk" name="id_mk" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php foreach ($mk_list as $mk): ?>
                <option value="<?= $mk['id_mk'] ?>"><?= htmlspecialchars($mk['nama_mk']) ?> (<?= htmlspecialchars($mk['kode_mk']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Saya Hadir!</button>
</form>

<hr>
<h3>Absensi Anda Hari Ini</h3>
<?php if (empty($absen_hari_ini)): ?>
    <p>Anda belum melakukan absensi hari ini.</p>
<?php else: ?>
    <ul>
        <?php foreach ($absen_hari_ini as $absen): ?>
            <li>(Hadir) - <?= htmlspecialchars($absen['nama_mk']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<?php require __DIR__ . '/../templates/footer.php'; ?>