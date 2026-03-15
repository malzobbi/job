<?php
session_start();

if (empty($_SESSION['id_company'])) {
    header("Location: ../index.php");
    exit();
}

require_once("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect & sanitize inputs
    $jobtitle   = $_POST['jobtitle'];
    $joblink    = $_POST['apply_link'];
    $description= $_POST['description'];
    $location   = $_POST['location'];
    $subtitle   = $_POST['subtitle'];
    $experience = $_POST['experience'];
    $skill      = $_POST['skill']; // skill ID
    $status=0;
    // Prepared statement
    $stmt = $conn->prepare("
        INSERT INTO job_post 
        (id_company, jobtitle, description, location, subtitle, experience, qualification, apply_link,job_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssssssi",
        $_SESSION['id_company'],
        $jobtitle,
        $description,
        $location,
        $subtitle,
        $experience,
        $skill,
        $joblink,
        $status
    );

    if ($stmt->execute()) {
        $_SESSION['jobPostSuccess'] = true;
        header("Location: index.php");
        exit();
    } else {
        echo "Database Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: create-job-post.php");
    exit();
}
