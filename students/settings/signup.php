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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <link rel="stylesheet" href="css/student-account-style.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/mem-validation.js"></script>
    
</head>
    <body id="#home">
        
        <nav class="navbar navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						MENU                        
					</button>
					<a href="../index.php" class="navbar-brand"> learnbe </a>
				</div>
				<div class="collapse navbar-collapse" id="navbar">
					<ul class="nav navbar-nav">
						<li><a href="../timeline.php"><span class='glyphicon glyphicon-transfer'></span> Timeline</a></li>
						<li><a href="../workroom.php"><span class='glyphicon glyphicon-book'></span> Work Room</a></li>
                        <li><a href="../dropbox.php"><span class='glyphicon glyphicon-paperclip'></span> DropBox</a></li>
						<li><a href="../explore.php"><span class='glyphicon glyphicon-globe'></span> Explore</a></li>
                        <li><?php echo "<a href=../profile.php?student=" . $srow["student"] . "><span class='glyphicon glyphicon-user'></span> Profile</a>"; ?></li>
				        
					</ul>
					<ul class="nav navbar-nav navbar-right">
                      <li><a href="../friend-requests.php"><span class="glyphicon glyphicon-plus"></span> Friend Requests(<?php echo $requestcount; ?>)</a></li>
                        <li><a href="../messages.php"><span class="glyphicon glyphicon-envelope"></span> Messages(<?php echo $messagecount; ?>)</a></li>
                          <li><a href="account.php"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                        <li><?php echo "<a href='scripts/logout-student.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a>"; ?></li>
					</ul>
				</div>
			</div>
		</nav>
        
        <div class="container">
            <div id="settings-section"><br />
                  <h1>Change Memorial Word</h1>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="well">
                            <div id="menu">
                                <ul>
                                    <li><a href="account.php">Overview</a></li>
                                    <li><a href="change-bio.php">Change Bio</a></li>
                                    <li><a href="change-image.php">Change Profile Pic</a></li>
                                    <li><a href="change-memword.php">Change Mem Word</a></li>
                                    <li><a href="change-password.php">Change Password</a></li>
                                    <li><a href="../help.php">Help</a></li>
                                </ul>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="col-sm-9">
                        <div class="well">
                            <div class="settings">
                                <h2 id="white" style="text-decoration:underline;"><?php echo $srow["firstname"]; ?>'s Memorable Word</h2>
                                    <form action="scripts/update-student-memorable.php" name="memform" onsubmit="return CheckMemStudent()" method="POST">
                                    <div class="form-group">
                                    <input type="hidden" class="form-control" name="student" value="<?php echo $_SESSION["student"]; ?>">
                                    </div>

                                    <div class="form-group">
                                    <label for="oldmemorable">Old Memorable Word</label>
                                    <input type="password" class="form-control" name="oldmem" placeholder="Old Word">
                                    <p id="omval" style="color: #FFFFFF;"></p>
                                    </div>

                                    <div class="form-group">
                                    <label for="newmemorable">New Memorable Word</label>
                                    <input type="password" class="form-control" name="newmem" placeholder="New Word">
                                    <p id="nmval" style="color: #FFFFFF;"></p>
                                    </div>
                                        
                                    <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                      <p id="pval" style="color: #FFFFFF;"></p>
                                    </div>
                                        
                                    <p><strong>NOTE: If your old memorable word is correct, then your new word will be updated, when you have supplied your correct password. If not the system will ignore your request for security purposes. Please consult your teacher if problems occur.</strong></p>
                                    <button type="submit" class="update-button"><span class="glyphicon glyphicon-log-in"></span> Update</button>
                                    </form>
                            </div>
                        </div> 
                    </div>
                    
                </div>
            </div>
        </div>
        <footer>
            <i><img src="../../img/logo.png" width="80"></i>
            <span class="copyright" align-text = "center">&copy; <h9><strong> 2018. IMY220 Project.</strong></h9></span>
        </footer>
        
	</body>
</html>