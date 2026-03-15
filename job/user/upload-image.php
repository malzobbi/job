<?php
session_start();
if (empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");
$id_user = $_SESSION['id_user'];
   

if (isset($_POST['submit2'])) {
     
  $uploadDir = "avatar/";               // Folder to store files
  $allowedTypes = ['jpg', 'gif', 'png', 'avif'];
  $maxSize = 10 * 1024 * 1024;             // 10MB



  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $fileName = basename($_FILES["profile_image"]["name"]);
  $fileTmp  = $_FILES["profile_image"]["tmp_name"];
  $fileSize = $_FILES["profile_image"]["size"];
  $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  /* ----------------------------
     VALIDATIONS
  ----------------------------- */
  if (!in_array($fileExt, $allowedTypes)) {
    die('File type not allowed.<br> <a href="#" onclick="history.back(); return false;"> << back</a>');

  }

  if ($fileSize > $maxSize) {
    die('File is too large. Max 10MB.<br> <a href="#" onclick="history.back(); return false;"> << back</a>');

  }

  /* ----------------------------
     PREVENT DUPLICATE / SPOOFING
  ----------------------------- */
  $newFileName = uniqid("file_", true) . "." . $fileExt;
  $targetPath = $uploadDir . $newFileName;
  $imageInfo = getimagesize($_FILES["profile_image"]["tmp_name"]);
if ($imageInfo === false) {
  die('Uploaded file is not a valid image.<br> <a href="#" onclick="history.back(); return false;"> << back</a>');
}
  if (move_uploaded_file($fileTmp, $targetPath)) {
    $stmt = $conn->prepare("UPDATE users SET avatar=? WHERE id_user=?");
    $stmt->bind_param("si", $newFileName, $id_user);
    $stmt->execute();

    header("Location: cv.php");
    exit(); 
  } else {
    echo "Upload failed.";
    echo '<a href="#" onclick="history.back(); return false;"> << back</a>';

  }
}
