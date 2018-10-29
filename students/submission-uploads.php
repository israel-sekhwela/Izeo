<?php
session_start();
include 'scripts/database-connection.php';

if(!isset($_SESSION["student"]))
{
	header("Location: login.php");
}

$student = $_SESSION["student"];

$asid = $_GET["asid"];

$sql = "SELECT * FROM tbl_student WHERE student = '$student'";
$sresult = mysqli_query($dbcon, $sql);
$srow = mysqli_fetch_assoc($sresult);

$sqlmessage = "SELECT * FROM inbox_messages WHERE to_student = '$student'";
$mresult = mysqli_query($dbcon, $sqlmessage);
$messagecount = mysqli_num_rows($mresult);

$sqlrequests = "SELECT * FROM friends_requests WHERE to_student = '$student'";
$reqresult = mysqli_query($dbcon, $sqlrequests);
$requestcount = mysqli_num_rows($reqresult);

$sqlrep = "SELECT * FROM assignments JOIN assignments_uploads ON assignments.asid = assignments_uploads.asid WHERE assignments.asid = '$asid'";
$represult = mysqli_query($dbcon, $sqlrep);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submission Uploads</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <link rel="stylesheet" href="css/student-style.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/message-validation.js"></script>
    
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
                        <li><a href="project.php"><span class='glyphicon glyphicon-file'></span> Projects</a></li>
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
        
        <div class="container text-center">
            <div id="messages-section">
                <h1>Submission</h1>
                <h3>Upload file</h3>
                <img src="icons/doc.svg" height="100px" width="100px" alt="replyicon" /> <br /><br />
                
                 <button type="button" data-toggle="modal" data-target="#file" title="Post a piece of work" class="button" style="width:150px;"><span class="glyphicon glyphicon-upload"></span> Upload</button> <br /><br />
                
                  <div id="file" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Upload File</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" name="submitform" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                <input type="hidden" class="form-control" name="asid" value="<?php echo $asid ?>">
                                </div>
                                <img src="icons/file.svg" class="img-circle" alt="doc" width="120px" height="120px">
                                <div class="form-group">
                                <input type="file" name="my_file" id="fileToUpload">
                                <input type="submit" class="button" value="Submit File" name="submitwork">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Close</button>
                        </div>
                    </div>
                </div>
            </div>
           
                <?php
                include 'scripts/database-connection.php';
                // error_resporting();
                if(isset($_POST["submitwork"]))
                {
        
                    if ($_FILES["my_file"]["type"] != "file/exe")
                    {   
                        if (move_uploaded_file($_FILES["my_file"]["tmp_name"],"../submissions/".$_FILES["my_file"]["name"]))
                        {
                            $doc = $_FILES["my_file"]["name"];
                            $doc = mysqli_real_escape_string($dbcon, $doc);

                            $sqldoc = "INSERT INTO assignments_uploads (asid, filename, student) VALUES ('$asid', '$doc', '$student')";

                            mysqli_query($dbcon, $sqldoc);
                            
                            echo "<script type='text/javascript'>location.href = 'project.php';</script>";
                            
                            mysqli_query($dbcon, "INSERT INTO activities (student, activity) VALUES ('$student', '$student has just submitted their module: $asid assignment')");
                            
                            mysqli_query($dbcon, "UPDATE tbl_student SET engagement = engagement + 5 WHERE student = '$student'");

                        }
                        else
                        {
                            echo "<h3>Error submitting your work.</h3>";
                            
                        }
                    }
                    else
                    {
                        echo "<h3>Exe file not supported, try again!.</h3>";
                    }
                }
            ?>
            </div>
            
              
        </div>
                
        <footer>
            <i><img src="../img/logo.png" width="80"></i>
            <span class="copyright" align-text = "center" style="color:black;">&copy; <h9 style="color:black;"><strong> 2018. IMY220 Project.</strong></h9></span>
        </footer>
        
	</body>
</html>