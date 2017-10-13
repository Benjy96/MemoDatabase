<?php 

session_start();

//Client-side validation happened on memoIndex, don't need to check if the post variables exist
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
	
	//get user element
	foreach($rootElement->childNodes AS $user){
		if($user->getAttribute("name") == $_SESSION["user"]){
			$userElement = $user; 
			break;
		}
	}
	
	//get implicit data
	$date = date(d-m-Y);
	$sender = $_SESSION["user"];
	$latestID = $userElement->$rootElement->childNodes->item(1)->getAttribute("id");
	$newID = latestID + 1;
	
	//get explicit data (passed via POST) - validated client-side on memoIndex.php
	$title = $_POST["memoTitle"];
	$body = $_POST["memoBody"];
	$recipient = $_POST["memoRecipient"];

	//validate the optional URL field - if invalid, return to the form
	if(!empty($_POST["memoURL"])){
		$URL = $_POST["memoURL"];
		//if invalid, return to the form
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
			$_SESSION["memoTitle"] = $title;
			$_SESSION["memoBody"] = $body;
			$_SESSION["memoRecipient"] = $recipient;
			
			header("Location: memoIndex.php"); 
		}
	}
}
?>