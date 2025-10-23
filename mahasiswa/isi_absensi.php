<?php
require __DIR__ . '/../templates/header.php';
check_auth('mahasiswa');

$id_mahasiswa = $current_user['id_mahasiswa'];
$today = date('Y-m-d');

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mk = $_POST['id_mk'];
    
    if (empty($id_mk)) {
        $error = 'Anda harus memilih mata kuliah.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO absensi (id_mahasiswa, id_mk, tanggal_absensi, status) VALUES (?, ?, ?, 'Hadir')");
            $stmt->execute([$id_mahasiswa, $id_mk, $today]);
            $success = 'Absensi berhasil dicatat!';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                $error = 'Anda sudah absen untuk mata kuliah ini hari ini.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// INI PERBEDAANNYA: Ambil MK yang diikuti mahasiswa dari tabel peserta_mk
$stmt_mk_list = $pdo->prepare("
    SELECT mk.id_mk, mk.nama_mk, mk.kode_mk 
    FROM mata_kuliah mk
    JOIN peserta_mk p ON mk.id_mk = p.id_mk
    WHERE p.id_mahasiswa = ?
    ORDER BY mk.nama_mk
");
$stmt_mk_list->execute([$id_mahasiswa]);
$mk_list = $stmt_mk_list->fetchAll();

// Ambil data absensi hari ini (untuk ditampilkan)
$stmt_absen = $pdo->prepare("
    SELECT mk.nama_mk 
    FROM absensi a 
    JOIN mata_kuliah mk ON a.id_mk = mk.id_mk
    WHERE a.id_mahasiswa = ? AND a.tanggal_absensi = ? AND a.status = 'Hadir'
");
$stmt_absen->execute([$id_mahasiswa, $today]);
$absen_hari_ini = $stmt_absen->fetchAll();
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
<h3>Absensi Anda Hari Ini (Status: Hadir)</h3>
<?php if (empty($absen_hari_ini)): ?>
    <p>Anda belum melakukan absensi mandiri hari ini.</p>
<?php else: ?>
    <ul>
        <?php foreach ($absen_hari_ini as $absen): ?>
            <li><?= htmlspecialchars($absen['nama_mk']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>