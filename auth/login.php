<?php
session_start();
// Arahkan jika sudah login
if (isset($_SESSION['user_id'])) {
    // Arahkan ke dashboard yang sesuai, contoh: ../index.php
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-box">
            
            <div class="login-header">
                <h2>Absensi Mahasiswa</h2>
                <p>Silahkan masukkan username dan password anda terlebih dahulu!</p>
            </div>

            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (isset($_GET['error'])): ?>
                <p class="login-error">Username atau Password salah!</p>
            <?php endif; ?>
                <button type="submit" class="btn-masuk">Masuk</button>
                <a href="#" class="forgot-password">Lupa password?</a>
            </form>
        </div>
        </div>
    </div>

</body>
</html>