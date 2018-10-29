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

$sqlorder = "SELECT * FROM posts JOIN tbl_student ON posts.student = tbl_student.student WHERE FIND_IN_SET('$student', friends) ORDER BY dateposted DESC";
$presult = mysqli_query($dbcon, $sqlorder);

$sqlmessage = "SELECT * FROM inbox_messages WHERE to_student = '$student'";
$mresult = mysqli_query($dbcon, $sqlmessage);
$messagecount = mysqli_num_rows($mresult);

$sqlrequests = "SELECT * FROM friends_requests WHERE to_student = '$student'";
$reqresult = mysqli_query($dbcon, $sqlrequests);
$requestcount = mysqli_num_rows($reqresult);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Timeline</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="30"/>
    
    <link rel="shortcut icon" href="../img/fav.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <link rel="stylesheet" href="css/student-style.css">
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/post-validation.js"></script>

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
                    <!-- <a href="timeline.php" class="navbar-brand"> <img src="../img/logo.png" width="90" class="logo-img"/> </a> -->
                    
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
            <div id="timeline-section">
                <div id="search-bar">
                    <form action="post-search.php" method="GET">
                        <input type="text" style="float:right;" name="query" placeholder="Search posts..." />
                    </form>
                </div>
                <h1>Notifications</h1>
                 <a href="notifications.php"><button type="button" style="width:220px;" class="button"><span class="glyphicon glyphicon-th-list"></span> Notifications</button></a>
                
                    <div id="post-bar">
                          <form action="scripts/post-message.php" name="postform" onsubmit="return CheckPost()" method="POST">
                        <input type="hidden" name="student" value="<?php echo $_SESSION["student"]; ?>">
                        <input type="hidden" name="postto" value="<?php echo $srow["student"]; ?>">
                        <textarea class="form-control" rows="2" name="post" placeholder="Anything you would like to post, <?php echo $srow["firstname"]; ?>?"></textarea>
                        <br />
                        <button type="submit" class="button"><span class="glyphicon glyphicon-pencil"></span> Post</button>
                        </form>
                         <br />
                        <p id="postval"></p>
                    </div>
                        
                 <div id="media-bar">
                    <button type="button" data-toggle="modal" data-target="#forum" title="Post a discussion" class="button"><span class="glyphicon glyphicon-comment"></span> Forum</button>
                    <button type="button" data-toggle="modal" data-target="#file" title="Upload your work for others to see" class="button"><span class="glyphicon glyphicon-book"></span> Share</button>
                    <a href="chat.php"><button type="button" title="Chat with friends" class="button"><span class="glyphicon glyphicon-transfer"></span> Chat</button></a>
                </div>
            
                 
                 <!-- Forum Popup -->
            <div id="forum" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Start Discussion</h4>
                        </div>
                        <div class="modal-body">
                            <form action="scripts/post-forum.php" name="forumform" onsubmit="return CheckForum()" method="POST">
                                <div class="form-group">
                                <input type="hidden" class="form-control" name="student" value="<?php echo $_SESSION["student"]; ?>">
                                </div>
                                <img src="img/reply.svg" class="img-circle" alt="doc" width="120px" height="120px">
                                <div class="form-group">
                                    <label for="subject">Select Subject</label>
                                    <select class="form-control" name="subject" id="subject">
                                        <option value="General">General</option>
                                        <option value="FAQ">FAQ</option>
                                        <option value="CodeNewBie">CodeNewBie</option>
                                        <option value="JavaScript">JavaScript</option>
                                        <option value="jQuery">jQuery</option>
                                        <option value="jQuery">PHP</option>
                                        <option value="Python">Python</option>
                                        <option value="C++">C++</option>
                                        <option value="Data Science">Data Science</option>
                                    </select>
                                </div>                        
                                <div class="form-group">
                                    <label for="title">Topic:</label>
                                    <input type="text" class="form-control" name="title" placeholder="Question Title" id="title">
                                </div>
                                <p id="topval"></p>
                                <div class="form-group">
                                    <label for="body">Discussion:</label>
                                       <textarea class="form-control" rows="2" name="body" id="body" placeholder="Start a discussion <?php echo $srow["firstname"]; ?>!"></textarea>
                                </div>
                                <p id="disval"></p>
                                 <input type="submit" class="button" value="Post" name="post">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Close</button>
                        </div>
                    </div>
                </div>
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
                        if (move_uploaded_file($_FILES["my_file"]["tmp_name"], "../../sharedfiles/".$_FILES["my_file"]["name"]))
                        {
                            $doc = $_FILES["my_file"]["name"];
                            $doc = mysqli_real_escape_string($dbcon, $doc);
                            
                            $description = $_POST["description"];
                            $description = mysqli_real_escape_string($dbcon, $description);

                            $sqldoc = "INSERT INTO tbl_shared_files (student, filename, description) VALUES ('$student', '$doc', '$description')";

                            mysqli_query($dbcon, $sqldoc);
                            
                            echo "<script type='text/javascript'>location.href = 'workroom.php';</script>";

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
               
                
                <div class="row">
                        <?php
                        while($prow = mysqli_fetch_assoc($presult))
                            {
                                
                                echo "<div class='col-sm-12'>" .
                                "<div class='well'" .
                                "<h1>" . $prow["student"] . " &#8594; " . $prow["postto"] . "</h1>" .
                                "<h4>" . $prow["post"] . "</h4>" .
                                "<img src=../profileimg/" . $prow["profileimg"] . " class='img-circle post-img' width=120 height=120 alt='Profile Picture'>" . "<br />" .                        
                                "</div>" .
                                "<p id='dateposted'>" . $prow["dateposted"] . "</p>" .
                                "</div>";
                            }
                    
                        ?>
                </div>
                
            </div>
        </div>
        
        <footer>
            <i><img src="../img/logo.png" width="80"></i>
            <span class="copyright" align-text = "center" style="color:black;">&copy; <h9 style="color:black;"><strong> 2018. IMY220 Project.</strong></h9></span>
        </footer>
        
	</body>
</html>