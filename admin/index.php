<?php
include('../session.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Selamat datang, Admin!</h1>
        <p><a href="kelola_soal.php">ks</a></p>
        <p><a href="../logout.php">Logout</a></p>
        <!-- Konten admin di sini, misalnya daftar pengguna, manajemen ujian, dll -->
    </div>
</body>
</html>
