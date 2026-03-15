<?php

session_start();
if(isset($_GET['id'])){
	$userid=$_GET['id'];

}

if(empty($_SESSION['id_admin'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");

if(isset($_GET)) {

	$sql = "DELETE FROM users WHERE id_user=$userid";
	if($conn->query($sql)) {
		$sql1 = "DELETE FROM apply_job_post WHERE id_user=$userid";
		if($conn->query($sql1)) {
		}
		header("Location: allusers.php");
		exit();
	} else {
		echo "Error";
	}
}