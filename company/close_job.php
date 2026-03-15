<?php
// Start session
session_start();

// If company not logged in, redirect
if (empty($_SESSION['id_company'])) {
    header("Location: ../index.php");
    exit();
}

// DB connection
require_once("../db.php");

// Check if jobNo is sent via POST
if (!isset($_POST['jobNo']) || empty($_POST['jobNo'])) {
    header("Location: my-job-post.php");
    exit();
}

// Sanitize job id
$jobId = (int) $_POST['jobNo'];
$companyId = (int) $_SESSION['id_company'];

// 1️⃣ Check job status (and ownership)
$sqlCheck = "SELECT job_status 
             FROM job_post 
             WHERE id_jobpost = ? AND id_company = ?";

$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("ii", $jobId, $companyId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Job not found or not owned by this company
    header("Location: my-job-post.php");
    exit();
}
$row = $result->fetch_assoc();

// 2️⃣ If already closed, just redirect
if ((int)$row['job_status'] === 0) {
// Prepare SQL (SECURE)
$stmt = $conn->prepare("
    UPDATE job_post 
    SET job_status = 1 
    WHERE id_jobpost = ? AND id_company = ?
");

}else{
    $stmt = $conn->prepare("
    UPDATE job_post 
    SET job_status = 0 
    WHERE id_jobpost = ? AND id_company = ?
");

}
$stmt->bind_param("ii", $jobId, $companyId);

// Execute
if ($stmt->execute()) {
    // Success → redirect back
    header("Location: my-job-post.php?closed=success");
    exit();
} else {
    // Failure → redirect with error
    header("Location: my-job-post.php?closed=error");
    exit();
}

// Close statement
$stmt->close();
$conn->close();
?>