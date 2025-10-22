<?php require __DIR__ . '/../templates/header.php'; ?>

<h2>Login Sistem Absensi</h2>
<hr>

<?php
// Tampilkan pesan error jika ada
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $message = 'Terjadi kesalahan.';
    if ($error == 'invalid') {
        $message = 'Username atau password salah.';
    } elseif ($error == 'not_logged_in') {
        $message = 'Anda harus login untuk mengakses halaman ini.';
    } elseif ($error == 'unauthorized') {
        $message = 'Anda tidak memiliki hak akses ke halaman tersebut.';
    }
    echo '<div class="alert alert-danger">' . $message . '</div>';
}

// Tampilkan pesan sukses jika ada
if (isset($_GET['success']) && $_GET['success'] == 'register') {
    echo '<div class="alert alert-success">Registrasi berhasil! Silakan login.</div>';
}
?>

<form action="proses_login.php" method="POST">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>