<?php

$xmlDoc = new DOMDocument();
$xmlDoc->formatOutput = true;
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->load("memos.xml");

//let's start with a search by ID - can change the params later
$memos = $xmlDoc->getElementsByTagName("memo");

$query = $_GET["q"];
if(strlen($query) > 0){
	if(is_numeric($query)){	//If we are searching by ID
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
	}else{	//Else search for text
		$hint = "";
		
		for($i = 0; $i <($memos->length); $i++){
			$currentMemo = $memos->item($i)->getAttribute("id");
			$sender	= $memos->item($i)->parentNode->getAttribute("name");	//"author"
			$recipient = $memos->item($i)->childNodes->item(1)->nodeValue;
			$date = $memos->item($i)->childNodes->item(2)->nodeValue;
			
			if(stristr($sender, $query) || stristr($recipient, $query)){
				if($hint ==""){
					$hint = "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
					$currentMemo . ") sent to" .
					$recipient . 
					", by ". $sender .
					" on " . $date .
					"</div>";
				}else{
					$hint = $hint . "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
					$currentMemo . "), Sent to " .
					$recipient . 
					", by " . $sender . 
					" on " . $date .
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
}
?>