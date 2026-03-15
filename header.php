<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Job Portal for AusTech Institute</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/_all-skins.min.css">
  <link rel="stylesheet" href="css/custom.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
  <link rel="icon" type="image/png" href="favicon/favicon-32x32.png">

</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

<header class="main-header">
  <a href="index.php" class="logo logo-bgy">
    <span class="logo-mini"><b>J</b>P</span>
    <span class="logo-lg"><b>Job</b> by AusTech</span>
  </a>

  <nav class="navbar navbar-static-top">
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li><a href="jobs.php">Jobs</a></li>

        <?php if(empty($_SESSION['id_user']) && empty($_SESSION['id_company'])) { ?>
          <li><a href="login.php">Login</a></li>
        <?php } else { ?>
          <?php if(isset($_SESSION['id_user'])) { ?>
            <li><a href="user/index.php">Dashboard</a></li>
          <?php } elseif(isset($_SESSION['id_company'])) { ?>
            <li><a href="company/index.php">Dashboard</a></li>
          <?php } ?>
          <li><a href="logout.php">Logout</a></li>
        <?php } ?>
      </ul>
    </div>
  </nav>
</header>

<div class="content-wrapper" style="margin-left:0px;">
