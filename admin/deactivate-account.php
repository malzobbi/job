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

if(isset($_GET['id'])) {
	$active=$_GET['active'];
	if($active=='1'){
		$sql = "UPDATE users SET active='0' WHERE id_user=$userid";
	}else{
		$sql = "UPDATE users SET active='1' WHERE id_user=$userid";
	}
	if($conn->query($sql) == TRUE) {
		header("Location: allusers.php");
		exit();
	} else {
		echo $conn->error;
	}
} else {
	header("Location: allusers.php");
	exit();
}