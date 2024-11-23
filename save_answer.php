<?php
session_start();

// Simpan jawaban ke sesi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'], $_POST['selected_option'])) {
    $questionId = $_POST['question_id'];
    $selectedOption = $_POST['selected_option'];

    $_SESSION['user_answers'][$questionId] = $selectedOption;
    echo "Jawaban disimpan: Soal $questionId -> $selectedOption";
} else {
    echo "Permintaan tidak valid";
}
