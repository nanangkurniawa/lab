<?php
session_start();
include 'db.php';

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil atau inisialisasi waktu ujian
if (!isset($_SESSION['time_remaining'])) {
    $_SESSION['time_remaining'] = 1800; // 30 menit
}
$timeRemaining = $_SESSION['time_remaining'];


// Ambil jawaban yang disimpan dari session
$userAnswers = isset($_SESSION['user_answers']) ? $_SESSION['user_answers'] : [];

// Ambil pengaturan batas soal dari database
$category_limit = [];
$query = "SELECT category_id, question_limit FROM category_limits";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $category_limit[$row['category_id']] = $row['question_limit'];
    }
}

// Default batas soal jika belum ada di database
$category_limit += [
    1 => 3,
    2 => 3,
    3 => 3,
    4 => 3,
    5 => 3,
    6 => 3,
];

// Ambil soal berdasarkan kategori
$questions = [];
foreach ($category_limit as $category => $limit) {
    $query = "SELECT id, question, option_a, option_b, option_c, option_d, correct_option 
              FROM questions 
              WHERE kategori = $category
              ORDER BY RAND()
              LIMIT $limit";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }
}

// Acak soal menggunakan Fisher-Yates Shuffle
function fisherYatesShuffle($questions) {
    $n = count($questions);
    for ($i = $n - 1; $i > 0; $i--) {
        $j = rand(0, $i);
        $temp = $questions[$i];
        $questions[$i] = $questions[$j];
        $questions[$j] = $temp;
    }
    return $questions;
}

if (!isset($_SESSION['shuffled_questions'])) {
    $_SESSION['shuffled_questions'] = fisherYatesShuffle($questions);
}

$shuffledQuestions = $_SESSION['shuffled_questions'];


// Menyimpan waktu mulai ujian jika belum ada di sesi
if (!isset($_SESSION['exam_start_time'])) {
    $_SESSION['exam_start_time'] = time(); // Waktu mulai dalam detik
}

$examDuration = 900; // Durasi ujian dalam detik (15 menit)
$timeElapsed = time() - $_SESSION['exam_start_time']; // Waktu yang sudah berlalu
$timeRemaining = max($examDuration - $timeElapsed, 0); // Sisa waktu
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Online</title>
    <script>
       let timeRemaining = <?= $timeRemaining ?>; // Waktu tersisa diambil dari server (PHP)

       function startTimer() {
            const timerElement = document.getElementById('timer');
            const interval = setInterval(() => {
                if (timeRemaining <= 0) {
                    clearInterval(interval);
                    alert('Waktu ujian telah habis!');
                    document.getElementById('examForm').submit(); // Kirim form otomatis
                    return;
                }

                // Kirim waktu tersisa ke server setiap 1 menit
                if (timeRemaining % 60 === 0) {
                    updateServerTime(timeRemaining);
                }

                // Kurangi waktu dan perbarui tampilan
                timeRemaining--;
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }, 1000);
        }

        function updateServerTime(remainingTime) {
            const formData = new FormData();
            formData.append('time_remaining', remainingTime);

            fetch('save_timer.php', {
                method: 'POST',
                body: formData
            }).catch(error => console.error('Error updating timer:', error));
        }

        // Simpan jawaban menggunakan AJAX
        function saveAnswer(questionId, selectedOption) {
            const formData = new FormData();
            formData.append('question_id', questionId);
            formData.append('selected_option', selectedOption);

            fetch('save_answer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => console.log("Saved:", data)) // Debugging response
            .catch(error => console.error('Error:', error));
        }

        // Jalankan saat halaman dimuat
        window.onload = () => {
            startTimer();

            const form = document.getElementById('examForm');
            form.addEventListener('submit', validateForm);

            const inputs = document.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => {
                input.addEventListener('change', function () {
                    const questionId = this.name.match(/\d+/)[0]; // ID soal
                    const selectedOption = this.value;
                    saveAnswer(questionId, selectedOption);
                });
            });
        };

        // Validasi form sebelum pengiriman
        function validateForm(event) {
            const questions = document.querySelectorAll('.question');
            let isValid = true;

            questions.forEach((question) => {
                const inputs = question.querySelectorAll('input[type="radio"]');
                const isAnswered = Array.from(inputs).some(input => input.checked);

                if (!isAnswered) {
                    isValid = false;
                    question.classList.add('unanswered');
                } else {
                    question.classList.remove('unanswered');
                }
            });

            if (!isValid) {
                alert('Harap isi semua jawaban sebelum mengirim.');
                event.preventDefault(); // Cegah pengiriman form
            }
        }
    </script>
    <style>
    .unanswered {
            border: 1px solid red;
            padding: 5px;
        }
        body, h1, h2, p, ul, ol, li, form, input, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }
    label {
            display: block;
            margin: 5px 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e74c3c;
            text-align: right;
        }

        .unanswered {
            border: 2px solid red;
            padding: 10px;
            border-radius: 5px;
        }

        .question {
            margin-bottom: 30px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .question p {
            font-weight: bold;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin: 8px 0;
        }

        button {
            display: inline-block;
            background: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #2980b9;
        }

        .submit-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
        <!-- Timer -->
        <div id="timer" style="font-size: 1.5em; font-weight: bold; margin-bottom: 20px;">
            <?= floor($timeRemaining / 60) ?>:<?= str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) ?>
        </div>

        <!-- Form Ujian -->
        <form id="examForm" action="submit_exam.php" method="POST">
            <?php foreach ($shuffledQuestions as $index => $question): ?>
                <div class="question">
                    <p><?= ($index + 1) . ". " . htmlspecialchars($question['question']) ?></p>
                    <?php foreach (['a', 'b', 'c', 'd'] as $option): ?>
                        <label>
                            <input type="radio" name="answer[<?= $question['id'] ?>]" value="<?= $option ?>" 
                                <?= isset($userAnswers[$question['id']]) && $userAnswers[$question['id']] === $option ? 'checked' : '' ?>>
                            <?= htmlspecialchars($question['option_' . $option]) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="submit-btn" style="margin-top: 20px;">
                <button type="submit">Kirim Jawaban</button>
            </div>
        </form>
    </div>
</body>
</html>
