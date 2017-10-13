<?php 

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user"])){
	
	//Get file for writing to
	$file = "memos.xml";
	$filePath = fopen($file, "rb") or die("Can't open memo XML file: addMemo.php");
	$data = fread($filePath, filesize($file));
	
	//Open a DOMDocument - tree-based parsing
	$xml = new DOMDocument();
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false;
	$xml->loadXML($data) or die("Can't load memo XML file: addMemo.php");
	
	$rootElement = $xml->documentElement;	//all_memos
	$userElement;
	
	//get user element
	foreach($rootElement->childNodes AS $user){
		if($user == $_SESSION["user"]){
			$userElement = $user; 
			break;
		}
	}
	
	//get implicit data
	$date = date(d-m-Y);
	$sender = $_SESSION["user"];
	$latestID = $userElement->childNodes->item(0)->getAttribute("id");
	$newID = latestID + 1;
	
	//get explicit data (passed via POST) - validated client-side on memoIndex.php
	$title = $_POST["memoTitle"];
	$body = $_POST["memoBody"];
	$recipient = $_POST["memoRecipient"];
	
	//validate the URL field - if invalid, return to the form
	if(!empty($_POST["memoURL"])){
		$URL = $_POST["memoURL"];
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
			$_SESSION["title"] = $title;
			$_SESSION["body"] = $body;
			$_SESSION["recipient"] = $recipient;
			
			header("Location: memoIndex.php"); 
		}
	}
}
?>