<?php
session_start();
if (empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}
$theFile="";$theImage="";
require_once("../db.php");
$id_user = $_SESSION['id_user'];

/* ==========================
   FETCH USER
========================== */
$result = $conn->query("SELECT * FROM users WHERE id_user = $id_user");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $theFile=$row['resume'];
    $theImage=$row['avatar'];
    $firstname=$row['firstname'];
} 

require_once('header.php');

?>

<style>
.work-exp-block {
  background: #f9f9f9;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 4px;
}
.main-header .navbar {
  border-bottom: none !important;
}

.main-header .logo {
  border-bottom: none !important;
}
.colored-line {
  border: none; /* Removes the default border and shading */
  height: 3px; /* Sets the thickness of the line */
  background-color: green; /* Sets the color of the line */
  width: 100%; /* Optional: sets the width to 50% */
  margin: 20px auto; /* Optional: centers the line and adds spacing */
}
</style>
</head>

<section class="content">
<div class="container">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-3">
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Welcome <b><?= htmlspecialchars($firstname)."&#128075;";  ?></b></h3>
    </div>
    <div class="box-body no-padding">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="edit-profile.php"><i class="fa fa-user"></i> Edit Profile</a></li>
        <li class="active"><a href="cv.php"><i class="fa fa-file-text"></i> My Resume and Image</a></li>
        <li><a href="index.php"><i class="fa fa-address-card-o"></i> My Applications</a></li>
        <li><a href="../jobs.php"><i class="fa fa-list-ul"></i> Jobs</a></li>
        <li><a href="mailbox.php"><i class="fa fa-envelope"></i> Mailbox</a></li>
        <li><a href="settings.php"><i class="fa fa-gear"></i> Settings</a></li>
        <li><a href="../logout.php"><i class="fa fa-arrow-circle-o-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="col-md-9">
<h2>My Resume and Image</h2>


<div class="row">

<form action="upload-file.php" method="post" enctype="multipart/form-data">
<div class="col-md-6">
<div class="form-group">
<label>Upload Resume</label><br>
<input type="file" name="upload_file" class="form-control" required>
Upload only files of (doc, pdf, docx,odt,html,rtf)
</div>
<hr>
<button type="submit" name="submit" class="btn btn-success btn-lg">Save Resume</button>
</div>

</form>

<form action="upload-image.php" method="post" enctype="multipart/form-data">
<div class="col-md-6">
<div class="form-group">
<label>Upload Profile Image</label><br>

<input type="file" name="profile_image" class="form-control">
Upload only images of (jpg, gif, png, avif)
</div>
<hr>
<button type="submit" name="submit2" class="btn btn-success btn-lg">Save Image</button>
</div>

</div>

</form>
<hr class="colored-line">
<br>
<?php
  if($theFile==""){
    echo "No Resume uploaded";
  }else{
    
    echo 'This is your current resume: <a href="resume/'.$theFile.'">Your resume</a>';

  }


?>
&emsp;&emsp;&emsp;&emsp;&emsp;
<?php
  if($theImage==""){
    echo "No Image uploaded";
  }else{
    
    echo '<br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;This is your current image: <img src="avatar/'.$theImage.'" alt="Your Image" width="400" height="300"/>';


  }


?>
</div>

</div>

</div>

</section>

</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php require_once('footer.php'); ?>