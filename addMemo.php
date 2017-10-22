<?php 
session_start();
/*
The way this works:

XML file stores all memos, and each memo is assigned underneath a "user" (that created it).

For memo ID: every time a memo is added, the <last_updated> field records what user the memo was assigned to,
meaning we can then use this information to get the LAST memo id (since memo IDs increment
with every addition).

For adding a memo, we first find implicit and explicit data. Implicit data is, for example,
the current logged in user (sender) and the date/time. We then add the user's memo to their
corresponding section of the memo XML file.

! - The login system is directly tied to memo storage and creation.

*/

/* ----- ADD MEMO ----- */

/*
Client-side validation happened on memoIndex; we don't need to check if the post variables exist
In addition, we are not using $_SERVER["PHP_SELF"], so we don't need htmlspecialchars validation 
*/
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user"])){
	//If a user enters the same details into the "Create memo" form more than once, the display doesn't update due to caching, this fixes that.
	$_SESSION["formSubmitted"] = true;
	//Open a DOMDocument - tree-based parsing
	$xml = new DOMDocument();
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false;
	$xml->load("memos.xml") or die("Can't load memo XML file: addMemo.php");
	
	//Get root element
	$rootElement = $xml->documentElement;	
	//get last user to make a memo so we can locate the most recent memo
	$lastUpdated = $rootElement->childNodes->item(0)->nodeValue;
	$lastUpdatedNode = $rootElement->childNodes->item(0);
	
	//Get user element (to create a memo under)
	foreach($rootElement->childNodes AS $rootChild){
		if($rootChild->hasAttribute("name")){
			if($rootChild->getAttribute("name") == $_SESSION["user"]){
				$sender = $rootChild;	//current user's XML node
				break;
			}
		}
	}
	
	//Get implicit data
	$date = date("d/m/y");
	$currentUser = $sender->getAttribute("name");
	//Get last memo entered's ID:
	foreach($rootElement->childNodes AS $rootChild){
		if($rootChild->hasAttribute("name")){
			if($rootChild->getAttribute("name") == $lastUpdated){
				$newID = $rootChild->childNodes->item(0)->getAttribute("id");
				break;
			}
		}
	}
	$newID++;
	
	//Get explicit data (passed via POST) - validated client-side on memoIndex.php
	$_SESSION["invalidURL"] = false;
	$_SESSION["invalidRecipient"] = false;
	$_SESSION["invalidFormData"] = false;
	
	$title = $_POST["memoTitle"];
	$body = $_POST["memoBody"];
	$recipient = $_POST["memoRecipient"];

	unset($_SESSION["memoTitle"]);
	unset($_SESSION["memoBody"]);
	unset($_SESSION["memoRecipient"]);
	
	//Set explicit data (needs to be returned to memoIndex if the form is invalid)
	$_SESSION["memoTitle"] = $title;
	$_SESSION["memoBody"] = $body;
	$_SESSION["memoRecipient"] = $recipient;
	
	//Clear previous invalid search
	$_SESSION["invalidFormData"] = false;
	
	/*	Ensure the recipient name is appropriate (no numbers - this website is boring and official, probably used by the government. 
		No xxX123_SlAyErZ456_ allowed unfortunately.	*/
	if(!preg_match("/^[a-zA-Z ]*$/",$recipient)){
		$_SESSION["invalidRecipient"] = true;
		$_SESSION["invalidFormData"] = true;
	}
	
	//Validate the optional URL field - if invalid, return to the form
	if(!empty($_POST["memoURL"])){
		$URL = $_POST["memoURL"];
		//if invalid, return to the form
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$URL)) {
			$_SESSION["invalidURL"] = true;
			$_SESSION["invalidFormData"] = true;
		}
	}else{
		$URL = " ";
	}
	
	/* ----- We now have validated explicit and implicit data, let's create the memo and add it to the XML file ----- */
	if($_SESSION["invalidFormData"] == false){
		//Sender will be parent
		
		//Create memo title node
		$newTitle=$xml->createElement("title");
		$newTitleText=$xml->createTextNode("$title");
		$newTitle->appendChild($newTitleText);
		
		//Create recipient node
		$newRecipient=$xml->createElement("recipient");
		$newRecipientText=$xml->createTextNode("$recipient");
		$newRecipient->appendChild($newRecipientText);

		//Create date node
		$newDate=$xml->createElement("date");
		$newDateText=$xml->createTextNode("$date");
		$newDate->appendChild($newDateText);
		
		//Create body node
		$newBody=$xml->createElement("body");
		$newBodyText=$xml->createTextNode("$body");
		$newBody->appendChild($newBodyText);
		
		//Create URL node
		$newURL=$xml->createElement("url");
		$newURLText=$xml->createTextNode("$URL");
		$newURL->appendChild($newURLText);
		
		//Create the collated memo
		$newMemoNode=$xml->createElement("memo");
		$newMemoNode->setAttribute("id", $newID);
		//Add memo child nodes
		$newMemoNode->appendChild($newTitle);	//Title
		$newMemoNode->appendChild($newRecipient);	//Recipient
		$newMemoNode->appendChild($newDate);	//Date
		$newMemoNode->appendChild($newBody);	//Body
		$newMemoNode->appendChild($newURL);	//URL
		
		//Add memo to the data set
		//use current user to insert where we need it
		$lastMemoFromUser=$sender->firstChild;
		$sender->insertBefore($newMemoNode, $lastMemoFromUser);
		
		//Update last user updated
		$newLastUpdatedNode=$xml->createElement("last_updated");
		$newLastUpdatedText=$xml->createTextNode($currentUser);
		$newLastUpdatedNode->appendChild($newLastUpdatedText);
		$rootElement->replaceChild($newLastUpdatedNode, $lastUpdatedNode);
	}
	
	//Dump new xml back into the file
	if($_SESSION["invalidFormData"] == false){
		$xml->save("memos.xml");
	}
	
	header("Location: memoIndex.php");
}
?>