<?php
include('../session.php');
include('../db.php');
// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}


// Simpan pengaturan batas soal per kategori ke database
if (isset($_POST['set_exam_limit'])) {
    $category_limit = [
        1 => intval($_POST['category_1_limit']),
        2 => intval($_POST['category_2_limit']),
        3 => intval($_POST['category_3_limit']),
        4 => intval($_POST['category_4_limit']),
        5 => intval($_POST['category_5_limit']),
        6 => intval($_POST['category_6_limit']),
    ];

    // Simpan atau perbarui batas soal di database
    foreach ($category_limit as $category => $limit) {
        $stmt = $conn->prepare("INSERT INTO category_limits (category_id, question_limit) 
                                VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE question_limit = ?");
        $stmt->bind_param("iii", $category, $limit, $limit);
        $stmt->execute();
    }

    $_SESSION['category_limit'] = $category_limit; // Opsional, untuk sesi saat ini
    header("Location: kelola_soal.php");
    exit();
}

// Ambil batas soal dari database untuk ditampilkan di form
$category_limit = [];
$query = "SELECT category_id, question_limit FROM category_limits";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $category_limit[$row['category_id']] = $row['question_limit'];
    }
}

// Ambil pengaturan batas soal jika ada di sesi
$category_limit += [
    1 => 3,
    2 => 3,
    3 => 3,
    4 => 3,
    5 => 3,
    6 => 3,
];
// Fungsi untuk menambahkan soal
if (isset($_POST['add_question'])) {
    $question_text = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $kategori=$_POST['kategori'];

    $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option,kategori) VALUES (?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssss", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option,$kategori);
    $stmt->execute();
    header("Location: kelola_soal.php");
    exit;
}

// Fungsi untuk menghapus soal
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: kelola_soal.php");
    exit;
}

// Fungsi untuk mengedit soal
if (isset($_POST['edit_question'])) {
    $id = $_POST['id'];
    $question_text = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $kategori=$POST['kategori'];

    $stmt = $conn->prepare("UPDATE questions SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?, kategorri=? WHERE id = ?");
    $stmt->bind_param("sssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option,$kategori, $id);
    $stmt->execute();
    header("Location: kelola_soal.php");
    exit;
}

// Ambil semua soal
$query = "SELECT * FROM questions";
$result = $conn->query($query);


?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .custom-card-header {
            background-color: #ffc107; /* Warna kuning */
            color: #333; /* Warna teks */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Kelola Soal</h1>

        <form method="POST" action="">
    <div class="row">
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="col-md-6">
            <label for="category_<?= $i ?>_limit" class="form-label">Jumlah Soal Kategori <?= $i ?>:</label>
            <input type="number" id="category_<?= $i ?>_limit" name="category_<?= $i ?>_limit" 
                   min="0" max="100" class="form-control" 
                   value="<?= $category_limit[$i] ?>" required>
        </div>
        <?php endfor; ?>
    </div>
    <div class="mt-3">
        <button type="submit" name="set_exam_limit" class="btn btn-warning w-100">Terapkan Pengaturan</button>
    </div>
</form>



        <!-- Form Tambah Soal -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Tambah Soal Baru
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <textarea name="question" rows="3" class="form-control" placeholder="Tulis soal di sini..." required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="option_a" class="form-control" placeholder="Opsi A" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="option_b" class="form-control" placeholder="Opsi B" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="option_c" class="form-control" placeholder="Opsi C" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="option_d" class="form-control" placeholder="Opsi D" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="correct_option" class="form-label">Jawaban Benar:</label>
                            <select name="correct_option" class="form-select" required>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori:</label>
                            <select name="kategori" class="form-select" required>
                                <option value="1">C1</option>
                                <option value="2">C2</option>
                                <option value="3">C3</option>
                                <option value="4">C4</option>
                                <option value="5">C5</option>
                                <option value="6">C6</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_question" class="btn btn-success mt-3 w-100">Tambah Soal</button>
                </form>
            </div>
        </div>

        <!-- Tabel Soal -->
        <div class="card">
            <div class="card-header bg-info text-white">
                Daftar Soal
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Soal</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Jawaban Benar</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['question']) ?></td>
                                <td><?= htmlspecialchars($row['option_a']) ?></td>
                                <td><?= htmlspecialchars($row['option_b']) ?></td>
                                <td><?= htmlspecialchars($row['option_c']) ?></td>
                                <td><?= htmlspecialchars($row['option_d']) ?></td>
                                <td><?= $row['correct_option'] ?></td>
                                <td><?= $row['kategori'] ?></td>
                                <td>
                                    <a href="edit_soal.php?id=<?= $row['id'] ?>" class="btn btn-edit btn-sm">Edit</a>
                                    <a href="kelola_soal.php?delete=<?= $row['id'] ?>" 
                                       class="btn btn-delete btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus soal ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2024 Sistem Kelola Soal. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>