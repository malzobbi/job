<?php
session_start();
$error1=false;

//If user Not logged in then redirect them back to homepage. 
//This is required if user tries to manually enter view-job-post.php in URL.

require_once("header.php");

?>
    <section id="candidates" class="content-header">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="box box-solid">
               <?php
        if($_GET['error']==1 or $_GET['error']==2) {
            echo "An error has occured, this account is inactive";
        }else if($_GET['error']==0) {
            echo "You may check your provided email, and use the username and password provided.";
        
        }else if($_GET['error']==3) {
            echo "An error has occured, no email has been sent.";
        
        }else if($_GET['error']==4 or $_GET['error']==5) {
            echo "This email is not available";
        }
?>
          </div>
          
           
            
          </div>
        </div>
      </div>
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
</body>
</html>
