<?php

// bmpm.php
// To simplify includes

include "dmsoundex.php";
include "phoneticengine.php";
include "languages.php";

abstract class BMPM {
	const TYPE_SEPHARDIC = "sep";
	const TYPE_ASHKENAZI = "ash";
	const TYPE_ANY = "any";

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
	private $_languages;
	private $_rules;
	private $_approx;
	private $_approxCommon;
	private $_languageRules;

	private static $_bmpmAshkenazi = null;
	private static $_bmpmGeneric = null;
	private static $_bmpmSephardic = null;

	function getBMPM($type = BMPM::TYPE_ANY) {
		// Fall back to TYPE_ANY if we don't have matching for that language in that type.
		$languages = _getLanguages($type);
		if (!in_array($language, $languages)) {
			$type = TYPE_ANY;
			$languages = _getLanguages($type);
		}

		switch ($type) {
			case BMPM::TYPE_SEPHARDIC:
				if ($_bmpmSephardic == null) {
					$_bmpmSephardic = new BMPMSephardic();
				}

				return $_bmpmSephardic;

			case BMPM::TYPE_ASHKENAZI:
				if ($_bmpmAshkenazi == null) {
					$_bmpmAshkenazi = new BMPMSephardic();
				}

				return $_bmpmAshkenazi;

			case BMPM::TYPE_GENERIC:
			default:
				if ($_bmpmGeneric == null) {
					$_bmpmGeneric = new BMPMGeneric();
				}

				return $_bmpmGeneric;

		}
		return null;
	}

	function getType() {
		return $this->_type;
	}

	function setType($type) {
		$this->_type = $type;
	}

	function getLanguages() {
		return $this->_languages;
	}

	function setLanguages($languages) {
		$this->_languages = $languages;
	}

	function getApprox() {
		return $this->_approx;
	}

	function setApprox($approx) {
		$this->_approx = $approx;
	}

	function getRules() {
		return $this->_rules;
	}

	function setRules($rules) {
		$this->_rules = $rules;
	}

	function getLanguageRules() {
		$this->_languageRules;
	}

	function setLanguageRules($languageRules) {
		$this->_languageRules = $languageRules;
	}
}


class BMPMGeneric extends BMPM {
	function __construct() {
		$this->setType(BMPM::TYPE_GENERIC);
		include "gen/approxcommon.php";
		include "gen/lang.php";

		include "gen/rulesany.php";
		include "gen/rulesarabic.php";
		include "gen/rulescyrillic.php";
		include "gen/rulesczech.php";
		include "gen/rulesdutch.php";
		include "gen/rulesenglish.php";
		include "gen/rulesfrench.php";
		include "gen/rulesgerman.php";
		include "gen/rulesgreek.php";
		include "gen/rulesgreeklatin.php";
		include "gen/ruleshebrew.php";
		include "gen/ruleshungarian.php";
		include "gen/rulesitalian.php";
		include "gen/ruleslatvian.php";
		include "gen/rulespolish.php";
		include "gen/rulesportuguese.php";
		include "gen/rulesromanian.php";
		include "gen/rulesrussian.php";
		include "gen/rulesspanish.php";

		$this->setRules($rules);

		include "gen/approxany.php";
		include "gen/approxarabic.php";
		include "gen/approxcyrillic.php";
		include "gen/approxczech.php";
		include "gen/approxdutch.php";
		include "gen/approxenglish.php";
		include "gen/approxfrench.php";
		include "gen/approxgerman.php";
		include "gen/approxgreek.php";
		include "gen/approxgreeklatin.php";
		include "gen/approxhebrew.php";
		include "gen/approxhungarian.php";
		include "gen/approxitalian.php";
		include "gen/approxlatvian.php";
		include "gen/approxpolish.php";
		include "gen/approxportuguese.php";
		include "gen/approxromanian.php";
		include "gen/approxrussian.php";
		include "gen/approxspanish.php";

		$this->setApprox($approx);

		return $this;
	}

	static function getPhoneticEncoding($name, $type = TYPE_ANY, $language = LANGUAGE_AUTO) {
		$bmpm = $this->getBMPM($type);
		if ($bmpm == null) {
			echo "Null bmpm";
			return null;
		}

		$rules = $this->getRules($rules);
		$approx = $this->getApprox($type);
		$approxCommon = $this->getApproxCommon($type);
		$allLanguagesBitmap = $this->getAllLanguagesBitmap($type);

		$languageCode = LanguageCode($language, $languages, $allLanguagesBitmap);
		if ($languageName == "auto" || $language == "") {
			$languageCode = Language_UTF8($name, $this->$languageRules, $allLanguagesBitmap);
		} else {
			$languageCode = $languageCode;
		}

		$result = Phonetic_UTF8(
			$name,
			$rules[LanguageIndexFromCode($languageCode, $languages)],
			$approxCommon,
			$approx[LanguageIndexFromCode($languageCode, $languages)],
			$languageCode2,
			$type
			);
		$numbers = PhoneticNumbers($result);

		return $numbers;
	}

	static function getDaitchMotokoffSoundex($name) {
		return soundx_name($name);
	}
}

?>