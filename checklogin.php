<?php

//To Handle Session Variables on This Page
session_start();

//Including Database Connection From db.php file to avoid rewriting in all files
require_once("db.php");

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed");
    }

    // Escape inputs
    $username = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

	

    if (!$username || !$password) {
		
        $_SESSION['loginError'] = true;
        header("Location: login-candidates.php");
        exit();
    }

    // Use this when creating a password
  	//$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	$stmt = $conn->prepare("SELECT id_user, password,active FROM users WHERE email = ? LIMIT 1");

	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
  
		
	if ($row = $result->fetch_assoc()) {
        
		if($row['active'] == 0) {
				$_SESSION['loginError'] = true;
				header("Location: login-candidates.php");
				exit();
		} else{
		
    if (password_verify($password, $row['password'])) {
  
        // Regenerate session to prevent fixation
        session_regenerate_id(true);

        $_SESSION['id_user'] = $row['id_user'];

        // CSRF token should be single-use
        unset($_SESSION['csrf_token']);

        header("Location: user/index.php");
        exit();
    }else{
		$_SESSION['loginError'] = true;
    	header("Location: login-candidates.php");
    	exit();
	}
        }
}else{
        
        $_SESSION['loginError'] = true;
    	header("Location: login-candidates.php");
    	exit();
}
}
