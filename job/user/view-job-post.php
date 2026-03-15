<?php

//To Handle Session Variables on This Page
session_start();

if(empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}


//Including Database Connection From db.php file to avoid rewriting in all files
require_once("../db.php");

$sql = "SELECT * FROM apply_job_post WHERE id_user='$_SESSION[id_user]' AND id_jobpost='$_GET[id]'";
$result = $conn->query($sql);
if(!$result->num_rows > 0) 
{
  
  $sql1 = "SELECT * FROM job_post INNER JOIN company ON job_post.id_company=company.id_company WHERE id_jobpost='$_GET[id]'";
  $result1 = $conn->query($sql1);
  if($result1->num_rows > 0) 
  {
    $row = $result1->fetch_assoc();
  }

} else {
  header("Location: index.php");
  exit();
}
require_once("header.php");
?>

<form id="applyForm" method="post" action="apply.php">
  <input type="hidden" name="job_id" value="<?php echo (int)$_GET['id']; ?>">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0px;">

    <section id="candidates" class="content-header">
      <div class="container">
        <div class="row">          
          <div class="col-md-9 bg-white padding-2">
            <div class="pull-left">
              <h2><b><i><?php echo $row['jobtitle']; ?></i></b></h2>
               <h4><b><i><?php echo $row['subtitle']; ?></i></b></h4>
            </div>
            <div class="pull-right">
              <a href="#" class="btn btn-default btn-lg btn-flat margin-top-20" onclick="history.back()"><i class="fa fa-arrow-circle-left"></i> Back</a>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div>
              <p><span class="margin-right-10"><i class="fa fa-location-arrow text-green"></i> <?php echo $row['location']; ?></span> <i class="fa fa-calendar text-green"></i> <?php echo date("d-M-Y", strtotime($row['createdat'])); ?></p>              
            </div>
            <div>
              <?php echo stripcslashes($row['description']); ?>
            </div>
            
            
          </div>
         <div class="col-md-3">
            <div class="thumbnail advertiser-details">
              <input value="Apply for this job"  class="btn btn-green-gradient" type="submit" onclick="submitF()" />
              <br>
              <div class="adv-header">
                
                <h4>Advertiser details</h4>
                                  
              </div>
              <img src="../uploads/logo/<?php echo $row['logo']; ?>" alt="companylogo">

    <div class="caption text-center">
     <div class="company-details">

  <h3>
    <i class="fa fa-building"></i>
    <?php echo $row['companyname']; ?>
  </h3>

  <p>
    <i class="fa fa-user"></i>
    <?php echo $row['name']; ?>
  </p>

  <p>
    <i class="fa fa-envelope"></i>
    <?php echo $row['email']; ?>
  </p>

  <p>
    <i class="fa fa-globe"></i>
    <a href="<?php echo $row['website']; ?>" target="_blank">
      <?php echo $row['website']; ?>
    </a>
  </p>

  <p>
    <i class="fa fa-map-marker"></i>
    <?php echo $row['country']; ?>,
    <?php echo $row['state']; ?>,
    <?php echo $row['city']; ?>
  </p>

  <hr>

</div>

     
    </div>

  </div>
</div>

            </div>
          </div>
        </div>
      </div>
    </section>

    

  </div>
  <!-- /.content-wrapper -->

 require_once("footer.php");

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
<script>
function submitF() {

  if (!confirm("Do you want to apply for this job?")) {
    return;
  }

  // submit form
  document.getElementById("applyForm").submit();

  // open external link FIRST
  
  window.open(
    "<?php echo htmlspecialchars($row['apply_link'], ENT_QUOTES); ?>",
    "_blank",
    "noopener,noreferrer"
  );

  
  
}
</script>

  
</form>




</body>
</html>
