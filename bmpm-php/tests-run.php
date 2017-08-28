<?php

$debug = false; // for debugging the test suite
error_reporting(E_ALL);

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
  return BMPM::LANGUAGE_AUTO;
}

$inputFileName = $argv[1];

// process each line in the input file

$fails = 0;
$lines = file($inputFileName);
for ($ln=0; $ln<count($lines); $ln++){
  if (($ln+1)%100 == 0) echo "$inputFileName::: " . ($ln+1) . " of " . count($lines) . "\n";
  
  $comps = explode("\t", trim($lines[$ln], "\n")); // some lines may have trailing tabs which we must keep
  if (count($comps) != 5) {
    echo "$inputFileName:" . ($ln+1) . " invalid line: " . $lines[$ln] . "\n";
    $fails++;
    continue;
  }

  list($name, $typeName, $languageName, $bmpmExpected, $soundexExpected) = $comps;

  if ($debug) {
    echo "$inputFileName:" . ($ln+1) . " [[$name $typeName $languageName]]\n";
  }

  $bmpmActual = BMPM::getPhoneticEncoding($name, typeForName($typeName), languageForName($languageName));
  if ($bmpmActual != $bmpmExpected) {
    echo "$inputFileName:" . ($ln+1) . " BMPM::getPhoneticEncoding failed: [[$name $typeName $languageName]]\n[[expected]] $bmpmExpected\n  [[actual]] $bmpmActual\n";
    $fails++;
  }

  $soundexActual = BMPM::getDaitchMotokoffSoundex($name);
  if ($soundexExpected != $soundexActual) {
    echo "$inputFileName:" . ($ln+1) . " BMPM::getDaitchMotokoffSoundex failed: [[$name]]\n[[expected]] $soundexExpected\n  [[actual]] $soundexActual\n";
    $fails++;
  }
}

echo "$fails tests failed\n";
?>