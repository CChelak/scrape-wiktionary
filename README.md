# scrape-wiktionary
Scrape Wiktionary for words with certain parts of speech

There are lists of words with particular parts of speech on Wiktionary. This program scrapes the appropriate web pages and stores the results in a file.

The basic pseudocode is:

Copy contents of page: "https://en.wiktionary.org/wiki/Category:English_uncountable_nouns"
Strip out just the word entries in the list (using regular expressions??)
Write those words to a file
Identify the url for the "next 200" words
Repeat until all pages have been extracted


This is a first stab at github and php for me, so I could use some tips.
-Thad
