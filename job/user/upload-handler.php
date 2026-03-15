<?php

// Make sure the upload directory exists
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if file was uploaded
if (isset($_FILES['myfile'])) {

    $fileName = basename($_FILES['myfile']['name']);
    $fileTmp  = $_FILES['myfile']['tmp_name'];
    $fileSize = $_FILES['myfile']['size'];
    $fileError = $_FILES['myfile']['error'];

    // Optional: restrict file types
    $allowedTypes = ['jpg','jpeg','png','pdf','doc','docx'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        die("❌ File type not allowed");
    }

    if ($fileError === 0) {
        $newFileName = time() . "_" . $fileName;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $destination)) {
            echo "✅ File uploaded successfully!<br>";
            echo "Saved as: " . $newFileName;
        } else {
            echo "❌ Failed to move uploaded file";
        }
    } else {
        echo "❌ Upload error code: " . $fileError;
    }

} else {
    echo "❌ No file received";
}
