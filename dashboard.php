<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


echo "<h1>Selamat datang di Dashboard!</h1>";
echo "<a href='exam.php'>Mulai Ujian</a>";
echo "<br><a href='result.php'>Lihat Hasil</a>";
echo "<br><a href='logout.php'>Logout</a>";
?>
