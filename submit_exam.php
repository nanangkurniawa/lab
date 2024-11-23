<?php
session_start();
include 'db.php';

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil jawaban pengguna dari sesi
$userAnswers = isset($_SESSION['user_answers']) ? $_SESSION['user_answers'] : [];
$totalQuestions = count($userAnswers);
$correctAnswers = 0;

// Hitung jawaban benar
foreach ($userAnswers as $questionId => $answer) {
    $query = $conn->prepare("SELECT correct_option FROM questions WHERE id = ?");
    $query->bind_param("i", $questionId);
    $query->execute();
    $result = $query->get_result();
    $correctOption = $result->fetch_assoc()['correct_option'];
    if ($correctOption === $answer) {
        $correctAnswers++;
    }
}
$query->close();
$conn->close();

// Bersihkan sesi terkait ujian
unset($_SESSION['user_answers']);
unset($_SESSION['time_remaining']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .result {
            font-size: 1.2em;
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .score {
            font-weight: bold;
            font-size: 1.5em;
            color: #333;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container a {
            text-decoration: none;
            display: inline-block;
            padding: 10px 20px;
            background: #d9534f; /* Warna merah untuk log out */
            color: white;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .button-container a:hover {
            background: #c9302c; /* Warna merah gelap saat hover */
        }

        .details {
            font-size: 0.9em;
            margin-top: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Terima Kasih!</h1>
        <p>Ujian Anda telah selesai. Berikut adalah hasil Anda:</p>
        <div class="result">
            <p>Jumlah Soal: <?= $totalQuestions ?></p>
            <p>Jawaban Benar: <?= $correctAnswers ?></p>
            <p class="score">Skor Anda: <?= $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0 ?>%</p>
        </div>
        <div class="button-container">
            <a href="logout.php">Log Out</a>
        </div>
        <div class="details">
            <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, silakan hubungi tim pengajar.</p>
        </div>
    </div>
</body>
</html>
