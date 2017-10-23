<?php 
session_start(); 

unset($_SESSION["formSubmitted"]);	//Prevent the memo display form from NOT refreshing

if(!isset($_SESSION["user"])){
	header("Location: login.php");
}

function displayAddMemoSection(){ ?>
	<h4>Create a Memo:</h4>
      <form role="form" action="addMemo.php" method="post">
        <div class="form-group">
          <input required name="memoTitle" type="text" class="form-control" placeholder="* Title..."/> 
		</div>
		<div class="form-group">
          <textarea required name="memoBody" class="form-control" rows="3" placeholder="* Memo..."></textarea>
        </div>
		<div class="form-group">
          <input required name="memoRecipient" type="text" class="form-control" placeholder="* Recipient(s)..."/> 
		</div>
		<div class="form-group">
		<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#memoURL">Optional URL</button>
			<div id="memoURL" class="collapse">
				  <input name="memoURL" type="text" class="form-control" placeholder="URL..."/> 
			</div>
		</div>
		<br>
		<span class="notice">* fields are required</span>
		<br><br>
		<button type="submit" class="btn btn-primary">Submit</button>
      </form>
<?php 
}

function displayAddMemoSectionInvalid(){ ?>
	<h4>Create a Memo:</h4>
      <form role="form" action="addMemo.php" method="post">
        <div class="form-group">
          <input required name="memoTitle" type="text" value="<?php echo $_SESSION["memoTitle"]; ?>" class="form-control" placeholder="* Title..."/>
		</div>
		<div class="form-group">
          <textarea required name="memoBody" class="form-control" rows="3" placeholder="* Memo..."><?php echo $_SESSION["memoBody"]; ?></textarea>
        </div>
		<div class="form-group">
          <input required name="memoRecipient" value="<?php echo $_SESSION["memoRecipient"]; ?>"type="text" class="form-control" placeholder="* Recipient(s)..."/> 
		  <?php if($_SESSION["invalidRecipient"] == true) { ?><div class="notice">
			 Please enter a valid username. Special characters and numbers are not allowed.
		  </div> <?php } ?>
		</div>
		<div class="form-group">
		  <input name="memoURL" type="text" class="form-control is-invalid" placeholder="URL..."/> 
		  <?php if($_SESSION["invalidURL"] == true) { ?><div class="notice">
			 Please enter a valid URL, or leave blank.
		  </div> <?php } ?>
		</div>
		<br>
		<span class="notice">* fields are required</span>
		<br><br>
		<button type="submit" class="btn btn-primary">Submit</button>
      </form>
<?php 
}
?>

