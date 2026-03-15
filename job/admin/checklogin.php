<?php
session_start();
require_once("../db.php");

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed");
    }

    // Escape inputs
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['ppp'] ?? '');


    if (!$username || !$password) {
        $_SESSION['loginError'] = true;
        header("Location: index.php");
        exit();
    }

    // Use this when creating a password
  	//$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	$stmt = $conn->prepare("SELECT id_admin, password FROM admin WHERE username = ? LIMIT 1");

	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	
	
	if ($row = $result->fetch_assoc()) {

    if (password_verify($password, $row['password'])) {

        // Regenerate session to prevent fixation
        session_regenerate_id(true);

        $_SESSION['id_admin'] = $row['id_admin'];

        // CSRF token should be single-use
        unset($_SESSION['csrf_token']);

        header("Location: dashboard.php");
        exit();
    }
}
}