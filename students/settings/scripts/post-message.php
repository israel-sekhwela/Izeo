<?php
include 'database-connection.php';

if(isset($_POST["student"]))
{
	$student = $_POST["student"];
}

if(isset($_POST["post"]))
{
	$post = $_POST["post"];
    $post = stripslashes($post);
    $post = mysqli_real_escape_string($dbcon, $post);
}

$sql = "INSERT INTO tbl_posts (student, post) VALUES ('$student', '$post')";

if(mysqli_query($dbcon, $sql))
{
	header("Location: ../timeline.php");
}
else
{
	echo mysqli_error($dbcon);
}
?>