<?php
session_start();

if (empty($_SESSION['id_admin'])) {
    header("Location: index.php");
    exit();
}

require_once("../db.php");

if (!isset($_GET['id'])) {
    header("Location: companies.php");
    exit();
}

$id = (int) $_GET['id'];
$sql = "SELECT * FROM company where id_company=$id";
                      $result = $conn->query($sql);
                      if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $company=$row['companyname']; 
                            $person=$row['name'];
                            $emails=$row['email'];
                        }
                    }

/**
 * Generate random complex password
 */
function generatePassword($length = 12) {
    return substr(str_shuffle(
        "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*"
    ), 0, $length);
}

/**
 * Encrypt password (legacy system)
 */
function encryptPassword($password) {
    $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
    return $encryptedPassword;
}

$newPassword = generatePassword();
$encryptedPassword = encryptPassword($newPassword);

// Update password in database
$sql = "UPDATE company SET password = ? WHERE id_company = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $encryptedPassword, $id);
$stmt->execute();

// Flash message for admin
$_SESSION['reset_msg'] = 
    "Password has been reset successfully. <br><hr>
     <b>New Password:</b> $newPassword <br>
     <b>Company name:</b> $company <br>
     Please copy and send it to ($person)<br>
     The email is: $emails";

header("Location: companies.php");
exit();
