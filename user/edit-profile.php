<?php
session_start();
if (empty($_SESSION['id_user'])) {
  header("Location: ../index.php");
  exit();
}

require_once("../db.php");
$id_user = $_SESSION['id_user'];

/* ==========================
   FETCH USER
========================== */
$user = $conn->query("SELECT * FROM users WHERE id_user=$id_user")->fetch_assoc();

/* ==========================
   FETCH WORK EXPERIENCE
========================== */
$workExp = [];
$res = $conn->query("SELECT * FROM user_work_experience WHERE id_user=$id_user");
while ($row = $res->fetch_assoc()) {
  $workExp[] = $row;
}

/* ==========================
   FETCH USER SKILLS
========================== */
$userSkills = [];
$res = $conn->query("SELECT skill_id FROM user_skills WHERE id_user=$id_user");
while ($r = $res->fetch_assoc()) {
  $userSkills[] = $r['skill_id'];
}

/* ==========================
   FETCH SKILL DETAILS
========================== */
$skillDetails = '';
$res = $conn->query("SELECT skill_details FROM user_skills WHERE id_user=$id_user LIMIT 1");
if ($res && $res->num_rows > 0) {
  $skillDetails = $res->fetch_assoc()['skill_details'];
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
</style>
</head>

<section class="content">
<div class="container">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-3">
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Welcome <b><?= htmlspecialchars($user['firstname'])."&#128075;"; ?></b></h3>
    </div>
    <div class="box-body no-padding">
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="edit-profile.php"><i class="fa fa-user"></i> Edit Profile</a></li>
        <li><a href="cv.php"><i class="fa fa-file-text"></i> My Resume and Image</a></li>
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
<h2>Edit Profile</h2>

<form action="update-profile.php" method="post" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6">
<div class="form-group">
<label>First Name</label>
<input type="text" name="fname" class="form-control input-lg"
value="<?= htmlspecialchars($user['firstname']) ?>" required>
</div>

<div class="form-group">
<label>Last Name</label>
<input type="text" name="lname" class="form-control input-lg"
value="<?= htmlspecialchars($user['lastname']) ?>" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" class="form-control input-lg"
value="<?= htmlspecialchars($user['email']) ?>" readonly>
</div>

<div class="form-group">
<label>Address</label>
<textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
</div>

<div class="form-group">
<label>Suburb</label>
<input type="text" name="city" class="form-control"
value="<?= htmlspecialchars($user['city']) ?>">
</div>

<div class="form-group">
<label>State</label>
<select name="state" class="form-control">
<?php
$state = ["NSW","QLD","VIC","SA","TAS","WA"];
foreach ($state as $s) {
  $sel3 = ($user['state'] == $s) ? "selected" : "";
  echo "<option value='$s' $sel3>$s</option>";
}
?>
</select>
</div>
</div>

<div class="col-md-6">

<div class="form-group">
<label>Contact Number</label>
<input type="text" name="contactno" class="form-control"
value="<?= htmlspecialchars($user['contactno']) ?>">
</div>

<div class="form-group">
<label>Highest Qualification</label>
<select name="qualification" class="form-control">
<?php
$quals = ["Bachelor","Diploma","Graduate Diploma","Master","Doctoral","Certificate III","Others"];
foreach ($quals as $q) {
  $sel = ($user['qualification'] == $q) ? "selected" : "";
  echo "<option value='$q' $sel>$q</option>";
}
?>
</select>
</div>

<div class="form-group">
<label>Name of academic institution</label>
<input type="text" name="stream" class="form-control"
value="<?= htmlspecialchars($user['stream']) ?>">
</div>

<div class="form-group">
  <label>Graduation year</label>
<select name="gryear" class="form-control">
<?php
$gry = ["2000","2001","2002","2003",	"2004",	"2005",	"2006",	"2007",	"2008",	"2009",	"2010",	"2011",	"2012",	"2013",	"2014",	"2015",	"2016",	"2017",	"2018",	"2019",	"2020",	"2021",	"2022",	"2023",	"2024",	"2025",	"2026",	"2027",	"2028"];
foreach ($gry as $g) {
  $sele = ($user['gryear'] == $g) ? "selected" : "";
  echo "<option value='$g' $sele>$g</option>";
}
?>
</select>
</div>
<div class="form-group">
<label>About Me</label>
<textarea name="aboutme" class="form-control"><?= htmlspecialchars($user['aboutme']) ?></textarea>
</div>
</div>
</div>

<hr>

<h3><i class="fa fa-briefcase"></i> Work Experience</h3>

<div id="work-experience-wrapper">
<?php foreach ($workExp as $i => $exp) { ?>
<div class="work-exp-block">
<input type="text" name="work_experience[<?= $i ?>][title]"
class="form-control" value="<?= htmlspecialchars($exp['job_title']) ?>" placeholder="Job Title"><br>

<div class="row">
<div class="col-md-6">
<label>Start Date</label>
<input type="date" name="work_experience[<?= $i ?>][start]"
value="<?= $exp['start_date'] ?>" class="form-control">
</div>

<div class="col-md-6">
<label>End Date</label>
<input type="date" name="work_experience[<?= $i ?>][end]"
value="<?= $exp['end_date'] ?>"
class="form-control end-date" <?= $exp['is_current'] ? 'disabled' : '' ?>>

<label>
<input type="checkbox"
name="work_experience[<?= $i ?>][current]"
class="current-job" <?= $exp['is_current'] ? 'checked' : '' ?>>
 Currently Working
</label>
</div>
</div><br>

<textarea name="work_experience[<?= $i ?>][description]"
class="form-control"><?= htmlspecialchars($exp['description']) ?></textarea>
</div>
<?php } ?>
</div>

<button type="button" id="add-work" class="btn btn-primary">
<i class="fa fa-plus"></i> Add Work Experience
</button>

<hr>

<h3><i class="fa fa-code"></i> Skills</h3>

<?php
$res = $conn->query("SELECT * FROM skills");
while ($skill = $res->fetch_assoc()) {
$checked = in_array($skill['id'], $userSkills) ? "checked" : "";
?>
<div class="checkbox">
<label>
<input type="checkbox" name="skills[]" value="<?= $skill['id'] ?>" <?= $checked ?>>
<?= htmlspecialchars($skill['skill_name']) ?>
</label>
</div>
<?php } ?>

<div class="form-group">
<label>Skills Details</label>
<textarea name="skills_details" class="form-control"><?= htmlspecialchars($skillDetails) ?></textarea>
</div>

<hr>

<button type="submit" class="btn btn-success btn-lg">Update Profile</button>

</form>
</div>

</div>
</div>
</section>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
let workIndex = <?= count($workExp) ?>;

$('#add-work').click(function () {
$('#work-experience-wrapper').append(`
<div class="work-exp-block">
<input type="text" name="work_experience[${workIndex}][title]"
class="form-control" placeholder="Job Title"><br>

<div class="row">
<div class="col-md-6">
<input type="date" name="work_experience[${workIndex}][start]" class="form-control">
</div>
<div class="col-md-6">
<input type="date" name="work_experience[${workIndex}][end]"
class="form-control end-date">
<label>
<input type="checkbox" name="work_experience[${workIndex}][current]"
class="current-job"> Currently Working
</label>
</div>
</div><br>

<textarea name="work_experience[${workIndex}][description]"
class="form-control"></textarea>
</div>
`);
workIndex++;
});

$(document).on('change', '.current-job', function () {
$(this).closest('.work-exp-block').find('.end-date').prop('disabled', this.checked);
});


</script>
<?php
require_once('footer.php');
?>