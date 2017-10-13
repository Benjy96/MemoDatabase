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
	
	$rootElement = $xml->documentElement;
	//get user element
	//get latest memo number
	//append new memo
	
	//Data to add to memo:
	
	/*
	Implicit:
	
		Sender - user
		ID - incremented from previous memo
		Date - get timestamp
	
	*/
	
	/*
	Explicit: (via POST)
	
	Body
	Title
	Recipient
	URL
	
	*/
}

function getExplicitData(){
	
}

function getImplicitData(){
	$date = date(d-m-Y);
	$sender = $_SESSION["user"];
	$id = 
}




?>