<?php
session_start();

//If we have logged out, remove session variables
if(isset($_POST["logout"])){
	session_unset();
}

//If we have already logged in, redirect to the memo databse index page, else prompt log in
if(isset($_SESSION["user"])){
	header("Location: memoIndex.php");
}else{
	login();
}

// ----- FUNCTIONS ----- //

//Tree-based parse (good for smaller documents)
function getUsers($fileName){
	$userList = new DOMDocument();
	$userList->formatOutput = true;
	$userList->preserveWhiteSpace = false;
	$userList->load($fileName) or die("Error loading XML");
	return $userList;
}

//Check if a form has been submitted
function login(){
	//if form has been submitted (in full)
	if(isset($_POST["username"]) && isset($_POST["password"])){
		//Store passed in form values and reset the POST array
		$username = $_POST["username"];
		$password = $_POST["password"];

		//Load in the list of users from an XML file
		$userList = getUsers("userList.xml");
		
		//Get root and users element for iterating through users
		$root = $userList->documentElement;
		$users = $root->childNodes->item(0);
		
		//loop through DOMDocument
		foreach ($users->childNodes as $iterator){	//go through each user
			if($username == $iterator->childNodes->item(0)->nodeValue){	//if username matches, check <user> password
				if($password == $iterator->childNodes->item(1)->nodeValue){	//if password matches
					$_SESSION["user"] = $iterator->childNodes->item(0)->nodeValue;	//set a session variable - current user
					$_SESSION["wrongUser"] = false;
					$_SESSION["wrongPass"] = false;
					header("Location: memoIndex.php"); //log in
				}
				$_SESSION["wrongPass"] = true;
			}
			$_SESSION["wrongUser"] = true;
		}
		displayLogin();
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
  
	<!-- LOGIN FORM -->
    <div>
	  <!-- POST to a hard-coded, new file to avoid any security risks with $_SERVER["PHP_SELF"] -->
      <form class="login-form" action="login.php" method="post">
        <h1 class="bottom-padding">Please sign in</h2>
		<div class="form-group">
			<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
		</div>
		<div class="form-group">
			<input type="password" name="password" class="form-control" placeholder="Password" required>
		</div>
       
        <button class="btn btn-primary" type="submit">Sign in</button>
		<?php if($_SESSION["wrongUser"] == true || $_SESSION["wrongPass"] == true){ ?>
		<div class="notice"style="padding-top: 15px">
		Wrong details entered.
		</div>
		<?php } ?>
      </form>
	  
    </div> 
	<!-- /LOGIN FORM -->
	
  </body> 
  <!-- /BODY -->
</html>
<?php 
}
?>
