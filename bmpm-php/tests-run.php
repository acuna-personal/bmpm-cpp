<?php

$debug = false; // for debugging the test suite
error_reporting(E_ALL);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

if ($debug) {
  set_time_limit(60);
}

include_once "BMPM.php";

function typeForName($typeName) {
  switch ($typeName) {
    case "sep":
      return BMPM::TYPE_SEPHARDIC;
    case "ash":
      return BMPM::TYPE_ASHKENAZI;
  }
  return BMPM::TYPE_GENERIC;
}

function languageForName($languageName) {
  switch ($languageName) {
    case "arabic":
      return BMPM::LANGUAGE_ARABIC;
    case "cyrillic":
      return BMPM::LANGUAGE_CYRILLIC;
    case "czech":
      return BMPM::LANGUAGE_CZECH;
    case "dutch":
      return BMPM::LANGUAGE_DUTCH;
    case "english":
      return BMPM::LANGUAGE_ENGLISH;
    case "french":
      return BMPM::LANGUAGE_FRENCH;
    case "german":
      return BMPM::LANGUAGE_GERMAN;
    case "greek":
      return BMPM::LANGUAGE_GREEK;
    case "greeklatin":
      return BMPM::LANGUAGE_GREEKLATIN;
    case "hebrew":
      return BMPM::LANGUAGE_HEBREW;
    case "hungarian":
      return BMPM::LANGUAGE_HUNGARIAN;
    case "italian":
      return BMPM::LANGUAGE_ITALIAN;
    case "latvian":
      return BMPM::LANGUAGE_LATVIAN;
    case "polish":
      return BMPM::LANGUAGE_POLISH;
    case "portuguese":
      return BMPM::LANGUAGE_PORTUGUESE;
    case "romanian":
      return BMPM::LANGUAGE_ROMANIAN;
    case "russian":
      return BMPM::LANGUAGE_RUSSIAN;
    case "spanish":
      return BMPM::LANGUAGE_SPANISH;
    case "turkish":
      return BMPM::LANGUAGE_TURKISH;
  }
  return BMPM::LANGUAGE_ANY;
}

function beep() {
  echo "\x07";
}

$inputFileName = $argv[1];

// process each line in the input file

$fails = 0;

$handle = fopen($inputFileName, "r");
if (!$handle) {
  echo "Unable to open: $inputFileName\n";
  exit(1);
}

$ln = 1;
while (($line = fgets($handle)) !== false) {
  if ($ln % 100 == 0) {
    echo "$inputFileName:$ln\n";
  }
  
  $comps = explode("\t", trim($line, "\n\r")); // some lines may have trailing tabs which we must keep
  if (count($comps) != 6) {
    echo "$inputFileName:" . $ln . " invalid line: " . $lines[$ln] . "\n";
    $fails++;
    continue;
  }

  list($name, $typeName, $languageName, $bmpmExactExpected, $bmpmApproxExpected, $soundexExpected) = $comps;

  if ($debug) {
    echo "$inputFileName:$ln [[$name $typeName $languageName]]\n";
  }

  try {
    // TODO: Order shouldn't matter for any of these
    
    $bmpmApproxActual = BMPM::getPhoneticEncoding($name, typeForName($typeName), languageForName($languageName), false);
    if ($bmpmApproxActual != $bmpmApproxExpected) {
      echo "$inputFileName:" . $ln . " BMPM::getPhoneticEncoding failed: [[$name $typeName $languageName approx]]\n[[expected]] $bmpmApproxExpected\n  [[actual]] $bmpmApproxActual\n";
      $fails++;
      beep();
    }

    $bmpmExactActual = BMPM::getPhoneticEncoding($name, typeForName($typeName), languageForName($languageName), true);
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

?>