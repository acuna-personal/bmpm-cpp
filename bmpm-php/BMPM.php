<?php

// bmpm.php

include_once "dmsoundex.php";
include_once "phoneticengine.php";
include_once "languages.php";

abstract class BMPM {
	const TYPE_SEPHARDIC = "sep";
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
			$languageCode
			);
		$numbers = PhoneticNumbers($result);

		return $numbers;
	}

	public static function getDaitchMotokoffSoundex($name) {
		return trim(soundx_name($name));
	}

	function getType() {
		return $this->_type;
	}

	function setType($type) {
		$this->_type = $type;
	}

	function getLanguageNames() {
		return $this->_languageNames;
	}

	function setLanguageNames($languageNames) {
		$this->_languageNames = $languageNames;
	}

	function handlesLanguage($language) {
		if ($this->_languageNames == null) {
			echo "handlesLanguage: Null languages list\n";
			debug_print_backtrace();
			return false;
		}
		return in_array($language, $this->_languageNames);
	}

	function getApprox() {
		return $this->_approx;
	}

	function setApprox($approx) {
		$this->_approx = $approx;
	}

	function getApproxCommon() {
		return $this->_approxCommon;
	}

	function setApproxCommon($approxCommon) {
		$this->_approxCommon = $approxCommon;
	}

	function getExact() {
		return $this->_exact;
	}

	function setExact($exact) {
		$this->_exact = $exact;
	}

	function getExactCommon() {
		return $this->_exactCommon;
	}

	function setExactCommon($exactCommon) {
		$this->_exactCommon = $exactCommon;
	}

	function getRules() {
		return $this->_rules;
	}

	function setRules($rules) {
		$this->_rules = $rules;
	}

	function getLanguageRules() {
		return $this->_languageRules;
	}

	function setLanguageRules($languageRules) {
		$this->_languageRules = $languageRules;
	}

	function getAllLanguagesBitmap() {
		return $this->_allLanguagesBitmap;
	}

	function setAllLanguagesBitmap($allLanguagesBitmap) {
		$this->_allLanguagesBitmap = $allLanguagesBitmap;
	}
}

include_once "BMPMGeneric.php";
include_once "BMPMSephardic.php";
include_once "BMPMAshkenazi.php";

?>