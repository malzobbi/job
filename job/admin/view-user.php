<?php

//To Handle Session Variables on This Page
session_start();

if(empty($_SESSION['id_admin'])) {
  header("Location: ../index.php");
  exit();
}


//Including Database Connection From db.php file to avoid rewriting in all files
require_once("../db.php");


  
$sql1 = "SELECT * FROM users WHERE id_user='$_GET[id]'";
$result1 = $conn->query($sql1);
if($result1->num_rows > 0) 
{
  $row = $result1->fetch_assoc();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Job Portal</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/AdminLTE.min.css">
  <link rel="stylesheet" href="../css/_all-skins.min.css">
  <!-- Custom -->
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="icon" type="image/png" href="../favicon/favicon-32x32.png">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.disabled {
    pointer-events: none;
    opacity: 0.5; /* optional visual effect */
}

</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="index.php" class="logo logo-bg">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>J</b>P</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Job</b> Portal</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
                  
        </ul>
      </div>
    </nav>
  </header>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left: 0px;">

  <section id="candidates" class="content-header">
    <div class="container">
      <div class="row">

        <!-- LEFT SIDE CONTENT (9 columns) -->
        <div class="col-md-9 bg-white padding-2">
          <?php
              if($row['active']=='1'){
            ?>
          <div class="pull-left">
            
            <h2><b><i><?php echo $row['firstname']." ".$row['lastname']; ?></i></b></h2>
            <h5><b><i class="fa fa-envelope"><?php echo " ".$row['email']." || ".'<i class="fa fa-phone">'." ".$row['contactno']; ?></i></i></b></h5>

          </div>
            <?php
              }else{
            ?>
            <div class="disabled" >
            
            <h2><b><i><?php echo $row['firstname']." ".$row['lastname']; ?>, [account is disabled]</i></b></h2>
            <h5><b><i class="fa fa-envelope"><?php echo " ".$row['email']." || ".'<i class="fa fa-phone">'." ".$row['contactno']; ?></i></i></b></h5>

          </div>


              <?php
              }
              ?>
          <div class="pull-right">
            <a href="allusers.php" class="btn btn-default btn-lg btn-flat margin-top-20">
              <i class="fa fa-arrow-circle-left"></i> Back
            </a>
          </div>

          <div class="clearfix"></div>
          <hr>

          <!-- Basic Info -->
          <p>
            <span class="margin-right-10">
              <i class="fa fa-location-arrow text-green"></i> Address: <?php echo $row['address']." ".$row['city']." ".$row['state']; ?>
            </span>

            <i class="fa fa-calendar text-green"></i> Creation Date: <?php echo $row['creationDate']; ?>
          </p>

          <!-- Qualifications -->
          <p>
            <i class="fa fa-suitcase"></i> Qualifications:
            <?php echo stripcslashes($row['qualification']); ?>
          </p>

          <p>
            <i class="fa fa-graduation-cap"></i> Graduation Year:
            <?php echo $row['passingyear']; ?>
          </p>

          <hr>

          <!-- Skills -->
          <p>
            <i class="fa fa-certificate"></i> Skills:
            <?php echo stripcslashes($row['skills']); ?>
          </p>

          <p>
            <i class="fa fa-star"></i> Designation:
            <?php echo $row['designation']; ?>
          </p>

          <hr>

          <!-- About Me -->
          <p>
            <i class="fa fa-user"></i> About me:
            <?php echo stripcslashes($row['aboutme']); ?>
          </p>

          <!-- Resume -->
          <p>
            <i class="fa fa-file"></i> My Resume:
            <?php if($row['resume']==""){
                echo "No Resume has been uploaded!";
            }else{
              ?>
           <a href="../user/resume/<?php echo $row['resume']; ?>">Download</a>
           <?php
            }
           ?>
          </p>

        </div>

        <!-- RIGHT SIDE IMAGE (3 columns) -->
        <div class="col-md-3">
          <div class="thumbnail">
            <?php
              if( ($row['avatar']==='' || $row['avatar']===null) &&  $row['gender']==2){
            ?>
                        <img src="../user/avatar/female.png" alt="my image" class="img-responsive">
            <?php
              }else if(($row['avatar']==='' || $row['avatar']===null) &&  $row['gender']==1){
            ?>
                          <img src="../user/avatar/male.png" alt="my image" class="img-responsive">
              <?php
              }else if(($row['avatar']==='' || $row['avatar']===null) &&  $row['gender']>2) {

              ?>
                          <img src="../user/avatar/others.png" alt="my image" class="img-responsive">
              <?php 
              }else if($row['avatar']==='' &&  ($row['gender'] === NULL || trim($row['gender']) === '')) {

              ?>
                          <img src="../user/avatar/others.png" alt="my image" class="img-responsive">
              <?php 
              }else{ 
              ?>
            <img src="../user/avatar/<?php echo $row['avatar']; ?>" alt="my image" class="img-responsive">
            <?php 
              }
            ?>
            <div class="caption text-center">
                <?php echo "Date of Birth:".$row['dob']; ?>

                <?php if($row['gender']==1){echo "<br>Male";}else if ($row['gender']==2){echo "<br>Female";}else{echo "<br>Others";}; ?>

            </div>
          </div>
        </div>

      </div> <!-- /row -->
    </div> <!-- /container -->
  </section>

</div>
<!-- /.content-wrapper -->
 <?php require_once("footer.php"); ?>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../js/adminlte.min.js"></script>
</body>
</html>
