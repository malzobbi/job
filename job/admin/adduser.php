<?php

//To Handle Session Variables on This Page
session_start();

if(empty($_SESSION['id_admin'])) {
  header("Location: index.php");
  exit();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
		
//Including Database Connection From db.php file to avoid rewriting in all files
require_once("../db.php");

//If user Actually clicked register button
if(isset($_POST)) {

	//Escape Special Characters In String First
	$firstname = mysqli_real_escape_string($conn, $_POST['fname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lname']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$city = mysqli_real_escape_string($conn, $_POST['city']);
	$state = mysqli_real_escape_string($conn, $_POST['state']);
	$contactno = mysqli_real_escape_string($conn, $_POST['contactno']);
	$qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
	$stream = mysqli_real_escape_string($conn, $_POST['stream']);
	$passingyear = mysqli_real_escape_string($conn, $_POST['passingyear']);
	$dob = mysqli_real_escape_string($conn, $_POST['dob']);
	$age = mysqli_real_escape_string($conn, $_POST['age']);
	$designation = mysqli_real_escape_string($conn, $_POST['designation']);
	$aboutme = mysqli_real_escape_string($conn, $_POST['aboutme']);
	$skills = mysqli_real_escape_string($conn, $_POST['skills']);
	$gender=mysqli_real_escape_string($conn, $_POST['gender']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$passwords = mysqli_real_escape_string($conn, $_POST['password']);
	
	//check the password complexity
	
if (!isPasswordStrong($passwords)) {
	$_SESSION['registerError'] = true;
	
	header("Location: register-candidates.php");
	exit;
}

	//Encrypt Password
	//$password = base64_encode(strrev(md5($password)));
	$password = password_hash($passwords, PASSWORD_DEFAULT);




	//sql query to check if email already exists or not
	$sql = "SELECT email FROM users WHERE email='$email'";
	$result = $conn->query($sql);

	//if email not found then we can insert new data
	if($result->num_rows == 0) {

					//This variable is used to catch errors doing upload process. False means there is some error and we need to notify that user.
			$uploadOk = true;

			//Folder where you want to save your resume. THIS FOLDER MUST BE CREATED BEFORE TRYING
			$folder_dir = "../user/resume/";

			//Getting Basename of file. So if your file location is Documents/New Folder/myResume.pdf then base name will return myResume.pdf
			$base = basename($_FILES['resume']['name']); 

			//This will get us extension of your file. So myResume.pdf will return pdf. If it was resume.doc then this will return doc.
			$resumeFileType = pathinfo($base, PATHINFO_EXTENSION); 

			//Setting a random non repeatable file name. Uniqid will create a unique name based on current timestamp. We are using this because no two files can be of same name as it will overwrite.
			$file = uniqid() . "." . $resumeFileType;   

			//This is where your files will be saved so in this case it will be uploads/resume/newfilename
			$filename = $folder_dir .$file;  

	//We check if file is saved to our temp location or not.
	if(file_exists($_FILES['resume']['tmp_name'])) { 

		//Next we need to check if file type is of our allowed extention or not. I have only allowed pdf. You can allow doc, jpg etc. 
		if($resumeFileType == "pdf" or $resumeFileType == "docx")  {

			//Next we need to check file size with our limit size. I have set the limit size to 5MB. Note if you set higher than 2MB then you must change your php.ini configuration and change upload_max_filesize and restart your server
			if($_FILES['resume']['size'] < 10000000) { // File size is less than 10MB

				//If all above condition are met then copy file from server temp location to uploads folder.
				move_uploaded_file($_FILES["resume"]["tmp_name"], $filename);

			} else {
				//Size Error
				$_SESSION['uploadError'] = "Wrong Size. Max Size Allowed : 10MB";
				$uploadOk = false;
			}
		} else {
			//Format Error
			$_SESSION['uploadError'] = "Wrong Format. Only PDF or DOCX Allowed";
			$uploadOk = false;
		}
	} else {
			//File not copied to temp location error.
			$_SESSION['uploadError'] = "Something Went Wrong. File Not Uploaded. Try Again.";
			$uploadOk = false;
	}

	//If there is any error then redirect back.
	if($uploadOk == false) {
		header("Location: register-candidates.php");
		exit();
	}

		$hash = md5(uniqid());
		$today=date("d/m/Y");

		//sql new registration insert query
	$insertSql = "INSERT INTO users(
    firstname, lastname, email, password, address, city, state,
    contactno, qualification, stream, passingyear, dob, age,
    designation, resume, hash, aboutme, skills, active, creationDate, gender
) VALUES (
    '$firstname', '$lastname', '$email', '$password', '$address', '$city', '$state',
    '$contactno', '$qualification', '$stream', '$passingyear', '$dob', '$age',
    '$designation', '$file', '$hash', '$aboutme', '$skills', 0, '$today', '$gender'
)";
    if ($conn->query($insertSql) === TRUE) {
			// Send Email

			

			require '../autoload.php'; // if using Composer

				$mail = new PHPMailer(true);
				$domain = $_SERVER['HTTP_HOST'];
				$to  = $email;
				$subject = "Activate your account";
				$message = "This email was sent to to verify your email. Your account will not be active unless you verify this email";
				$message .="<br>Please click here to activate your account<br>";
				$message .='<a href="'.$domain.'/job/activate.php?id='.$hash.'">Click here</a><br>';
				$message .="Or copy this link and paste it in your browser<br>";
				$message .=$domain.'/job/activate.php?id='.$hash.'<br>';
				$message.="Use the username: ".$email."<br>";
				$message.="Use tha password: ".$passwords;
					
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

				$mail->AltBody = 'This email was sent to to verify your email. Your account will not be active unless you verify this email';

				$mail->send();
				

			} catch (Exception $e) {
				echo "Email could not be sent. Error: {$mail->ErrorInfo}";
			}

			// //If data inserted successfully then Set some session variables for easy reference and redirect to login
			$_SESSION['registerCompleted'] = true;
			header("Location: allusers.php");
			exit();
		} else {
			//If data failed to insert then show that error. Note: This condition should not come unless we as a developer make mistake or someone tries to hack their way in and mess up :D
			echo "Error " . $sql . "<br>" . $conn->error;
		}
	}else {
		//if email found in database then show email already exists error.
		$_SESSION['registerError'] = true;
		header("Location: register-candidates.php");
		exit();
	}

	//Close database connection. Not compulsory but good practice.
	$conn->close();

} else {
	//redirect them back to register page if they didn't click register button
	header("Location: register-candidates.php");
	exit();
}
function isPasswordStrong($password) {
    return preg_match(
        '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
        $password
    );
}