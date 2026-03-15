<?php

// Database connection
require_once("db.php");

// Check if id exists in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request.");
}
// active =1 means active user
$hash = $_GET['id'];

// Prepare SQL statement
$stmt = $conn->prepare("UPDATE users SET active = 1 WHERE hash = ?");
$stmt->bind_param("s", $hash);
$isCorrect=false;
// Execute
if ($stmt->execute()) {
//print_r($stmt);
    if ($stmt->affected_rows > 0) {
      $isCorrect=true;
      
       
    } else {
      $isCorrect=false;
       
    }

} else {
    echo "Error updating user.";
}

// Close statement

require_once("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Status Message</title>
<link rel="icon" href="/favicon.ico">

<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .message-box {
  width: 100vw;          /* Full viewport width */
  max-width: 100vw;
  margin-left: calc(-50vw + 50%);
  padding: 20px 25px;
  border-radius: 0;     /* Optional: remove rounding for full width */
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  animation: fadeIn 0.6s ease;
}

  .success {
    background: #e6f7ed;
    border-left: 6px solid #28a745;
    color: #155724;
  }

  .error {
    background: #fdecea;
    border-left: 6px solid #dc3545;
    color: #721c24;
  }

  h2 {
    margin-bottom: 10px;
  }

  p {
    margin: 0;
    font-size: 15px;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>

<body>
<?php
  if($isCorrect){
?>
<!-- SUCCESS MESSAGE -->
<div class="message-box success">
  <h2>✅ Success</h2>
  <p>User was activated successfully. You may login now.</p>
  
</div>
<?php }else{ ?>
<!-- ERROR MESSAGE (use instead of success) -->

<div class="message-box error">
  <h2>❌ Error</h2>
  <p>Something went wrong. Please try again.</p>
</div>
<?php 
$stmt->close();
$conn->close();
}
?>

</body>
</html>