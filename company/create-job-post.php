<?php

//To Handle Session Variables on This Page
session_start();

//If user Not logged in then redirect them back to homepage. 
if(empty($_SESSION['id_company'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");
require_once("header.php");

?>

  <script src="../js/tinymce/tinymce.min.js"></script>

  <script>tinymce.init({ selector:'#description', height: 300 });</script>


  
    <section id="candidates" class="content-header">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Welcome <b><?php echo $_SESSION['name']; ?></b></h3>
              </div>
              <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                  <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                  <li><a href="edit-company.php"><i class="fa fa-tv"></i> My Company</a></li>
                  <li class="active"><a href="create-job-post.php"><i class="fa fa-plus"></i> Create Job Post</a></li>
                  <li><a href="my-job-post.php"><i class="fa fa-suitcase"></i> My Job Post</a></li>
                  <li><a href="job-applications.php"><i class="fa fa-file-o"></i> Job Application</a></li>
                  <li><a href="mailbox.php"><i class="fa fa-envelope"></i> Mailbox</a></li>
                  <li><a href="settings.php"><i class="fa fa-gear"></i> Settings</a></li>
                  <li><a href="resume-database.php"><i class="fa fa-user"></i> Resume Database</a></li>
                  <li><a href="../logout.php"><i class="fa fa-arrow-circle-o-right"></i> Logout</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9 bg-white padding-2">
            <h2><i>Create Job Post</i></h2>
            <div class="row">
              <form method="post" action="addpost.php">
                <div class="col-md-12 latest-job ">
                  <div class="form-group">
                    <input class="form-control input-lg" type="text" id="jobtitle" name="jobtitle" placeholder="Job Title">
                  </div>
                  <div class="form-group">
                  Job Link <i class="fa fa-external-link"></i>

                    <input type="text" class="form-control  input-lg" id="apply_link" name="apply_link" placeholder="Job Link">
                  </div>
                  
                  <div class="form-group">
                  

                    <div class="form-group">
                    <label for="state"> Location <i class="fa fa-map-marker"></i></label>
                  <select id="location" name="location" class="form-control" required>
                    <option value="">-- Select State / Territory --</option>

                    <option value="NSW">New South Wales (NSW)</option>

                    <option value="VIC">Victoria (VIC)</option>

                    <option value="QLD">Queensland (QLD)</option>

                    <option value="WA">Western Australia (WA) </option>

                    <option value="SA">South Australia (SA)</option>

                    <option value="TAS">Tasmania (TAS)</option>

                    <option value="ACT">Australian Capital Territory (ACT)</option>

                    <option value="NT">Northern Territory (NT)</option>
                    <option value="Others">Others</option>
                  </select>
                  </div>
                  
                  
                  </div>
                  Job Sub-title <i class="fa fa-building"></i>
                  <div class="form-group">
                    <input type="text" class="form-control  input-lg" id="subtitle" name="subtitle" placeholder="Sub-title" required="">
                  </div>
                <div class="form-group">
                <input type="number" class="form-control  input-lg" id="experience" autocomplete="off" name="experience" placeholder="Experience (in Years) Required" required="">
                  </div>
                <div class="form-group">
                    <input type="text" class="form-control  input-lg" id="qualification" name="qualification" placeholder="Qualification Required" required="">
                </div>
                  More details about the job <i class="fa fa-info-circle"></i>

                  <div class="form-group">
                    <textarea class="form-control input-lg" id="description" name="description" placeholder="Job Description"></textarea>
                  </div>
                  
                  <div class="form-group">
                    <button type="submit" class="btn btn-flat btn-success">Create</button>
                  </div>
                </div>
              </form>
            </div>
            
          </div>
        </div>
      </div>
    </section>

    

  </div>
  <!-- /.content-wrapper -->

  <?php require_once("footer.php");  ?>

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
