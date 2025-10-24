<?php
session_start();
// Arahkan jika sudah login (SESUAI LOGIKA BARU KITA)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'superadmin') {
        header('Location: ../superadmin/index.php');
        exit;
    } elseif ($_SESSION['role'] == 'dosen') {
        header('Location: ../dosen/index.php');
        exit;
    } elseif ($_SESSION['role'] == 'mahasiswa') {
        header('Location: ../mahasiswa/index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi - Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-page"> <div class="login-wrapper">
        <div class="login-box">
            
            <div class="login-header">
                <h2>Absensi Mahasiswa</h2>
                <p>Silahkan masukkan data login Anda!</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                $message = 'Terjadi kesalahan.';
                if ($error == 'invalid') {
                    $message = 'Login ID atau password salah.';
                } elseif ($error == 'not_logged_in') {
                    $message = 'Anda harus login untuk mengakses halaman ini.';
                } elseif ($error == 'unauthorized') {
                    $message = 'Anda tidak memiliki hak akses ke halaman tersebut.';
                } elseif ($error == 'data_sync') {
                    $message = 'Data akun Anda tidak sinkron. Hubungi Super Admin.';
                }
                // Tampilkan error di dalam style baru
                echo '<p class="login-error">' . htmlspecialchars($message) . '</p>';
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
                
                <button type="submit" class="btn-masuk">Masuk</button>
            </form>
        </div>
    </div>

</body>
</html>