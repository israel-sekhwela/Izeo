<?php
session_start();
include "database-connection.php";

if(isset($_POST["student"]))
{
	$student = $_POST["student"];
    $student = stripslashes($student);
	$student = mysqli_real_escape_string($dbcon, $student);
}

if(isset($_POST["oldpass"]))
{
	$oldpass = $_POST["oldpass"];
    $oldpass = stripslashes($oldpass);
	$oldpass = mysqli_real_escape_string($dbcon, $oldpass);
}

if(isset($_POST["newpass"]))
{
	$newpass = $_POST["newpass"];
    $newpass = stripslashes($newpass);
	$newpass = mysqli_real_escape_string($dbcon, $newpass);
}


$sql = "UPDATE tbl_student SET password = '$newpass' WHERE student = '$student' AND password = '$oldpass'";

$result = mysqli_query($dbcon, $sql);


if(mysqli_query($dbcon, $sql))
{
	header("Location: ../account.php");
}
else
{
	echo mysqli_error($dbcon);
}
?>