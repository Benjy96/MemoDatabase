<?php
session_start();

if(isset($_SESSION["user"])){
	header("Location: memoIndex.php");
}else{
	checkSubmitted();
}

function getUsers($fileName){
	//user file will be smaller than memo file, so use DOMDocument
	$userList = new DOMDocument();
	$userList->formatOutput = true;
	$userList->preserveWhiteSpace = false;
	$userList->load($fileName) or die("Error loading XML");
	return $userList;
}

//Check if a form has been submitted
function checkSubmitted(){
	//check username and password against the xml user database
	if(isset($_POST["username"])){
		
		//Store passed in form values and reset the POST array
		$username = $_POST["username"];
		$password = $_POST["password"];

		//Load in the list of users from an XML file
		$userList = getUsers("userList.xml");
		
		//loop through DOMDocument
		$root = $userList->documentElement;
		$users = $root->childNodes->item(0);
		
		foreach ($users->childNodes as $iterator){	//go through each user
			if($username == $iterator->childNodes->item(0)->nodeValue){	//if username matches, check <user> password
				if($password == $iterator->childNodes->item(1)->nodeValue){	//if password matches
					$_SESSION["user"] = $iterator->childNodes->item(0)->nodeValue;	//set a session variable - current user
					header("Location: memoIndex.php"); //log in
				}
			}
		}
	}else{
		displayLogin();
	}
}

//Display the login form
function displayLogin(){
?>
<!DOCTYPE html>
<!-- HEADER -->
<html lang="en">
  <head>
  <title>Memo Database Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & JavaScript-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  
	<!-- Custom CSS -->
	<link rel="stylesheet" href="memo_app_css.css">
	<!-- /Custom CSS -->
  
  </head>
  <!-- /HEADER -->
  
  <!-- BODY -->
  <body class="main-background"> 
  
    <div><!-- LOGIN FORM -->
      <form class="login-form" action="login.php" method="post">
        <h1 class="bottom-padding">Please sign in</h2>
		<div class="form-group">
			<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
		</div>
		<div class="form-group">
			<input type="password" name="password" class="form-control" placeholder="Password" required>
		</div>
       
        <button class="btn btn-primary" type="submit">Sign in</button>
      </form>
    </div> 
	<!-- /LOGIN FORM -->
	
  </body> 
  <!-- /BODY -->
</html>
<?php 
}
?>
