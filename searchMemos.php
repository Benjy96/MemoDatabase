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
	
	//for each memo
		//if(id[0] == q[0]
			//for each q character;
				//if(id[x] == q[x]
					//set hint

					
	for($i = 0; $i<($memos->length); $i++){
		$currentMemo = $memos->item($i)->getAttribute("id");
		if(stristr($currentMemo, $query)){
			if($hint==""){
				$hint = $currentMemo;
			}else{
				$hint = $hint . "<br/>";
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