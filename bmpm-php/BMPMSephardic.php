<?php

class BMPMSephardic extends BMPM {
	function __construct() {
		$this->setType(BMPM::TYPE_SEPHARDIC);
		
		include "sep/approxcommon.php";
		$this->setApproxCommon($approxCommon);
		
		include "sep/languagenames.php";
		$this->setLanguageNames($languages);
		
		include "sep/lang.php";
		$this->setAllLanguagesBitmap($all);
		$this->setLanguageRules($languageRules);

		$rules = array();
		include "sep/rulesany.php";
		include "sep/rulesfrench.php";
		include "sep/ruleshebrew.php";
		include "sep/rulesitalian.php";
		include "sep/rulesportuguese.php";
		include "sep/rulesspanish.php";
		$this->setRules($rules);

		$exact = array();
		include "sep/exactany.php";
		include "sep/exactfrench.php";
		include "sep/exacthebrew.php";
		include "sep/exactitalian.php";
		include "sep/exactportuguese.php";
		include "sep/exactspanish.php";
		$this->setExact($exact);

		$approx = array();
		include "sep/approxany.php";
		include "sep/approxfrench.php";
		include "sep/approxhebrew.php";
		include "sep/approxitalian.php";
		include "sep/approxportuguese.php";
		include "sep/approxspanish.php";
		$this->setApprox($approx);

		return $this;
	}
}

?>