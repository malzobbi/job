<?php
session_start();

if (empty($_SESSION['id_company'])) {
    header("Location: ../index.php");
    exit();
}

require_once("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
    $website     = mysqli_real_escape_string($conn, $_POST['website']);
    $city        = mysqli_real_escape_string($conn, $_POST['city']);
    $state       = mysqli_real_escape_string($conn, $_POST['state']);
    $contactno   = mysqli_real_escape_string($conn, $_POST['contactno']);
    $aboutme     = mysqli_real_escape_string($conn, $_POST['aboutme']);

    $logoFile = null;

    // ===============================
    // IMAGE UPLOAD (optional)
    // ===============================
    if (!empty($_FILES['image']['name'])) {

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $folder_dir = "../uploads/logo/";
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($imageFileType, $allowedExt)) {
            $_SESSION['uploadError'] = "Wrong format. Allowed: JPG, PNG, WEBP, GIF";
            header("Location: edit-company.php");
            exit();
        }

        // Validate size (5MB)
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $_SESSION['uploadError'] = "Wrong size. Max allowed: 5MB";
            header("Location: edit-company.php");
            exit();
        }

        // Create unique filename
        $logoFile = uniqid('logo_', true) . '.' . $imageFileType;
        $filename = $folder_dir . $logoFile;

        // Move file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
            $_SESSION['uploadError'] = "Image upload failed.";
            header("Location: edit-company.php");
            exit();
        }
    }

    // ===============================
    // UPDATE QUERY
    // ===============================
    $sql = "
        UPDATE company SET
            name='$name',
            companyname='$companyname',
            website='$website',
            city='$city',
            state='$state',
            contactno='$contactno',
            aboutme='$aboutme'
    ";

    if ($logoFile !== null) {
        $sql .= ", logo='$logoFile'";
    }

    $sql .= " WHERE id_company='" . $_SESSION['id_company'] . "'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['name'] = $companyname;
        header("Location: index.php");
        exit();
    } else {
        echo "Database Error: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: edit-company.php");
    exit();
}
