<?php
    session_start();
    include 'scripts/database-connection.php';

    if(!isset($_SESSION["student"]))
    {
        header("Location: login.php");
    }

    $student = $_SESSION["student"];

    $sql = "SELECT * FROM tbl_student WHERE student = '$student'";
    $sresult = mysqli_query($dbcon, $sql);
    $srow = mysqli_fetch_assoc($sresult);

    $year = $srow["yeargroup"];

    $sqlq = "SELECT * FROM friends_requests JOIN tbl_student WHERE friends_requests.to_student = '$student'";
    $fqresult = mysqli_query($dbcon, $sqlq);
    $grow = mysqli_fetch_assoc($fqresult);

    $sqlmessage = "SELECT * FROM inbox_messages WHERE to_student = '$student'";
    $mresult = mysqli_query($dbcon, $sqlmessage);
    $messagecount = mysqli_num_rows($mresult);

    $sqlrequests = "SELECT * FROM friends_requests WHERE to_student = '$student'";
    $reqresult = mysqli_query($dbcon, $sqlrequests);
    $requestcount = mysqli_num_rows($reqresult);

    $sqlass = "SELECT * FROM assignments ORDER BY dateset DESC";
    $assresult = mysqli_query($dbcon, $sqlass);

    $sqlshare = "SELECT * FROM tbl_shared_files ORDER BY dateshared DESC";
    $shareresult = mysqli_query($dbcon, $sqlshare);
    $sharecount = mysqli_num_rows($shareresult);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>WorkRoom</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../img/fav.ico" type="image/x-icon">
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
					<!-- <a href="../index.php" class="navbar-brand"> Izeo </a> -->
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
        
        <div class="container">
            <div id="requests-section">
                <h1>Work Room</h1>
                <hr />
                
                <h2>Collaborative Work</h2>
                <p>Here you can work on the same project in groups, as well as share files with other students.</p>
                
                <button type="button" data-toggle="modal" data-target="#file" title="Upload your work for others to see" class="button" style="width:200px;"><span class="glyphicon glyphicon-upload"></span> Upload</button> <br /><br />
                
                <div id="share-table">
                    <h2><?php echo $sharecount ?> Files Uploaded</h2>
                    <?php

                echo "<table id='sharetbl' class='table table-hover'>
                <thead>
                <tr>

                <th>Student</th>
                <th>Description</th>
                <th>Filename</th>
                <th>Date Shared</th>

                </tr>
                </thead>";
                    if (mysqli_num_rows($shareresult) > 0)
                    {
                        while ($sharerow = mysqli_fetch_assoc($shareresult))
                        {
                            echo "<tr>";
                            echo "<td><b><a href=profile.php?student=". $sharerow["student"] . ">" . $sharerow["student"] . "</a></b></td>";
                            echo "<td><b>" . $sharerow["description"] . "</b></td>";
                            echo "<td><a href=../sharedfiles/". $sharerow["filename"] . ">" . $sharerow["filename"] . "</a></td>";
                            echo "<td><b>" . $sharerow["dateshared"] . "</b></td>";
                            echo "</tr>";
                        }

                    }
                    echo "</table>";
            ?>
                
                </div>
                <!-- Upload Files Popup -->
                <div id="file" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Upload File</h4>
                            </div>
                            <div class="modal-body">
                                <form action="" name="shareform" method="POST" onsubmit="return CheckFileDescription()" enctype="multipart/form-data">
                                    <div class="form-group">
                                    <input type="hidden" class="form-control" name="student" value="<?php echo $_SESSION["student"]; ?>">
                                    </div>
                                    <img src="icons/file.svg" class="img-circle" alt="doc" width="120px" height="120px">
                                    <div class="form-group">
                                    <input type="file" name="my_file" id="fileToUpload">
                                    <div class="form-group">
                                    <label for="description">File Description</label>
                                    <input type="text" class="form-control" name="description" />
                                    <p id="desval" style="text-align:center;"></p>
                                    </div>
                                    <input type="submit" class="button" value="Share" name="files">
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

                if(isset($_POST["files"]))
                {
                    
                    $student = $_POST["student"];

                    if ($_FILES["my_file"]["type"] != "file/exe")
                    {   
                        if (move_uploaded_file($_FILES["my_file"]["tmp_name"], "files/".$_FILES["my_file"]["name"]))
                        {
                            $doc = $_FILES["my_file"]["name"];
                            $doc = mysqli_real_escape_string($dbcon, $doc);
                            
                            $description = $_POST["description"];
                            $description = mysqli_real_escape_string($dbcon, $description);

                            $sqldoc = "INSERT INTO tbl_shared_files (student, filename, description) VALUES ('$student', '$doc', '$description')";

                            mysqli_query($dbcon, $sqldoc);
                            
                            echo "<script type='text/javascript'>location.href = 'index.php';</script>";

                            mysqli_query($dbcon, "INSERT INTO posts (student, postto, post) VALUES ('$student', '$student', I just shared a file of work!')");

                            mysqli_query($dbcon, "INSERT INTO activities (student, activity) VALUES ('$student', '$student shared a file called $description')");
                            
                            mysqli_query($dbcon, "UPDATE tbl_student SET engagement = engagement + 4 WHERE student = '$student'");

                        }
                        else
                        {
                            echo "<h3>Error uploading your file.</h3>";
                            echo "<br /><img src=img/confused.svg height='100' width='100' class='img-responsive center-block' alt='confused_face'/>";
                            
                        }
                    }
                    else
                    {
                        echo "<h3>Error uploading your file.</h3>";
                        echo "<br /><img src=img/confused.svg height='100' width='100' class='img-responsive' alt='confused_face'/>";
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