<?php

// bmpm.php

include_once "dmsoundex.php";
include_once "phoneticengine.php";
include_once "languages.php";

abstract class BMPM {
	const TYPE_SEPHARDIC = 'sep';
	const TYPE_ASHKENAZI = "ash";
	const TYPE_GENERIC = "gen";

	const LANGUAGE_ANY = "any";
	const LANGUAGE_ARABIC = "arabic";
	const LANGUAGE_CYRILLIC = "cyrillic";
	const LANGUAGE_CZECH = "czech";
	const LANGUAGE_DUTCH = "dutch";
	const LANGUAGE_ENGLISH = "english";
	const LANGUAGE_FRENCH = "french";
	const LANGUAGE_GERMAN = "german";
	const LANGUAGE_GREEK = "greek";
	const LANGUAGE_GREEKLATIN = "greeklatin";
	const LANGUAGE_HEBREW = "hebrew";
	const LANGUAGE_HUNGARIAN = "hungarian";
	const LANGUAGE_ITALIAN = "italian";
	const LANGUAGE_LATVIAN = "latvian";
	const LANGUAGE_POLISH = "polish";
	const LANGUAGE_PORTUGUESE = "portuguese";
	const LANGUAGE_ROMANIAN = "romanian";
	const LANGUAGE_RUSSIAN = "russian";
	const LANGUAGE_SPANISH = "spanish";
	const LANGUAGE_TURKISH = "turkish";

	const MATCHING_APPROXIMATE = "approx";
	const MATCHING_EXACT = "exact";
	const MATCHING_HEBREW = "hebrew";

	private $_type;
	private $_languageNames;
	private $_rules;
	private $_exact;
	private $_exactCommon;
	private $_approx;
	private $_approxCommon;
	private $_hebrew;
	private $_hebrewCommon;
	private $_languageRules;
	private $_allLanguagesBitmap;

	public static function getBMPM($type = BMPM::TYPE_GENERIC) {
		static $_bmpmAshkenazi = null;
		static $_bmpmGeneric = null;
		static $_bmpmSephardic = null;
		switch ($type) {
			case BMPM::TYPE_SEPHARDIC:
				if ($_bmpmSephardic === null) {
					$_bmpmSephardic = new BMPMSephardic();
				}

				return $_bmpmSephardic;

			case BMPM::TYPE_ASHKENAZI:
				if ($_bmpmAshkenazi === null) {
					$_bmpmAshkenazi = new BMPMAshkenazi();
				}

				return $_bmpmAshkenazi;

			case BMPM::TYPE_GENERIC:
			default:
				if ($_bmpmGeneric === null) {
					$_bmpmGeneric = new BMPMGeneric();
				}

				return $_bmpmGeneric;
		}
	}

	public static function getAllBMPMData() {
		return array(
			BMPM::TYPE_GENERIC => BMPM::getBMPM(BMPM::TYPE_GENERIC)->getData(),
			BMPM::TYPE_ASHKENAZI => BMPM::getBMPM(BMPM::TYPE_ASHKENAZI)->getData(),
			BMPM::TYPE_SEPHARDIC => BMPM::getBMPM(BMPM::TYPE_SEPHARDIC)->getData(),
			);
	}

	public function getData() {
		return utf8ize(array(
			'languageNames' => $this->getLanguageNames(),
			'rules' => $this->getRules(),
			'exact' => $this->getExact(),
			'exactCommon' => $this->getExactCommon(),
			'approx' => $this->getApprox(),
			'approxCommon' => $this->getApproxCommon(),
			'hebrew' => $this->getHebrew(),
			'hebrewCommon' => $this->getHebrewCommon(),
			'languageRules' => $this->getLanguageRules(),
			'allLanguagesBitmap' => $this->getAllLanguagesBitmap(),
			));
	}

	public static function getDMData() {
		return soundx_data();
	}

	public static function getPhoneticEncoding($name, $type = TYPE_GENERIC, $language = LANGUAGE_ANY, $matching = MATCHING_APPROXIMATE) {
		$bmpm = BMPM::getBMPM($type);
		if ($bmpm == null) {
			echo "Null bmpm";
			return null;
		}

		// Fall back to TYPE_GENERIC if we don't have matching for that language in that type.
		if (!$bmpm->handlesLanguage($language)) {
			$type = BMPM::TYPE_GENERIC;
			$bmpm = BMPM::getBMPM($type);
			if ($bmpm == null) {
				echo "Null bmpm";
				return null;
			}
		}

		$rules = $bmpm->getRules();
		$matchingRules = $bmpm->getMatchingRules($matching);
		$matchingRulesCommon = $bmpm->getMatchingRulesCommon($matching);
		$allLanguagesBitmap = $bmpm->getAllLanguagesBitmap();
		$languages = $bmpm->getLanguageNames();
		$languageRules = $bmpm->getLanguageRules();

		if ($language == BMPM::LANGUAGE_ANY || $language == "") {
			$languageCode = Language_UTF8($name, $languageRules, $allLanguagesBitmap);
		} else {
			$languageCode = LanguageCode($language, $languages, $allLanguagesBitmap);
		}

		$idx = LanguageIndexFromCode($languageCode, $languages);

		//echo "$name => " . LanguageName($idx, $languages) . "\n";

		if ($matching != BMPM::MATCHING_HEBREW && !$matchingRules) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No matching rules for ($name, $type, $language, $matching)\n";
			return null;
		}

