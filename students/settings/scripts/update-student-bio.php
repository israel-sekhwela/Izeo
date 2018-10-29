<?php
session_start();
include "database-connection.php";

if(isset($_POST["student"]))
{
	$student = $_POST["student"];
    $student = stripslashes($student);
	$student = mysqli_real_escape_string($dbcon, $student);
}

if(isset($_POST["bio"]))
{
	$bio = $_POST["bio"];
    $bio = stripslashes($bio);
	$bio = mysqli_real_escape_string($dbcon, $bio);
}

$sql = "UPDATE tbl_student SET bio = '$bio' WHERE student = '$student'";

$result = mysqli_query($dbcon, $sql);

if(mysqli_query($dbcon, $sql))
{
	header("Location: ../account.php");
    mysqli_query($dbcon, "INSERT INTO activities (student, activity) VALUES ('$student', '$student just updated their bio')");
}
else
{
	echo mysqli_error($dbcon);
}
?>