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
					
	for($i = 0; $i<($memos->length); $i++){
		$currentMemo = $memos->item($i)->getAttribute("id");
		$currentTitle = $memos->item($i)->childNodes->item(0)->nodeValue;
		$currentSender = $memos->item($i)->parentNode->getAttribute("name");
		if(stristr($currentMemo, $query)){
			if($hint==""){
				$hint = "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
				$currentMemo . ") " .
				$currentTitle . 
				", by ". $currentSender .
				"</div>";
			}else{
				$hint = $hint . "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
				$currentMemo . ") " .
				$currentTitle . 
				", by " . $currentSender . 
				"</div>";
			}
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