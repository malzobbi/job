<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Including Database Connection From db.php file to avoid rewriting in all files
require_once("db.php");

//If user Actually clicked login button 
if(isset($_POST)) {
	/**
 * Generate random complex password
 */
            function generatePassword($length = 12) {
                return substr(str_shuffle(
                    "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*"
                ), 0, $length);
            }

            /**
             * Encrypt password (legacy system)
             */
            function encryptPassword($password) {
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                return $encryptedPassword;
            }

        $newPassword = generatePassword();
        $encryptedPassword = encryptPassword($newPassword);


    


	//Escape Special Characters in String
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	
	$theType = mysqli_real_escape_string($conn, $_POST['tp']);

	// Use this when creating a password
  	//$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
if($theType=="user"){
	//sql query to check company login
	$stmt = $conn->prepare("SELECT id_user, firstname, email, active FROM users WHERE email=? LIMIT 1");

	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {		//output data

            //create random password 
            $firstname=$row['firstname'];
			if($row['active'] == '2') {
				$_SESSION['companyLoginError'] = true;
			    header("Location: result.php?error=2");
				exit();
			} else if($row['active'] == '0') {
				$_SESSION['companyLoginError'] = true;
			    header("Location: result.php?error=1");
				exit();
			} else if($row['active'] == '1') {	
    			// active 1 means admin has approved account.
				//1. update the database with the encrypted password 
                //sql query to check user login
                $sql = "UPDATE users SET password='$encryptedPassword' WHERE id_user='$row[id_user]'";
        if($conn->query($sql) === true) {
                   
                //2. Send an email with the normal password
                
                require 'autoload.php'; // if using Composer

				$mail = new PHPMailer(true);
				$domain = $_SERVER['HTTP_HOST'];
				$to  = $email;
				$subject = "Reset Password for ".$row['firstname'];
				$message = "This email was sent to reset your password upon your request. If you haven't reset";
                $message.="your password, then please login and reset your password immediatly";
				$message .="<br>Please use the following username and password to access<br>";
				$message.="Use the username: ".$email."<br>";
				$message.="Use tha password: ".$newPassword;
                $message.="<br><br>";
                $message.="AusTech Team";
					
			try {
				// Server settings
                
				$mail->isSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->SMTPAuth   =  true;
				$mail->Username   = 'malzobbi@gmail.com';   // SMTP email
				$mail->Password   = 'pxedqauskxyulzlt';      // Gmail App Password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port       = 587;

				// Sender
				$mail->setFrom("malzobbi@gmail.com","AusTech Team" );

				// Recipient
				$mail->addAddress($email,$firstname );

				// Email content
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body    = '<h3>'.$message.'<p><strong>Message from job finiding portal</strong></p>';

				$mail->AltBody = 'This email was sent to reset your password.';

				$mail->send();
				$conn->close();
                $_SESSION['success'] = true;
 		        header("Location: result.php?error=0");
                exit();
			} catch (Exception $e) {
				echo "Email could not be sent. Error: {$mail->ErrorInfo}";
			}
				
		 } else {
                    echo $conn->error;
         }
		
			} else if($row['active'] == '3') {
				$_SESSION['companyLoginError'] = true;
				header("Location: result.php?error=3");
				exit();
		    }
		
} else {
 		//if no matching record found in user table then redirect them back to login page
 		$_SESSION['companyLoginError'] = true;
 		header("Location: result.php?error=4");
		exit();
}
	
}else{
    //sql query to check company login
	$stmt = $conn->prepare("SELECT id_company, companyname, email, active FROM company WHERE email=? LIMIT 1");

	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
    //////////////////////////////////////////////
    //if company table has this this login details
if ($row = $result->fetch_assoc()) {		//output data
            //create random password 
            $companyname=$row['companyname'];
			if($row['active'] == '2') {
				$_SESSION['companyLoginError'] = true;
			    header("Location: result.php?error=2");
				exit();
			} else if($row['active'] == '0') {
				$_SESSION['companyLoginError'] = true;
			    header("Location: result.php?error=1");
				exit();
			} else if($row['active'] == '1') {	
    			// active 1 means admin has approved account.
				//1. update the database with the encrypted password 
                //sql query to check user login
                $sql = "UPDATE company SET password='$encryptedPassword' WHERE id_company='$row[id_company]'";
        if($conn->query($sql) === true) {
                    
        

                //2. Send an email with the normal password
                
                require 'autoload.php'; // if using Composer

				$mail = new PHPMailer(true);
				$domain = $_SERVER['HTTP_HOST'];
				$to  = $email;
				$subject = "Reset Password  ".$row['companyname'];
				$message = "This email was sent to reset your password upon your request. If you haven't reset";
                $message.="your password, then please login and reset your password immediatly";
				$message .="<br>Please use the following username and password to access<br>";
				$message.="Use the username: ".$email."<br>";
				$message.="Use tha password: ".$newPassword;
                $message.="<br><br>";
                $message.="AusTech Team";
					
			try {
				// Server settings
				$mail->isSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->SMTPAuth   =  true;
				$mail->Username   = 'malzobbi@gmail.com';   // SMTP email
				$mail->Password   = 'pxedqauskxyulzlt';      // Gmail App Password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port       = 587;

				// Sender
				$mail->setFrom("malzobbi@gmail.com","AusTech Team" );

				// Recipient
				$mail->addAddress($email,$companyname );

				// Email content
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body    = '<h3>'.$message.'<p><strong>Message from job finiding portal</strong></p>';

				$mail->AltBody = 'This email was sent to reset your password.';

				$mail->send();
				$conn->close();
                $_SESSION['success'] = true;
 		        header("Location: result.php?error=0");
                exit();
			} catch (Exception $e) {
				echo "Email could not be sent. Error: {$mail->ErrorInfo}";
			}
				
		 } else {
                    echo $conn->error;
         }
		
			} else if($row['active'] == '3') {
				$_SESSION['companyLoginError'] = true;
				header("Location: result.php?error=3");
				exit();
		    }
		
} else {
 		//if no matching record found in user table then redirect them back to login page
 		$_SESSION['companyLoginError'] = true;
 		header("Location: result.php?error=4");
		exit();
}



}

}
