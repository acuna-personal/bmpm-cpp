<?php

// bmpm.php

include_once "dmsoundex.php";
include_once "phoneticengine.php";
include_once "languages.php";

abstract class BMPM {
	public const TYPE_SEPHARDIC = "sep";
	public const TYPE_ASHKENAZI = "ash";
	public const TYPE_GENERIC = "gen";

	public const LANGUAGE_ANY = "any";
	public const LANGUAGE_ARABIC = "arabic";
	public const LANGUAGE_CYRILLIC = "cyrillic";
	public const LANGUAGE_CZECH = "czech";
	public const LANGUAGE_DUTCH = "dutch";
	public const LANGUAGE_ENGLISH = "english";
	public const LANGUAGE_FRENCH = "french";
	public const LANGUAGE_GERMAN = "german";
	public const LANGUAGE_GREEK = "greek";
	public const LANGUAGE_GREEKLATIN = "greeklatin";
	public const LANGUAGE_HEBREW = "hebrew";
	public const LANGUAGE_HUNGARIAN = "hungarian";
	public const LANGUAGE_ITALIAN = "italian";
	public const LANGUAGE_LATVIAN = "latvian";
	public const LANGUAGE_POLISH = "polish";
	public const LANGUAGE_PORTUGUESE = "portuguese";
	public const LANGUAGE_ROMANIAN = "romanian";
	public const LANGUAGE_RUSSIAN = "russian";
	public const LANGUAGE_SPANISH = "spanish";
	public const LANGUAGE_TURKISH = "turkish";

	private $_type;
	private $_languageNames;
	private $_rules;
	private $_exact;
	private $_exactCommon;
	private $_approx;
	private $_approxCommon;
	private $_languageRules;
	private $_allLanguagesBitmap;

	private static $_bmpmAshkenazi = null;
	private static $_bmpmGeneric = null;
	private static $_bmpmSephardic = null;

	public static function getBMPM($type = BMPM::TYPE_GENERIC) {
		$bmpm = null;
		switch ($type) {
			case BMPM::TYPE_SEPHARDIC:
				if (BMPM::$_bmpmSephardic == null) {
					BMPM::$_bmpmSephardic = new BMPMSephardic();
				}

				$bmpm = BMPM::$_bmpmSephardic;
				break;

			case BMPM::TYPE_ASHKENAZI:
				if (BMPM::$_bmpmAshkenazi == null) {
					BMPM::$_bmpmAshkenazi = new BMPMAshkenazi();
				}

				$bmpm = BMPM::$_bmpmAshkenazi;
				break;

			case BMPM::TYPE_GENERIC:
			default:
				if (BMPM::$_bmpmGeneric == null) {
					BMPM::$_bmpmGeneric = new BMPMGeneric();
				}

				$bmpm = BMPM::$_bmpmGeneric;
		}
		return $bmpm;
	}

	public static function getPhoneticEncoding($name, $type = TYPE_GENERIC, $language = LANGUAGE_ANY, $exact = true) {
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
		$equivalencyRules = $exact ? $bmpm->getExact() : $bmpm->getApprox();
		$equivalencyRulesCommon = $exact ? $bmpm->getExactCommon() : $bmpm->getApproxCommon();
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

		if (!$equivalencyRules) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No equivalencyRules for ($name, $type, $language, $exact)\n";
			return null;
		}

		if (!$equivalencyRulesCommon) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No equivalencyRulesCommon for ($name, $type, $language, $exact)\n";
			return null;
		}

		if (!isset($rules[$idx])) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No rules for ($name, $type, $language, $exact)\n";
			return null;
		}

		if (!isset($equivalencyRules[$idx])) {
			echo "$name => " . LanguageName($idx, $languages) . "\n";
			echo "getPhoneticEncoding: No equivalencyRules for ($name, $type, $language, $exact)\n";
			return null;
		}

		$result = Phonetic_UTF8(
			$name,
			$type,
			$allLanguagesBitmap,
			$languageRules,
			$rules[$idx],
			$equivalencyRulesCommon,
			$equivalencyRules[$idx],
			$languageCode,
			$exact // concatenate iff it is an exact match
			);
		$numbers = PhoneticNumbers($result);

		return $numbers;
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


include_once "BMPMGeneric.php";
include_once "BMPMSephardic.php";
include_once "BMPMAshkenazi.php";

?>