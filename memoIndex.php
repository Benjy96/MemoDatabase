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
	//Modal functionality
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
	<!-- SIDEBAR -->
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
	<!-- /SIDERBAR -->

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
	  <!-- /ADD MEMO SECTION -->
	  
    </div>
	<!-- RIGHT CONTAINER -->
  </div>
</div>

</body>
</html>
