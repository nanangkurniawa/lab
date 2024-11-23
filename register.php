<?php
    include "db.php";
    session_start();


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$role = $_POST['role'];

		$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
		$query->bind_param("s", $username);
		$query->execute();
		$result = $query->get_result();

		if ($result->num_rows > 0) {
			echo "Username sudah digunakan. Silakan pilih username lain.";
			exit;
		}
    
    if (empty($username)) {
    	$em = "mohon isikan username";
    	header("Location: signup.php?error=$em&$data");
	    exit;
    }else if(empty($password)){
    	$em = "mohon isikan password";
    	header("Location: signup.php?error=$em&$data");
		
	    exit;
    }else {

    


    	$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?,?)");
    $stmt->bind_param("sss", $username, $hashed_password,$role);
	 if ($stmt->execute()) {
        header("Location: signup.php?success=Akun berhasil dibuat, silahkan login");
	    exit;
    } else {
        echo "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
    }
}
}
