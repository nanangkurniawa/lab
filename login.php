<?php 
session_start(); 
include "db.php";
include('session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

	if (empty($username)) {
		header("Location: index.php?error=Harap Masukkan Username");
	    exit();
	}else if(empty($password)){
        header("Location: index.php?error=Harap Masukkan Password");
	    exit();
	}else{


		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if ($user && password_verify($password, $user['password'])) {
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['role'] = $user['role'];


				// Redirect ke halaman yang sesuai
				if ($user['role'] === 'admin') {
					header("Location: admin/index.php");
				} else {
					header("Location: dashboard.php");
				}
				exit();
            }else{
				header("Location: index.php?error=Username atau Password Salah");
		        exit();
			}
		}else{
			header("Location: index.php?error=Username atau Password Salah");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}