<?php

session_start();

require_once("db.php");
$isApplied=0;
$limit = 4;

if(!empty($_SESSION['id_user'])) {
  $id_user=$_SESSION['id_user'];
}

if(isset($_GET["page"])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$start_from = ($page-1) * $limit;

$sql = "SELECT * FROM job_post LIMIT $start_from, $limit";
$result = $conn->query($sql);
if($result->num_rows > 0) {
 

	while($row = $result->fetch_assoc()) {
     //check if the user has already applied for this job or not
     $theCompanyID= $row['id_company'];
     $sql2 = "SELECT * FROM apply_job_post WHERE id_user=$id_user AND id_jobpost='$theCompanyID'";
              $result2 = $conn->query($sql2);
           
              if($result2->num_rows > 0) {
                 $row2 = $result2->fetch_assoc();
               
               
                $isApplied=1;
                
              }else{
                $isApplied=0;
              }
		$sql1 = "SELECT * FROM company WHERE id_company='$row[id_company]'";
              $result1 = $conn->query($sql1);
              if($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) 
                {
             ?>

		   <div class="attachment-block job-item clearfix">

              <img class="attachment-img" src="uploads/logo/<?php echo $row1['logo']; ?>" alt="Attachment Image">
              <div class="attachment-pushed">
                <h4 class="attachment-heading"><a href="view-job-post.php?id=<?php echo $row['id_jobpost']; ?>"><?php echo $row['jobtitle']; ?></a> <span class="attachment-heading pull-right"><?php echo $row['subtitle']; ?></span></h4>
                <div class="attachment-text">
                    <div><strong><?php echo $row1['companyname']; ?> | <?php echo $row['location']; ?> | Experience <?php echo $row['experience']; ?> Years</strong></div>
                    <div><?php if($isApplied==1){echo "Already applied for this job";}else{echo "New to you";}; ?></div>

                </div>
              </div>
            </div>

		<?php
			}
		}
	}
}

$conn->close();