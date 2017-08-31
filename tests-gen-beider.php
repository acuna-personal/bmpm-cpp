<?php

// Convert data from secret Beider files to our test format

$inputFileName = $argv[1];
$outputFileName = $argv[2];
$languages = explode('+', $argv[3]);
$type = $argv[4];

$handle = fopen($inputFileName, "r");
if (!$handle) {
  echo "Unable to open: $inputFileName\n";
  exit(1);
}

$outputHandle = fopen($outputFileName, "w");
if (!$handle) {
  echo "Unable to open: $outputFileName\n";
  exit(1);
}

while (($line = fgets($handle)) !== false) {
	$comps = explode("\t", trim($line, "\n\r")); // some lines may have trailing tabs which we must keep

	/*
	1. name
	2. phonetic code (exact match)
	3. same as 2 but formatted slightly differently
	4. phonetic code (approximate match)
	5. same as 4 but formatted slightly differently
	6. phonetic code (hebrew match)
	7. same as 6 but formatted slightly differently
	8. DM code
	*/

	list ($name, $phoneticExactRegex, $phoneticExact, $phoneticApproxRegex, $phoneticApprox, $phoneticHebrewRegex, $phoneticHebrew, $soundex) = $comps;

	// Currently not testing Hebrew
	// Currently don't understand expected results of multilingual tests ("spanish+german+polish+romanian")
	if (count($languages) == 1) {
		$languageName = $languages[0];
		fputs($outputHandle, "$name\t$type\t$languageName\t$phoneticExact\t$phoneticApprox\t$soundex\n");
	}
}

fclose($handle);

?>