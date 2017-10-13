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
	
	//Data to add to memo:
	
	/*
	Implicit:
	
		Sender
		ID
		Date
	
	*/
	
	/*
	Explicit:
	
	Body
	Title
	Recipient
	URL
	
	*/
}





?>