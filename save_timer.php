<?php
session_start();

// Simpan waktu ujian ke sesi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_remaining'])) {
    $_SESSION['time_remaining'] = intval($_POST['time_remaining']);
    echo "Waktu tersisa diperbarui: " . $_SESSION['time_remaining'];
} else {
    echo "Permintaan tidak valid";
}
