<?php session_start(); ?>

<!DOCTYPE html>
<!-- HEADER -->
<html lang="en">
  <head>
  <title>Memo Database</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & JavaScript-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  
	<!-- Custom CSS -->
	<link rel="stylesheet" href="memo_app_css.css">
	<!-- /Custom CSS -->
	
	<!-- Custom JavaScript -->
	<script type="text/javascript">
	alert("hi");
	var memos;
	
	if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
		var xmlhttp=new XMLHttpRequest();
	} else { // IE6, IE5
		var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","memos.xml",false);
	xmlhttp.send();
	xmlDoc=xmlhttp.responseXML;
	
	//get all memos
	memos=xmlDoc.getElementsByTagName("memo"); 
	var current = 0;
	
	function showMemo(){
		var title = memos[current].getElementsByTagName("title")[0].childNodes[0].nodeValue;
		var date = memos[current].getElementsByTagName("date")[0].childNodes[0].nodeValue;
		var body = memos[current].getElementsByTagName("body")[0].childNodes[0].nodeValue;
		
		document.getElementById("title").innerHTML = title;
		document.getElementById("date").innerHTML = date;
		document.getElementById("body").innerHTML = body;
	}
	
	
	/*
	
	TO DO: 
	
	- Allow user to cycle through previous memos
	- Complete the memo "template" in HTMLK:
		- Add Next/previous buttons to memo
	
	*/
	</script>
	<!-- /Custom JavaScript -->
  
  </head>
  <!-- /HEADER -->
<body onload="showMemo()">	<!-- Display memos on load -->

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
	
	<!-- CURRENT USER -->	
	<button type="button" class="btn btn-default circle" data-toggle="modal" data-target="#accModal">
	<span class="glyphicon glyphicon-user"></span>
	</button>
	
	<!-- MODAL -->
	<div id="accModal" class="modal hide" role="dialog">
      <div class="modal-dialog">
        
		<!-- MODAL CONTENT -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title main-text">Current User: <?php echo $_SESSION["user"]; ?></h3>
			</div>
			<div class="modal-body">
				<p class="main-text">Info: </p>
			</div>
			<div class="modal-footer">
				<form action="login.php" method="post">
					<button type="submit"  name="logout" class="btn btn-primary">Log out</button>
				</form>
			</div>
		</div>
		<!-- /MODAL CONTENT -->
		
		</div>
	</div>
	<!-- /MODAL -->
	<!-- /CURRENT USER -->
	
      <h2>Memo Database</h2>
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Memos...">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>
    </div>

	<!-- RIGHT CONTAINER -->
    <div class="col-sm-9">
	  <!-- TOP -->
	  <h4><small>RECENT MEMOS</small></h4>
	  <hr>
	  <!-- /TOP -->
	  
	  <!-- SAMPLE MEMO SECTION-->
	  <div id="memo">
		  <h2 id="title">Title</h2>
		  <h5 id="date"><span class="glyphicon glyphicon-time"></span> Date</h5>
		  <p id="body">Body</p>
	  </div>
	  <!-- /SAMPLE MEMO SECTION -->
	  
      <hr>	<!-- SECTION SEPARATOR -->

	  <!-- ADD MEMO SECTION -->
      <h4>Leave a Comment:</h4>
      <form role="form">
        <div class="form-group">
          <textarea class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
      </form>
      <br><br>
      
      <p><span class="badge">2</span> Comments:</p><br>
      
      <div class="row">
        <div class="col-sm-2 text-center">
          <img src="bandmember.jpg" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4>Anja <small>Sep 29, 2015, 9:12 PM</small></h4>
          <p>Keep up the GREAT work! I am cheering for you!! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <br>
        </div>
        <div class="col-sm-2 text-center">
          <img src="bird.jpg" class="img-circle" height="65" width="65" alt="Avatar">
        </div>
        <div class="col-sm-10">
          <h4>John Row <small>Sep 25, 2015, 8:25 PM</small></h4>
          <p>I am so happy for you man! Finally. I am looking forward to read about your trendy life. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <br>
          <p><span class="badge">1</span> Comment:</p><br>
          <div class="row">
            <div class="col-sm-2 text-center">
              <img src="bird.jpg" class="img-circle" height="65" width="65" alt="Avatar">
            </div>
            <div class="col-xs-10">
              <h4>Nested Bro <small>Sep 25, 2015, 8:28 PM</small></h4>
              <p>Me too! WOW!</p>
              <br>
            </div>
          </div>
        </div>
      </div>
	  
    </div>
	<!-- RIGHT CONTAINER -->
  </div>
</div>

</body>
</html>
