<?php

/***IFNCPP***/

$debug = false; // for debugging the test suite
error_reporting(E_ALL);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

if ($debug) {
  set_time_limit(60);
}

/***ENDIFNCPP***/

include_once "BMPM.php";


function beep() {
  echo "\x07";
}

function main($argc, $argv) {
  /***IFNCPP***/
  global $debug;
  /***ENDIFNCPP***/


  $inputFileName = $argv[1];

  // process each line in the input file

  $fails = 0;

  $handle = fopen($inputFileName, "r");
  if (!$handle) {
    echo "Unable to open: $inputFileName\n";
    return(1);
  }

  $ln = 1;
  while (($line = fgets($handle)) !== false) {
    if ($ln % 100 == 0) {
      echo "$inputFileName:$ln\n";
    }
    
    $comps = explode("\t", trim($line, "\n\r")); // some lines may have trailing tabs which we must keep
    if (count($comps) != 6) {
      echo "$inputFileName:" . $ln . " invalid line: " . $line . "\n";
      $fails++;
      beep();
      continue;
    }

    list($name, $typeName, $languageName, $bmpmExactExpected, $bmpmApproxExpected, $soundexExpected) = $comps;

    if ($debug) {
      echo "$inputFileName:$ln [[$name $typeName $languageName]]\n";
    }

    try {
      // TODO: Order shouldn't matter for any of these
      
      $bmpmApproxActual = BMPM::getPhoneticEncoding($name, BMPMTypeForName($typeName), BMPMLanguageForName($languageName), false);
      if ($bmpmApproxActual != $bmpmApproxExpected) {
        echo "$inputFileName:" . $ln . " BMPM::getPhoneticEncoding failed: [[$name $typeName $languageName approx]]\n[[expected]] $bmpmApproxExpected\n  [[actual]] $bmpmApproxActual\n";
        $fails++;
        beep();
      }

      $bmpmExactActual = BMPM::getPhoneticEncoding($name, BMPMTypeForName($typeName), BMPMLanguageForName($languageName), true);
      if ($bmpmExactActual != $bmpmExactExpected) {
        echo "$inputFileName:" . $ln . " BMPM::getPhoneticEncoding failed: [[$name $typeName $languageName exact]]\n[[expected]] $bmpmExactExpected\n  [[actual]] $bmpmExactActual\n";
        $fails++;
        beep();
      }

      $soundexExpected = trim($soundexExpected);
      $soundexActual = BMPM::getDaitchMotokoffSoundex($name);
      if ($soundexExpected != $soundexActual) {
        echo "$inputFileName:" . $ln . " BMPM::getDaitchMotokoffSoundex failed: [[$name]]\n[[expected]] $soundexExpected\n  [[actual]] $soundexActual\n";
        $fails++;
        beep();
      }
    } catch (Exception $e) {
      echo "$inputFileName:$ln [[$name $typeName $languageName]]\n";
      echo $e->getMessage() . "\n";
      echo $e->getTraceAsString() . "\n";
      $fails++;
      beep();
    }

    $ln++;
  }

  echo "$fails tests failed\n";

  return 0;
}

/***IFNCPP***/

$ret = main($argc, $argv);
exit($ret);

/***ENDIFNCPP***/

?>