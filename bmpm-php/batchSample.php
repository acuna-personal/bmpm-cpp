<?php

/*
 *
 * Copyright Alexander Beider and Stephen P. Morse, 2008
 *
 * This file is part of the Beider-Morse Phonetic Matching (BMPM) System. 

 * BMPM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BMPM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BMPM.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

error_reporting(E_ALL);

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

$inputFileName = $argv[1];
$outputFileName = $argv[2];

// open input and output files

if (!file_exists($outputFileName)) {
  if (!($handle = fopen($outputFileName,"x"))) {
    echo "error creating and opening ".$outputFileName;
    exit;
  }
} else {
  if (!($handle = fopen($outputFileName,"w"))) {
    echo "error opening ".$outputFileName;
    exit;
  }
}

// process each line in the input file

$lines = file($inputFileName);
for ($ln=0; $ln<count($lines); $ln++){
  if (($ln+1)%100 == 0) echo ($ln+1) . " of " . count($lines) . "<br>";
  
  $comps = explode("\t", trim($lines[$ln]));
  $name = $comps[0];
  $typeName = $comps[1];
  $languageName = $comps[2];

  $bmpm = BMPM::getPhoneticEncoding($name, typeForName($typeName), languageForName($languageName));
  $soundex = BMPM::getDaitchMotokoffSoundex($name);
  fputs($handle, "$name\t$bmpm\t$soundex\n");
}

echo "Done\n";
?>