<!DOCTYPE html>
<!-- HEADER -->
<html lang="en">
  <head>
  <title>Memo Database</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
	<meta http-equiv="cache-control" content="no-cache">
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
			showMemo(current);
		}
	}
	
	function previousMemo(){
		if(current > 0){
			current--;
			showMemo(current);
		}
	}
	
	//Display a stored memo
	function showMemo(){
		updateMemoDisplayedIndicator();
		setButtons();
		
		//Get all memo information
		var sender = memos[current].parentNode.getAttribute("name");
		
		var memoId = memos[current].getAttribute("id");
		
		var title = memos[current].getElementsByTagName("title")[0].childNodes[0].nodeValue;
		var recipient = memos[current].getElementsByTagName("recipient")[0].childNodes[0].nodeValue;
		var date = memos[current].getElementsByTagName("date")[0].childNodes[0].nodeValue;
		var body = memos[current].getElementsByTagName("body")[0].childNodes[0].nodeValue;
		var url = memos[current].getElementsByTagName("url")[0].childNodes[0].nodeValue;
		
		document.getElementById("memoID").innerHTML = " " + memoId;
		document.getElementById("title").innerHTML = " " + title;
		document.getElementById("recipient").innerHTML = " " + recipient;
		document.getElementById("sender").innerHTML = " " + sender;
		document.getElementById("date").innerHTML = " " + date;
		document.getElementById("body").innerHTML = " " + body;
		if(url != ""){
			document.getElementById("url").innerHTML = " " + url;
			document.getElementById("url").href = url;
		}
	}
	
	//Show which memo we are on (counter)
	function updateMemoDisplayedIndicator(){
		var element = document.getElementById("whichMemo");
		var index = memos.length - current;
		
		if(current == 0){
			element.innerHTML = "LATEST MEMO (#" + index + ")";
		}else{	
			element.innerHTML = "MEMO #" + index;
		}
	}
	
	//Dynamically set next/previous to active or disabled
	function setButtons(){
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
	
	//Identify a memo by its unique ID, and convert to an index (current)
	function getCurrentById(passedMemoID){
		temp = current;
		current = 0;
		found = false;
		for(i = 0; i < memos.length; i++){
			if(i > 0) current++;
			currentID = memos[i].getAttribute("id");
			if(currentID == passedMemoID){
				found = true;
				break;
			}
		}
		if(found == false) current = temp;
		showMemo();
		document.getElementById("responsiveSearch").innerHTML = "";
		document.getElementById("SearchMemos").value = "";
	}
	
	function showResult(string){
		//Hide the dropdown result box
		if(string.length == 0){
			document.getElementById("responsiveSearch").innerHTML = "";
			document.getElementById("responsiveSearch").style.border="0px"; //add style
			return;
		}
		
		xmlhttp.onreadystatechange=function(){
			if(this.readyState==4 && this.status==200){
				document.getElementById("responsiveSearch").innerHTML=this.response;
			}
		}
		
		xmlhttp.open("GET", "searchMemos.php?q=" + string, true);
		xmlhttp.send();
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
			<h4 class="main-text">Write Priveleges: <?php if($_SESSION["user"] == "guest") echo "None"; else echo "Allowed";?></h4>
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
        <input type="text" id="SearchMemos" onfocus="this.value=''" class="form-control" placeholder="Search memos..." onkeyup="showResult(this.value)">
		<span class="input-group-btn">
          <button class="btn btn-default" type="button">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
	  </div>
	  <div id="responsiveSearch" class="panel panel-default main-text"></div>
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
		  <span class="glyphicon glyphicon-envelope"></span> <p class="inline bg-info">RECIPIENT:</p><p id="recipient" class="inline"> somebody</p></br>
		  <span class="glyphicon glyphicon-time"></span> <p class="inline bg-info">DATE:</p><p id="date" class="inline"> 666/666/666</p></br>
		  <span class="glyphicon glyphicon-barcode"></span> <p class="inline bg-info">MEMO ID:</p><p id="memoID" class="inline"> memoid: #342423432</p></br>
		  <span class="glyphicon glyphicon-link"></span> <p class="inline bg-info">URL:</p><a id="url" class="inline"> memoid: #342423432</a>
	  </div>
	  
	  <button type="button" id="prevMemoButton" onclick="previousMemo()" class="btn btn-primary disabled" disabled="disabled">Previous Memo</button>
	  <button type="button" id="nextMemoButton" onclick="nextMemo()" class="btn btn-primary">Next Memo</button>
	  <!-- /MEMO SECTION -->
	  
      <hr>	<!-- SECTION SEPARATOR -->

	  <!-- ADD MEMO SECTION -->
      <?php 
	  //Only display the add memo section if we have write priveleges. Everyone but the "guest" account has write priveleges for this memo database.
	  
	  if(!($_SESSION["user"] == "guest")){
		  if($_SESSION["invalidFormData"] == true){
			displayAddMemoSectionInvalid();
		  }else{
			displayAddMemoSection();
		  }
	  }
	  ?>
	  <!-- /ADD MEMO SECTION -->
	  
    </div>
	<!-- RIGHT CONTAINER -->
  </div>
</div>
</body>
</html>
