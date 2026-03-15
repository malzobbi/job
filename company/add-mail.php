<?php


session_start();

if(empty($_SESSION['id_company'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../autoload.php'; // if using Composer

$mail = new PHPMailer(true);
if(isset($_POST)) {
	$to  = $_POST['to'];
	$sub=$_POST['subject'];
	$desc=$_POST['description'];
	$subject = mysqli_real_escape_string($conn, $sub);
	$message = mysqli_real_escape_string($conn, $desc);

	//get the useremail from the user id
	$sql = "SELECT * FROM users WHERE id_user=$to";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
						$sendto=$row['email'];
						$firstname=$row['firstname'];
						$lastname=$row['lastname'];
						$fullname=$firstname." ".$lastname;
                        
					  }
					}
		$sql2 = "SELECT * FROM company WHERE id_company=$_SESSION[id_company]";
                    $result2 = $conn->query($sql2);
                    if($result2->num_rows > 0) {
                      while($row2 = $result2->fetch_assoc()) {
						$companyname=$row2['companyname'];
						$senderemail=$row2['email'];
						
                        
					  }
					}		
		
}else{
	header("Location: mailbox.php");
	exit();
}
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'malzobbi@gmail.com';   // SMTP email
    $mail->Password   = 'pxedqauskxyulzlt';      // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom($senderemail, $companyname);

    // Recipient
    $mail->addAddress($sendto, $fullname);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = '<h3>'.$message.'<p><strong>Message from job finiding portal</strong></p>';
    $mail->AltBody = 'This email was sent to and you may reply from the job finding portal system from AusTech';

    $mail->send();
    echo "Email sent successfully";

} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}



	

	

	$sql = "INSERT INTO mailbox (id_fromuser, fromuser, id_touser, subject, message) VALUES ('$_SESSION[id_company]', '$companyname', '$to', '$subject', '$message')";

	if($conn->query($sql) == TRUE) {
		header("Location: mailbox.php");
		exit();
	} else {
		echo $conn->error;
	}


?>