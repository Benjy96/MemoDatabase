<?php

/*
AJAX Search functionality implemented in this file.

If the user's query is numeric, search for a memo's id, else search for text fields.
The user can search/narrow down memos via text fields such as title, author, and recipient.

*/

$xmlDoc = new DOMDocument();
$xmlDoc->formatOutput = true;
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->load("memos.xml");

$memos = $xmlDoc->getElementsByTagName("memo");

$query = $_GET["q"];
if(strlen($query) > 0){
	//If we are searching by ID
	if(is_numeric($query)){	
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
	//Else search for text (sender/author or recipient or title)
	}else{	
		$hint = "";
		
		for($i = 0; $i <($memos->length); $i++){
			$currentMemo = $memos->item($i)->getAttribute("id");
			$sender	= $memos->item($i)->parentNode->getAttribute("name");	//"author"
			$title = $memos->item($i)->childNodes->item(0)->nodeValue;
			$recipient = $memos->item($i)->childNodes->item(1)->nodeValue;
			$date = $memos->item($i)->childNodes->item(2)->nodeValue;
			
			if(stristr($sender, $query) || stristr($recipient, $query) || stristr($title, $query)){
				if($hint ==""){
					$hint = "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
					$currentMemo . "), Sent to " .
					$recipient . 
					" by ". $sender .
					" on " . $date .
					"</div>";
				}else{
					$hint = $hint . "<div class='panel-body' onclick='getCurrentById($currentMemo)'>(" . 
					$currentMemo . "), Sent to " .
					$recipient . 
					" by " . $sender . 
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