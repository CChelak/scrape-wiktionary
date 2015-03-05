<?php
	// start with the first page of uncountable nouns
	$nextURL = "https://en.wiktionary.org/wiki/Category:English_uncountable_nouns";
	
	// regular expression for "get anything inside an HTML li tag"
	$regex = "~<li>(.+?)</li>~";
	// regular expression for "get the URL link for the next page" (kinda)
	$nextLinkRegex = "~/w/index.php?(.+?)#mw-pages~";
	$linkfound = true;
	
	// do this 3 times (for now while debugging)
	for ($i=0; $i<10000 && $linkfound == true; $i++) {
		//if($i >= 1) {echo "say anything <br>";}
		// read the entire webpage and store as a string
		$data = file_get_contents($nextURL);
		//if($i >= 1){echo "$data<br><br>";}
		
		/* //A small sample of what is inside $data. It checks to see where the '&' symbols were for a previous test
		for ($k = 0; $k<1000; $k++){
			if ($data[$k] === '&'){
				echo "*******$data[$i]******";
			}
			else{
				echo " $data[$k] ";
			}
		}
		echo "<br><br>";*/
		
		for ($j = 0; $j<2; $j++){
		  $foundamp = strpos($data, "amp;");
			if ($foundamp !== false){
			$data = str_replace("amp;", "", $data);
		  }
		}		
		
		// find all the <li> content
		preg_match_all($regex,$data,$matches);
		
		// print each <li> entry to the page
		foreach ($matches[1] as $match) {
			echo "$match <br>";
		}
		
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
?>
