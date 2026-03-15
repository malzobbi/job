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

// Get current status
$sql = "SELECT active FROM company WHERE id_company = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  header("Location: companies.php");
  exit();
}

$row = $result->fetch_assoc();
$newStatus = ($row['active'] == 1) ? 0 : 1;

// Update status
$update = "UPDATE company SET active = ? WHERE id_company = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param("ii", $newStatus, $id);
$stmt->execute();

header("Location: companies.php");
exit();
