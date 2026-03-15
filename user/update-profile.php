<?php
session_start();
require_once("../db.php");

$id_user = $_SESSION['id_user'];
echo $_POST['qualification'];
/* -----------------------------
   BASIC PROFILE UPDATE
------------------------------ */
$stmt = $conn->prepare("
UPDATE users SET 
firstname=?, lastname=?, address=?, city=?, state=?, contactno=?, qualification=?, stream=?,passingyear=?, aboutme=?
WHERE id_user=?
");

$stmt->bind_param(
  "ssssssssssi",
  $_POST['fname'],
  $_POST['lname'],
  $_POST['address'],
  $_POST['city'],
  $_POST['state'],
  $_POST['contactno'],
  $_POST['qualification'],
  $_POST['stream'],
  $_POST['gryear'],
  $_POST['aboutme'],
  $id_user
);
$stmt->execute();

/* -----------------------------
   WORK EXPERIENCE (RESET & INSERT)
------------------------------ */
$conn->query("DELETE FROM user_work_experience WHERE id_user=$id_user");

if (!empty($_POST['work_experience'])) {
  foreach ($_POST['work_experience'] as $exp) {
    if (empty($exp['title'])) continue;

    $is_current = empty($exp['end']) ? 1 : 0;
    $end_date = $is_current ? NULL : $exp['end'];

    $stmt = $conn->prepare("
      INSERT INTO user_work_experience 
      (id_user, job_title, start_date, end_date, is_current, description)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
      "isssis",
      $id_user,
      $exp['title'],
      $exp['start'],
      $end_date,
      $is_current,
      $exp['description']
    );
    $stmt->execute();
  }
}

/* -----------------------------
   SKILLS (RESET & INSERT)
------------------------------ */
$conn->query("DELETE FROM user_skills WHERE id_user=$id_user");

if (!empty($_POST['skills'])) {
  foreach ($_POST['skills'] as $skill_id) {
    $stmt = $conn->prepare("
      INSERT INTO user_skills (id_user, skill_id, skill_details)
      VALUES (?, ?, ?)
    ");
    $stmt->bind_param(
      "iis",
      $id_user,
      $skill_id,
      $_POST['skills_details']
    );
    $stmt->execute();
  }
}

header("Location: edit-profile.php");
exit();
?>