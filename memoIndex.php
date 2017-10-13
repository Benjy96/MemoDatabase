<?php session_start(); 

if(!isset($_SESSION["user"])){
	header("Location: login.php");
}

?>

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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
	<!-- Custom CSS -->
	<link rel="stylesheet" href="memo_app_css.css">
	<!-- /Custom CSS -->
	
	<!-- Custom JavaScript -->
	<script type="text/javascript">
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
	
	function nextMemo(){
		if(current < memos.length) {
			current++;
			showMemo();
		}
	}
	
	function previousMemo(){
		if(current > 0){
			current--;
			showMemo();
		}
	}
	
	function showMemo(){
		updateMemoDisplayedIndicator();
		setButtons();
		
		//Get all memo information
		var sender = memos[current].parentNode.getAttribute("name");
		
		var memoId = memos[current].getAttribute("id");
		
		var title = memos[current].getElementsByTagName("title")[0].childNodes[0].nodeValue;
		var date = memos[current].getElementsByTagName("date")[0].childNodes[0].nodeValue;
		var body = memos[current].getElementsByTagName("body")[0].childNodes[0].nodeValue;
		
		document.getElementById("memoID").innerHTML = " " + memoId;
		document.getElementById("title").innerHTML = " " + title;
		document.getElementById("sender").innerHTML = " " + sender;
		document.getElementById("date").innerHTML = " " + date;
		document.getElementById("body").innerHTML = " " + body;
	}
	
	//Change the HTML at top of page
	function updateMemoDisplayedIndicator(){
		var element = document.getElementById("whichMemo");
		var index = memos.length - current;
		
		if(current == 0){
			element.innerHTML = "LATEST MEMO (#" + index + ")";
		}else{	
			element.innerHTML = "MEMO #" + index;
		}
	}
	
	function setButtons(){
		//Dynamically set next/previous to active or disabled
		if(current != 0){
			document.getElementById("prevMemoButton").disabled = false;
			document.getElementById("prevMemoButton").className = "btn btn-primary";
		}else{
			document.getElementById("prevMemoButton").disabled = true;
			document.getElementById("prevMemoButton").className = "btn btn-primary disabled";
		}
		
		if(current == memos.length - 1){
			document.getElementById("nextMemoButton").disabled = true;
			document.getElementById("nextMemoButton").className = "btn btn-primary disabled";
		}else{
			document.getElementById("nextMemoButton").disabled = false;
			document.getElementById("nextMemoButton").className = "btn btn-primary";
		}
	}
	
	</script>
	<!-- /Custom JavaScript -->
	
	<!-- JQuery -->
	<script>
	$(document).ready(function(){
		$("#accButton").click(function(){
			$("#accModal").modal();
		});
	});
	</script>
	<!-- /JQuery -->
  
  </head>
  <!-- /HEADER -->
<body onload="showMemo()">	<!-- Display memos on load -->

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
	
	<!-- CURRENT USER -->	
	<button class="btn btn-default circle" id="accButton">
	<span class="glyphicon glyphicon-user"></span>
	</button>
	
	<!-- MODAL -->
	<div class="modal fade" id="accModal" role="dialog">
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
	  <h4><small id="whichMemo">LATEST MEMO</small></h4>
	  <hr>
	  <!-- /TOP -->
	  
	  <!-- MEMO SECTION-->
	  <div id="memo" class="well well-lg">
		  <h2 id="title">Title</h2>
		  <blockquote>
			<p id="body">Body</p>
			<footer id="sender"> name</footer>
		  </blockquote>
		  <span class="glyphicon glyphicon-time"></span> <p class="inline bg-info">DATE:</p><p id="date" class="inline"> 666/666/666</p></br>
		  <span class="glyphicon glyphicon-barcode"></span> <p class="inline bg-info">MEMO ID:</p><p id="memoID" class="inline"> memoid: #342423432</p>
	  </div>
	  
	  <button type="button" id="prevMemoButton" onclick="previousMemo()" class="btn btn-primary disabled" disabled="disabled">Previous Memo</button>
	  <button type="button" id="nextMemoButton" onclick="nextMemo()" class="btn btn-primary">Next Memo</button>
	  
	  <!-- /MEMO SECTION -->
	  
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
