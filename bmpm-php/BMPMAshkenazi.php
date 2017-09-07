<?php

class BMPMAshkenazi extends BMPM {
	function __construct() {
		$this->setType(BMPM::TYPE_ASHKENAZI);
		
		include "ash/approxcommon.php";
		$this->setApproxCommon($approxCommon);
		
		include "ash/exactcommon.php";
		$this->setExactCommon($exactCommon);
		
		include "ash/hebrewcommon.php";
		$this->setHebrewCommon($hebrewCommon);

		include "ash/languagenames.php";
		$this->setLanguageNames($languages);
		
		include "ash/lang.php";
		$this->setAllLanguagesBitmap($all);
		$this->setLanguageRules($languageRules);

		$rules = array();
		include "ash/rulesany.php";
		include "ash/rulescyrillic.php";
		include "ash/rulesenglish.php";
		include "ash/rulesfrench.php";
		include "ash/rulesgerman.php";
		include "ash/ruleshebrew.php";
		include "ash/ruleshungarian.php";
		include "ash/rulespolish.php";
		include "ash/rulesromanian.php";
		include "ash/rulesrussian.php";
		include "ash/rulesspanish.php";
		$this->setRules($rules);

		$exact = array();
		include "ash/exactany.php";
		include "ash/exactcyrillic.php";
		include "ash/exactenglish.php";
		include "ash/exactfrench.php";
		include "ash/exactgerman.php";
		include "ash/exacthebrew.php";
		include "ash/exacthungarian.php";
		include "ash/exactpolish.php";
		include "ash/exactromanian.php";
		include "ash/exactrussian.php";
		include "ash/exactspanish.php";
		$this->setExact($exact);

		$approx = array();
		include "ash/approxany.php";
		include "ash/approxcyrillic.php";
		include "ash/approxenglish.php";
		include "ash/approxfrench.php";
		include "ash/approxgerman.php";
		include "ash/approxhebrew.php";
		include "ash/approxhungarian.php";
		include "ash/approxpolish.php";
		include "ash/approxromanian.php";
		include "ash/approxrussian.php";
		include "ash/approxspanish.php";
		$this->setApprox($approx);

		return $this;
	}
}

?>