		if (!$matchingRulesCommon) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No matching rules common for ($name, $type, $language, $matching)\n";
			return null;
		}

		if (!isset($rules[$idx])) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No rules for ($name, $type, $language, $matching)\n";
			return null;
		}

		if ($matching != BMPM::MATCHING_HEBREW && !isset($matchingRules[$idx])) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No matching rules for ($name, $type, $language, $matching)\n";
			return null;
		}

		$result = Phonetic_UTF8(
			$name,
			$type,
			$allLanguagesBitmap,
			$languageRules,
			$rules[$idx],
			$matchingRulesCommon,
			$matching == BMPM::MATCHING_HEBREW ? "" : $matchingRules[$idx],
			$languageCode,
			$matching == BMPM::MATCHING_EXACT // concatenate iff it is an exact match
			);
		$numbers = PhoneticNumbers($result);

		return $numbers;
	}

	public function getMatchingRules($matching) {
		switch ($matching) {
			case BMPM::MATCHING_APPROXIMATE:
				return $this->getApprox();
			case BMPM::MATCHING_HEBREW:
				return $this->getHebrew();
			case BMPM::MATCHING_EXACT:
				return $this->getExact();
		}
		return null;
	}

	public function getMatchingRulesCommon($matching) {
		switch ($matching) {
			case BMPM::MATCHING_APPROXIMATE:
				return $this->getApproxCommon();
			case BMPM::MATCHING_HEBREW:
				return $this->getHebrewCommon();
			case BMPM::MATCHING_EXACT:
				return $this->getExactCommon();
		}
		return null;
	}

	public static function getDaitchMotokoffSoundex($name) {
		return trim(soundx_name(remove_accents($name)));
	}

	public function getType() {
		return $this->_type;
	}

	public function setType($type) {
		$this->_type = $type;
	}

	public function getLanguageNames() {
		return $this->_languageNames;
	}

	public function setLanguageNames($languageNames) {
		$this->_languageNames = $languageNames;
	}

	public function handlesLanguage($language) {
		if ($this->_languageNames == null) {
			echo "handlesLanguage: Null languages list\n";
			debug_print_backtrace();
			return false;
		}
		return in_array($language, $this->_languageNames);
	}

	public function getApprox() {
		return $this->_approx;
	}

	public function setApprox($approx) {
		$this->_approx = $approx;
	}

	public function getApproxCommon() {
		return $this->_approxCommon;
	}

	public function setApproxCommon($approxCommon) {
		$this->_approxCommon = $approxCommon;
	}

	public function getExact() {
		return $this->_exact;
	}

	public function setExact($exact) {
		$this->_exact = $exact;
	}

	public function getExactCommon() {
		return $this->_exactCommon;
	}

	public function setExactCommon($exactCommon) {
		$this->_exactCommon = $exactCommon;
	}

	public function getHebrew() {
		return $this->_hebrew;
	}

	public function setHebrew($exact) {
		$this->_hebrew = $hbrew;
	}

	public function getHebrewCommon() {
		return $this->_hebrewCommon;
	}

	public function setHebrewCommon($hebrewCommon) {
		$this->_hebrewCommon = $hebrewCommon;
	}

	public function getRules() {
		return $this->_rules;
	}

	public function setRules($rules) {
		$this->_rules = $rules;
	}

	public function getLanguageRules() {
		return $this->_languageRules;
	}

	public function setLanguageRules($languageRules) {
		$this->_languageRules = $languageRules;
	}

	public function getAllLanguagesBitmap() {
		return $this->_allLanguagesBitmap;
	}

	public function setAllLanguagesBitmap($allLanguagesBitmap) {
		$this->_allLanguagesBitmap = $allLanguagesBitmap;
	}
}

/***IFNCPP***/

// From WordPress via https://stackoverflow.com/questions/1017599/how-do-i-remove-accents-from-characters-in-a-php-string
function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $newString = strtr($string, $chars);

    return $newString;
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

/***ENDIFNCPP***/

function BMPMTypeForName($typeName) {
  switch ($typeName) {
    case "sep":
      return BMPM::TYPE_SEPHARDIC;
    case "ash":
      return BMPM::TYPE_ASHKENAZI;
  }
  return BMPM::TYPE_GENERIC;
}

function BMPMLanguageForName($languageName) {
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

include_once "BMPMGeneric.php";
include_once "BMPMSephardic.php";
include_once "BMPMAshkenazi.php";

?>