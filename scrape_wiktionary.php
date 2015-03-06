<?php
	// start with the first page of uncountable nouns
	$nextURL = "https://en.wiktionary.org/wiki/Category:English_uncountable_nouns";
	
	// regular expression for "get anything inside an HTML li tag"
	$regex = "~<li>(.+?)</li>~";
	// regular expression for "get the URL link for the next page" (kinda)
	$nextLinkRegex = "~/w/index.php?(.+?)#mw-pages~";
	$linkfound = true;	//used to test if a correct link was found; if not, then the search loop will end

	$fname = "C:\\listOfUncountableNouns.txt";	//Name of file to save the words
	$fp = fopen($fname, "w");
	
	// do this 3 times (for now while debugging)
	for ($i=0; $i<10 && $linkfound == true; $i++) {
		//if($i >= 1) {echo "say anything <br>";}
		// read the entire webpage and store as a string
		$data = file_get_contents($nextURL);
		//if($i >= 1){echo "$data<br><br>";}
				
		for ($j = 0; $j<2; $j++){
		  $foundamp = strpos($data, "amp;");
			if ($foundamp !== false){
			$data = str_replace("amp;", "", $data);
		  }
		}		
		
		// find all the <li> content
		preg_match_all($regex,$data,$matches);
		
		// print each <li> entry to the page
		$wordlist = "";	//holds the large list of words found on the page
		
		foreach ($matches[1] as $match) {
			$wordBegin = strpos ($match, "title=") + 7;
			$wordEnd = strpos ($match, "\">", $wordBegin);
			$foundWord = substr ($match, $wordBegin, $wordEnd - $wordBegin);
			if ($foundWord != "Category:English singularia tantum" && $foundWord != "Category:Uncountable nouns by language"){
				$wordlist = $wordlist . $foundWord . "<br>";
			}
		}
		echo "$wordlist";
		fwrite ($fp, $wordlist);
		
		
		// find the next page url
		$oldlinkpos = 0;	//holds beginning position of previously found matching link
		$newlinkpos = 0;	//holds beginning position of newly found matching link
		$linklength = 300;	//set at 300 so the loop can begin. In "for" loop, condition set in case wrong url was found
						  	//or in other words, if something was found that was too long to be a url, program would keep searching
		$linkending = 0;
		
		for($m = 0; $m <=8 && $linklength >= 300 && $newlinkpos !== false; $m++){
			$oldlinkpos = $newlinkpos + 1;
			$newlinkpos = strpos ($data, "/w/index.php?title=Category:English_uncountable_nouns&pagefrom", $oldlinkpos); //
			
			if ($newlinkpos !==false) {
				$linkending = strpos ($data, "#mw-pages", $newlinkpos);
			}

			if($newlinkpos !== false && $linkending !== false){
				$linklength = $linkending + 8 - $newlinkpos;
				$nextURL = "https://en.wiktionary.org" . substr($data, $newlinkpos, $linklength);
				//echo "$nextURL <br>";
				//echo "ROUND $m<br>newlinkpos: $newlinkpos <br>linkending: $linkending <br>linklength: $linklength<br><br>";
			}
			else{
				echo "The next link was not found. <br>";
				$linkfound = false;
			}
			
			if ($linklength >= 300 && $m == 8){
				echo "The next link was not found. <br>";
				$linkfound = false;
			}
		}
	}
		fclose($fp);
?>
