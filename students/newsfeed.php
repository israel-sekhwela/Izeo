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

$sqlmessage = "SELECT * FROM inbox_messages WHERE to_student = '$student'";
$mresult = mysqli_query($dbcon, $sqlmessage);
$messagecount = mysqli_num_rows($mresult);

$sqlrequests = "SELECT * FROM friends_requests WHERE to_student = '$student'";
$reqresult = mysqli_query($dbcon, $sqlrequests);
$requestcount = mysqli_num_rows($reqresult);

$sqleng = "SELECT * FROM tbl_forum ORDER BY date DESC";
$fresult = mysqli_query($dbcon, $sqleng);

$sqlfriends = "SELECT * FROM tbl_student WHERE student <> '$student' ORDER BY RAND() LIMIT 10";
$friendsresult = mysqli_query($dbcon, $sqlfriends);

$sqlrep = "SELECT * FROM tbl_forum JOIN tbl_forum_replies ON tbl_forum.forumid = tbl_forum_replies.forumid";
$represult = mysqli_query($dbcon, $sqlrep);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>News feed</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" href="../img/fav.ico" type="image/x-icon">
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
					<!-- <a href="index.php" class="navbar-brand"> learnbe </a> -->
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
                    <form action="forum-search.php" method="GET">
                        <input type="text" style="float:right;" name="query" placeholder="Search forum discussions.." />
                    </form>
                </div>
            </div>
            <hr />
            <h2> News Feed</h2>
            <h4>Browse through the news, check out forums and find friends. </h4>
            <hr />
            <div id="forum">
                 <h2>Forum Posts</h2>
                  <h4>Join in or start a discussion forum, <?php echo $srow["firstname"] ?>!</h4>
                  <button type="button" data-toggle="modal" data-target="#forumd" title="Ask a question, start a discussion" class="button" style="width:200px;"><span class="glyphicon glyphicon-comment"></span> Post Discussion</button> <br />
            <?php

                echo "<table id='forumtbl' class='table'>
                <thead>
                <tr>

                <th>Topic</th>
                <th>Subject</th>
                <th>Student</th>
                <th>Date Posted</th>

                </tr>
                </thead>";
                    if (mysqli_num_rows($fresult) > 0)
                    {
                        while ($frow = mysqli_fetch_assoc($fresult))
                        {
                            echo "<tr>";
                            echo "<td><a href='view-forum.php?forumid=". $frow["forumid"] . "' title='view discussions about this topic'> " . $frow["title"] . "</a>" . "</td>";
                            echo "<td>" . $frow["subject"] . "</td>";
                            echo "<td>" . $frow["student"] . "</td>";
                            echo "<td>" . $frow["date"] . "</td>";
                            echo "</tr>";
                        }

                    }
                    else
                    {
                        echo "0 Forums Found.";
                    }
                    echo "</table>";
            ?>
            </div>
            
            <hr />
            
            <div id="news">
                <h2>Find Friends</h2>
                <h4>Find people that you may know!</h4>
                <?php
                
                 while ($studentrow = mysqli_fetch_assoc($friendsresult))
                    {
                        echo "<hr />";
                        echo "<h4><strong><a href=profile.php?student=". $studentrow["student"] . " title='Click to view profile'> " . $studentrow["firstname"] . " " . $studentrow["surname"] . "</strong></h4>" .
                        "<img src=../profileimg/" . $studentrow["profileimg"] . " class='img-circle' width=100 height=100 alt='news_image'></a>";
                        echo "<br /><br />";
                        echo "<p>" . $studentrow["bio"] . "</p>";
                        echo "<p>Engagement Rating: " . $studentrow["engagement"] . "%</p>";
                        echo "<hr />";
                    }
                ?>
            </div>

            
                         <!-- Forum Popup -->
            <div id="forumd" class="modal fade" role="dialog">
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
            
        </div>
        
        <footer>
            <i><img src="../img/logo.png" width="80"></i>
            <span class="copyright" align-text = "center" style="color:black;">&copy; <h9 style="color:black;"><strong> 2018. IMY220 Project.</strong></h9></span>
        </footer>
        
	</body>
</html>