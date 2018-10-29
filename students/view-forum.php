<?php
session_start();
include 'scripts/database-connection.php';

if(!isset($_SESSION["student"]))
{
	header("Location: login.php");
}

$student = $_SESSION["student"];

$fid = $_GET["forumid"];

$sql = "SELECT * FROM tbl_student WHERE student = '$student'";
$sresult = mysqli_query($dbcon, $sql);
$srow = mysqli_fetch_assoc($sresult);

$sqlq = "SELECT * FROM friends_requests JOIN tbl_student WHERE friends_requests.to_student = '$student'";
$fqresult = mysqli_query($dbcon, $sqlq);
$grow = mysqli_fetch_assoc($fqresult);

$from_student = $grow["from_student"];
$to_student = $grow["to_student"];

$sqlmessage = "SELECT * FROM inbox_messages WHERE to_student = '$student'";
$mresult = mysqli_query($dbcon, $sqlmessage);
$messagecount = mysqli_num_rows($mresult);

$sqlrequests = "SELECT * FROM friends_requests WHERE to_student = '$student'";
$reqresult = mysqli_query($dbcon, $sqlrequests);
$requestcount = mysqli_num_rows($reqresult);

$sqlrep = "SELECT * FROM tbl_forum WHERE forumid = '$fid'";
$frepresult = mysqli_query($dbcon, $sqlrep);
$frow = mysqli_fetch_assoc($frepresult);

$sqlre = "SELECT * FROM tbl_forum JOIN tbl_forum_replies ON tbl_forum.forumid = tbl_forum_replies.forumid WHERE tbl_forum_replies.forumid = '$fid' ORDER BY replydate DESC";
$replies = mysqli_query($dbcon, $sqlre);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $frow["title"]; ?> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <link rel="stylesheet" href="css/student-style.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/login-validation.js"></script>
    <script src="js/utilities.js"></script>
    <style>
        body
        {
            background: url(../img/splash.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        footer
        {
            background-color: #25B99A;
            left: 0;
            bottom: 0;
            text-align: center;
            color: #FFFFFF;
            overflow: hidden;
            position: fixed;
        }
    </style>
</head>
    <body>
        
        <nav class="navbar navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						MENU                        
					</button>
					<!-- <a href="../index.php" class="navbar-brand"> learnbe </a> -->
				</div>
				<div class="collapse navbar-collapse" id="navbar">
					<ul class="nav navbar-nav">
                    <li><a href="timeline.php"><span class='glyphicon glyphicon-home'></span> Izeo</a></li>
						<li><a href="timeline.php"><span class='glyphicon glyphicon-transfer'></span> Timeline</a></li>
						<li><a href="workroom.php"><span class='glyphicon glyphicon-book'></span> Work Room</a></li>
                        <li><a href="project.php"><span class='glyphicon glyphicon-file'></span> Project</a></li>
						<li><a href="newsfeed.php"><span class='glyphicon glyphicon-globe'></span> News Feed</a></li>
                        <li><?php echo "<a href=profile.php?student=" . $srow["student"] . "><span class='glyphicon glyphicon-user'></span> Profile</a>"; ?></li>
				        
					</ul>
					<ul class="nav navbar-nav navbar-right">
                       <li><a href="friend-requests.php"><span class="glyphicon glyphicon-plus"></span> Friend Requests(<?php echo $requestcount; ?>)</a></li>
                        <li><a href="messages.php"><span class="glyphicon glyphicon-envelope"></span> Messages(<?php echo $messagecount; ?>)</a></li>
                        <li><a href="settings/account.php"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                        <li><?php echo "<a href='scripts/logout-student.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a>"; ?></li>
					</ul>
				</div>
			</div>
		</nav>
        
        <div class="container">
            <div id="requests-section">
                <h1><strong><u><?php echo $frow["subject"]; ?></u></strong></h1>
                <h2><?php echo $frow["title"]; ?></h2>
                <br />
                <h4><?php echo $frow["student"]; ?> asked "<?php echo $frow["body"]; ?>"</h4>
                <br />
                <h4><?php echo $frow["date"]; ?>.</h4>
                <br />
               
           <?php echo "<a href=forum-replies.php?forumid=" . $fid . "><span class='glyphicon glyphicon-comment'></span> Reply to forum post</a>"; ?>
                
                  <?php
                        while($row = mysqli_fetch_assoc($replies))
                        {
                            echo "<hr />" .
                            "<h3><strong>" . $row["replystudent"] . " answered: </strong></h3>" .
                            "<p><strong>" . $row["replybody"] . "</strong></p>" .
                            "<p>" . $row["replydate"] . "</p>" .
                            "<hr />";
                        }
                    ?>
            </div>
            <a href="newsfeed.php"><button type="button" style="width:220px;" class="button"><span class="glyphicon glyphicon-arrow-left"></span> Back</button></a>
        </div>
                
        <footer>
            <i><img src="../img/logo.png" width="80"></i>
            <span class="copyright" align-text = "center" style="color:black;">&copy; <h9 style="color:black;"><strong> 2018. IMY220 Project.</strong></h9></span>
        </footer>
        
	</body>
</html>