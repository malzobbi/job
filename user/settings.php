<?php

//To Handle Session Variables on This Page
session_start();

//If user Not logged in then redirect them back to homepage. 
if(empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");
$sqlM = "SELECT * FROM users WHERE id_user='$_SESSION[id_user]'";
                  $resultM = $conn->query($sqlM);

                  if($resultM->num_rows > 0) {
                    while($rowM = $resultM->fetch_assoc()) 
                    {     

                      $firstname=$rowM['firstname'];
                    }
                  }
require_once("header.php");
?>
<style>
  
  label {
    font-weight: bold;
    display: block;
    margin-bottom: 8px;
  }

  input[type="password"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
  }

  .rules li::before {
  content: "❌ ";
}

.rules li.valid::before {
  content: "✅ ";
}

.rules li.invalid::before {
  content: "❌ ";
}

  .valid {
    color: #28a745;
  }

  .valid::before {
    content: "✅ ";
  }

  .invalid {
    color: #dc3545;
  }
</style>

    <section id="candidates" class="content-header">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Welcome <b><?= htmlspecialchars($firstname)."&#128075;";  ?></b></h3>
              </div>
              <div class="box-body no-padding">
                 <ul class="nav nav-pills nav-stacked">
                  <li><a href="edit-profile.php"><i class="fa fa-user"></i> Edit Profile</a></li>
                  <li><a href="cv.php"><i class="fa fa-file-text"></i> My Resume and Image</a></li>
                  <li><a href="index.php"><i class="fa fa-address-card-o"></i> My Applications</a></li>
                  <li><a href="../jobs.php"><i class="fa fa-list-ul"></i> Jobs</a></li>
                  <li><a href="mailbox.php"><i class="fa fa-envelope"></i> Mailbox</a></li>
                  <li class="active"><a href="settings.php"><i class="fa fa-gear"></i> Settings</a></li>
                  <li><a href="../logout.php"><i class="fa fa-arrow-circle-o-right"></i> Logout</a></li>
                
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9 bg-white padding-2">
            <h2><i>Change Password</i></h2>
            <p>Type in new password that you want to use</p>
            <div class="row">
              <div class="col-md-6">
                <form id="changePassword" action="change-password.php" method="post">
                  <div class="form-group">
                
              
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter password *" required>

              <ul class="rules" id="rules">
                <li id="length" class="invalid">Minimum 8 characters</li>
                <li id="upper" class="invalid">At least 1 uppercase letter</li>
                <li id="lower" class="invalid">At least 1 lowercase letter</li>
                <li id="number" class="invalid">At least 1 number</li>
                <li id="special" class="invalid">At least 1 special character</li>
              </ul>
              </div>

                  <div class="form-group">
                    <input id="cpassword" class="form-control input-lg" type="password" autocomplete="off" placeholder="Confirm Password" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-flat btn-success">Change Password</button>
                  </div>
                  <div id="passwordError" class="color-red text-center hide-me">
                    Password Mismatch!!
                  </div>
                  <?php
                    if(isset($_SESSION['registerError'])) {
                  ?>
                  <div id="registerError" class="color-red text-center">
                    Password is NOT complex! <br>Password was not saved
                  </div>
                  <?php
                    unset($_SESSION['registerError']); }
                  ?>      

                </form>
              </div>
              <div class="col-md-6">
                <form action="deactivate-account.php" method="post">
                  <label><input type="checkbox" required> I Want To Deactivate My Account</label>
                  <button type="submit" class="btn btn-danger btn-flat btn-lg">Deactivate My Account</button>
                </form>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>

    

  </div>
  <!-- /.content-wrapper -->
<?php require_once('footer.php'); ?>

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
  $("#changePassword").on("submit", function(e) {
    e.preventDefault();
    if( $('#password').val() != $('#cpassword').val() ) {
      $('#passwordError').show();
    } else {
      $(this).unbind('submit').submit();
    }
  });
</script>
<script>
const passwordInput = document.getElementById("password");
const rulesBox = document.getElementById("rules");

const rules = {
  length: pwd => pwd.length >= 8,
  upper: pwd => /[A-Z]/.test(pwd),
  lower: pwd => /[a-z]/.test(pwd),
  number: pwd => /\d/.test(pwd),
  special: pwd => /[\W_]/.test(pwd)
};

passwordInput.addEventListener("blur", () => {
  rulesBox.style.display = "block";
  validatePassword();
});

passwordInput.addEventListener("input", validatePassword);

function validatePassword() {
  const pwd = passwordInput.value;

  Object.keys(rules).forEach(rule => {
    const element = document.getElementById(rule);
    if (rules[rule](pwd)) {
      element.classList.add("valid");
      element.classList.remove("invalid");
    } else {
      element.classList.add("invalid");
      element.classList.remove("valid");
    }
  });
}
</script>


</body>
</html>