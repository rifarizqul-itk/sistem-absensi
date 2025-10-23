<?php
require __DIR__ . '/../templates/header.php';
check_auth('superadmin'); // Cek Super Admin

$error = '';
$success = '';

// Logika "UPSERT" (Update atau Insert)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mahasiswa'])) {
    $tanggal = $_POST['tanggal'];
    $id_mk = $_POST['id_mk'];
    $mahasiswa_data = $_POST['mahasiswa'];

    $pdo->beginTransaction();
    try {
        foreach ($mahasiswa_data as $id_mahasiswa => $data) {
            $status = $data['status'];
            $keterangan = $data['keterangan'];
            $id_absensi = $data['id_absensi']; // Bisa kosong jika data baru

            if (empty($status)) continue; // Lewati jika status tidak diisi

            if (empty($id_absensi)) {
                // INSERT baru
                $stmt_insert = $pdo->prepare(
                    "INSERT INTO absensi (id_mahasiswa, id_mk, tanggal_absensi, status, keterangan) 
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt_insert->execute([$id_mahasiswa, $id_mk, $tanggal, $status, $keterangan]);
            } else {
                // UPDATE yang ada
                $stmt_update = $pdo->prepare(
                    "UPDATE absensi SET status = ?, keterangan = ? 
                     WHERE id_absensi = ?"
                );
                $stmt_update->execute([$status, $keterangan, $id_absensi]);
            }
        }
        $pdo->commit();
        $success = "Data absensi berhasil disimpan!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal menyimpan data: " . $e->getMessage();
    }
}

// Ambil filter (jika ada)
$filter_tanggal = $_GET['tanggal'] ?? date('Y-m-d'); // Default hari ini
$filter_mk = $_GET['id_mk'] ?? '';

// Ambil daftar MK untuk filter
// INI PERBEDAANNYA: Ambil SEMUA mata kuliah
$stmt_mk_list = $pdo->query("SELECT * FROM mata_kuliah ORDER BY nama_mk");
$mk_list = $stmt_mk_list->fetchAll();

$absensi_list = [];
if (!empty($filter_mk) && !empty($filter_tanggal)) {
    // Kueri utama: Ambil semua peserta MK, lalu LEFT JOIN absensi
    $stmt_absensi = $pdo->prepare("
        SELECT 
            m.id_mahasiswa, m.nim, m.nama_lengkap,
            a.id_absensi, a.status, a.keterangan
        FROM peserta_mk p
        JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa
        LEFT JOIN absensi a ON p.id_mahasiswa = a.id_mahasiswa 
                           AND p.id_mk = a.id_mk 
                           AND a.tanggal_absensi = ?
        WHERE p.id_mk = ?
        ORDER BY m.nama_lengkap
    ");
    $stmt_absensi->execute([$filter_tanggal, $filter_mk]);
    $absensi_list = $stmt_absensi->fetchAll();
}
?>

<h2>Kelola Absensi Mahasiswa (Akses Super Admin)</h2>
<hr>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form action="kelola_absensi.php" method="GET" style="display: flex; gap: 10px; margin-bottom: 20px; align-items: flex-end;">
    <div class="form-group" style="margin-bottom: 0;">
        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>" class="form-control">
    </div>
    <div class="form-group" style="margin-bottom: 0;">
        <label for="id_mk">Mata Kuliah</label>
        <select name="id_mk" id="id_mk" class="form-control" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php foreach ($mk_list as $mk): ?>
                <option value="<?= $mk['id_mk'] ?>" <?= $filter_mk == $mk['id_mk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mk['nama_mk']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn">Tampilkan</button>
</form>

<?php if (!empty($absensi_list)): ?>
    <form action="kelola_absensi.php?tanggal=<?= $filter_tanggal ?>&id_mk=<?= $filter_mk ?>" method="POST">
        <input type="hidden" name="tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>">
        <input type="hidden" name="id_mk" value="<?= htmlspecialchars($filter_mk) ?>">
        
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absensi_list as $absen): ?>
                <tr>
                    <td><?= htmlspecialchars($absen['nim']) ?></td>
                    <td>
                        <?= htmlspecialchars($absen['nama_lengkap']) ?>
                        <input type="hidden" name="mahasiswa[<?= $absen['id_mahasiswa'] ?>][id_absensi]" value="<?= $absen['id_absensi'] ?>">
                    </td>
                    <td>
                        <select name="mahasiswa[<?= $absen['id_mahasiswa'] ?>][status]" style="padding: 5px;">
                            <option value="" <?= empty($absen['status']) ? 'selected' : '' ?>>-- Belum Absen --</option>
                            <option value="Hadir" <?= $absen['status'] == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                            <option value="Izin" <?= $absen['status'] == 'Izin' ? 'selected' : '' ?>>Izin</option>
                            <option value="Sakit" <?= $absen['status'] == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                            <option value="Alpa" <?= $absen['status'] == 'Alpa' ? 'selected' : '' ?>>Alpa</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="mahasiswa[<?= $absen['id_mahasiswa'] ?>][keterangan]" value="<?= htmlspecialchars($absen['keterangan'] ?? '') ?>" style="width: 100%;">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" style="margin-top: 15px;">Simpan Perubahan Absensi</button>
    </form>
<?php elseif (empty($filter_mk)): ?>
    <p>Silakan pilih mata kuliah dan tanggal untuk menampilkan data.</p>
<?php else: ?>
    <p>Tidak ada mahasiswa yang terdaftar di mata kuliah ini.</p>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>