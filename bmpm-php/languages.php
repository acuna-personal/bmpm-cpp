<?php

function Language($name, $rules, $allLanguagesBitmap) {
  // convert $name to utf8
  $name = utf8_encode($name); // takes care of things in the upper half of the ascii chart, e.g., u-umlaut
  if (strpos($name, "&") !== false) { // takes care of ampersand-notation encoding of unicode (&#...;)
    $name = html_entity_decode($name, ENT_NOQUOTES, "UTF-8");
  }
  return Language_UTF8($name, $rules, $allLanguagesBitmap);
}

function Language_UTF8($name, $rules, $allLanguagesBitmap) {
  //    $name = mb_strtolower($name, mb_detect_encoding($name));
  $name = mb_strtolower($name, "UTF-8");
  $choicesRemaining = $allLanguagesBitmap;
  for ($i=0; $i<count($rules); $i++) {
    list($letters, $languages, $accept) = $rules[$i];
  //echo "testing name=$name letters=$letters languages=$languages accept=$accept<br>";
    if (preg_match($letters, $name)) {
      if ($accept) {
        $choicesRemaining &= $languages;
      } else { // reject
        $choicesRemaining &= (~$languages) % ($allLanguagesBitmap+1);
      }
    }
  }
  if ($choicesRemaining == 0) {
    $choicesRemaining = 1;
  }
  return $choicesRemaining;
}

function LanguageIndex($langName, $languages) {
  for ($i=0; $i<count($languages); $i++) {
    if ($languages[$i] == $langName) {
      return $i;
    }
  }
  return 0; // name not found
}

function LanguageName($index, $languages) {
  if ($index < 0 || $index > count($languages)) {
    return "any"; // index out of range
  }
  return $languages[$index];
}

function LanguageCode($langName, $languages) {
  return pow(2, LanguageIndex($langName, $languages));
}

function LanguageIndexFromCode($code, $languages) {
  if ($code < 0 || $code > pow(2, count($languages)-1)) { // code out of range
    return 0;
  }
  $log = log($code, 2);
  $result = floor($log);
  if ($result != $log) { // choice was more than one language, so use "any"
    $result = LanguageIndex("any", $languages);
  }
  return $result;
}
?>
