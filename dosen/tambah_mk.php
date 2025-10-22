<?php
require __DIR__ . '/../templates/header.php';
check_auth('dosen');

// Ambil daftar prodi
$prodi_list = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi")->fetchAll();
// Ambil daftar dosen
$dosen_list = $pdo->query("SELECT * FROM dosen ORDER BY nama_dosen")->fetchAll();

$error = '';

// Logika CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $id_prodi = $_POST['id_prodi'];
    $id_dosen_pengampu = $_POST['id_dosen_pengampu'];

    if (empty($kode_mk) || empty($nama_mk) || empty($id_prodi) || empty($id_dosen_pengampu)) {
        $error = 'Semua field wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO mata_kuliah (kode_mk, nama_mk, id_prodi, id_dosen_pengampu) VALUES (?, ?, ?, ?)");
            $stmt->execute([$kode_mk, $nama_mk, $id_prodi, $id_dosen_pengampu]);
            
            header("Location: kelola_mk.php?status=success");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                $error = 'Kode MK sudah terdaftar.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<h2>Tambah Mata Kuliah Baru</h2>
<hr>
<a href="kelola_mk.php" class="btn btn-secondary">Kembali</a>

<?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top: 15px;"><?= $error ?></div>
<?php endif; ?>

<form action="tambah_mk.php" method="POST" style="margin-top: 15px;">
    <div class="form-group">
        <label for="kode_mk">Kode MK</label>
        <input type="text" id="kode_mk" name="kode_mk" required value="<?= htmlspecialchars($_POST['kode_mk'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="nama_mk">Nama Mata Kuliah</label>
        <input type="text" id="nama_mk" name="nama_mk" required value="<?= htmlspecialchars($_POST['nama_mk'] ?? '') ?>">
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
    <div class="form-group">
        <label for="id_dosen_pengampu">Dosen Pengampu</label>
        <select id="id_dosen_pengampu" name="id_dosen_pengampu" required>
            <option value="">-- Pilih Dosen --</option>
            <?php foreach ($dosen_list as $dosen): ?>
                <option value="<?= $dosen['id_dosen'] ?>"><?= htmlspecialchars($dosen['nama_dosen']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>