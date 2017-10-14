<?php

$xmlDoc = new DOMDocument();
$xmlDoc->formatOutput = true;
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->load("memos.xml");

//let's start with a search by ID - can change the params later
$memos = $xmlDoc->getElementsByTagName("memo");

$query = $_GET["q"];

if(strlen($query) > 0){
	$hint = "";
	
	//go through all memos to search for matches
	for($i = 0; $i<($memos->length); $i++){
		$currentMemo = $memos->item($i)->getAttribute("id");
		if($currentMemo == $query){
			$memoTitle = $memos->item($i)->childNodes->item(0)->nodeValue;
			$memoDate = $memos->item($i)->childNodes->item(2)->nodeValue;
			$hint = "(" . $currentMemo . ")" . " " . $memoTitle . ", posted on " . $memoDate;
			break;
		}
	}
	
	if($hint == ""){
		$response = "nothing found";
	}else{
		$response = $hint;
	}
	
	echo $response;
}
?>