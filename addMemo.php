<?php 

/*

TO DO:

- Finish implementing create memo


*/

session_start();

//Client-side validation happened on memoIndex, don't need to check if the post variables exist
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user"])){
	//Open a DOMDocument - tree-based parsing
	$xml = new DOMDocument();
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false;
	$xml->load("memos.xml") or die("Can't load memo XML file: addMemo.php");
	
	//get root element
	$rootElement = $xml->documentElement;	
	//get last user to make a memo so we can locate the most recent memo
	$lastUserUpdated = $rootElement->childNodes->item(0)->nodeValue;
	
	//get user element (to create a memo under)
	foreach($rootElement->childNodes AS $rootChild){
		if($rootChild->hasAttribute("name")){
			if($rootChild->getAttribute("name") == $_SESSION["user"]){
				$currentUser = $rootChild->getAttribute("name");
				break;
			}
		}
	}
	
	//get implicit data
	$date = date(d-m-Y);
	$sender = $_SESSION["user"];
	//get last memo entered's ID:
	foreach($rootElement->childNodes AS $rootChild){
		if($rootChild->hasAttribute("name")){
			if($rootChild->getAttribute("name") == $lastUserUpdated){
				$newID = $rootChild->childNodes->item(0)->getAttribute("id");
				break;
			}
		}
	}
	$newID++;
	
	//get explicit data (passed via POST) - validated client-side on memoIndex.php
	$title = $_POST["memoTitle"];
	$body = $_POST["memoBody"];
	$recipient = $_POST["memoRecipient"];

	//validate the optional URL field - if invalid, return to the form
	if(!empty($_POST["memoURL"])){
		$URL = $_POST["memoURL"];
		//if invalid, return to the form
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$URL)) {
			$_SESSION["memoTitle"] = $title;
			$_SESSION["memoBody"] = $body;
			$_SESSION["memoRecipient"] = $recipient;
			
			header("Location: memoIndex.php"); 
		}
	}
}
?>