<?php
session_start();
if (empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");
$id_user = $_SESSION['id_user'];

if (isset($_POST['submit'])) {
  $uploadDir = "resume/";               // Folder to store files
  $allowedTypes = ['doc', 'pdf', 'docx','odt','html','rtf'];
  $maxSize = 6 * 1024 * 1024;             // 6MB

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $fileName = basename($_FILES["upload_file"]["name"]);
  $fileTmp  = $_FILES["upload_file"]["tmp_name"];
  $fileSize = $_FILES["upload_file"]["size"];
  $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  /* ----------------------------
     VALIDATIONS
  ----------------------------- */
  if (!in_array($fileExt, $allowedTypes)) {
    die('File type not allowed.<br> <a href="#" onclick="history.back(); return false;"> << back</a>');

  }

  if ($fileSize > $maxSize) {
    die('File is too large. Max 6MB.<br> <a href="#" onclick="history.back(); return false;"> << back</a>');

  }

  /* ----------------------------
     PREVENT DUPLICATE / SPOOFING
  ----------------------------- */
  $newFileName = uniqid("file_", true) . "." . $fileExt;
  $targetPath = $uploadDir . $newFileName;

  if (move_uploaded_file($fileTmp, $targetPath)) {
    $stmt = $conn->prepare("UPDATE users SET resume=? WHERE id_user=?");
    $stmt->bind_param("si", $newFileName, $id_user);
    $stmt->execute();

    header("Location: cv.php");
    exit(); 
  } else {
    echo "Upload failed.";
    echo '<a href="#" onclick="history.back(); return false;"> << back</a>';

  }
}
