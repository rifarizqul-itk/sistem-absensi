<?php require __DIR__ . '/../templates/header.php'; ?>

<h2>Login Sistem Absensi</h2>
<hr>

<?php
// Tampilkan pesan error jika ada
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $message = 'Terjadi kesalahan.';
    if ($error == 'invalid') {
        $message = 'Login ID atau password salah.';
    } elseif ($error == 'not_logged_in') {
        $message = 'Anda harus login untuk mengakses halaman ini.';
    } elseif ($error == 'unauthorized') {
        $message = 'Anda tidak memiliki hak akses ke halaman tersebut.';
    }
    echo '<div class="alert alert-danger">' . $message . '</div>';
}
?>

<form action="proses_login.php" method="POST">
    <div class="form-group">
        <label for="login_id">Email</label>
        <input type="text" id="login_id" name="login_id" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>