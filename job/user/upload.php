<?php
if (isset($_POST['submit'])) {

  $uploadDir = "uploads/";               // Folder to store files
  $allowedTypes = ['jpg','jpeg','png','gif','pdf','doc','docx'];
  $maxSize = 2 * 1024 * 1024;             // 2MB

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
    die("File type not allowed.");
  }

  if ($fileSize > $maxSize) {
    die("File is too large. Max 2MB.");
  }

  /* ----------------------------
     PREVENT DUPLICATE / SPOOFING
  ----------------------------- */
  $newFileName = uniqid("file_", true) . "." . $fileExt;
  $targetPath = $uploadDir . $newFileName;

  if (move_uploaded_file($fileTmp, $targetPath)) {
    echo "File uploaded successfully!";
  } else {
    echo "Upload failed.";
  }
}
