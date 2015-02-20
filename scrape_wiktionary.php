<?php
	// start with the first page of uncountable nouns
	$nextURL = "https://en.wiktionary.org/wiki/Category:English_uncountable_nouns";
	
	// regular expression for "get anything inside an HTML li tag"
	$regex = "~<li>(.+?)</li>~";
	// regular expression for "get the URL link for the next page" (kinda)
	$nextLinkRegex = "~/w/index.php?(.+?)#mw-pages~";  
	
	// do this 3 times (for now while debugging)
	for ($i=0; $i<3; $i++) {
		// read the entire webpage and store as a string
		$data = file_get_contents($nextURL);
		// find all the <li> content
		preg_match_all($regex,$data,$matches);
		
		// print each <li> entry to the page
		foreach ($matches[1] as $match) {
			echo "$match <br>";
		}
		// find the next page url
		preg_match_all($nextLinkRegex,$data,$nextLink);
		// reassign $nextURL for the next loop
		$nextURL = "https://en.wiktionary.org" . $nextLink[0][1];
	}
?>
