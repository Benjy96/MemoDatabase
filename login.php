<?php

function getUsers($fileName){
	$userList = new DOMDocument();
	$userList->load($fileName) or die("Error loading XML");
	return $userList;
}

//check username and password against the xml user database
//user database is small so use domdocument
if(isset($_POST["username"])){

	$userList = getUsers("userList.xml");

} else { 

?>


<!DOCTYPE html>
<!-- HEADER -->
<html lang="en">
  <head>
  <title>Memo Database</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
  
  <body class="main-background"> <!-- BODY -->
  
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
    </div> <!-- /LOGIN FORM -->
	
  </body> <!-- /BODY -->
</html>

<?php } ?>