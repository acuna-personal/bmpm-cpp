<?php

class BMPMGeneric extends BMPM {
	function __construct() {
		$this->setType(BMPM::TYPE_GENERIC);
		
		include "gen/approxcommon.php";
		$this->setApproxCommon($approxCommon);
		
		include "gen/exactcommon.php";
		$this->setExactCommon($exactCommon);

		include "gen/languagenames.php";
		$this->setLanguageNames($languages);
		
		include "gen/lang.php";
		$this->setAllLanguagesBitmap($all);
		$this->setLanguageRules($languageRules);

		$rules = array();
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
		include "gen/rulesturkish.php";
		$this->setRules($rules);

		$approx = array();
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
		include "gen/approxturkish.php";
		$this->setApprox($approx);

		$exact = array();
		include "gen/exactany.php";
		include "gen/exactarabic.php";
		include "gen/exactcyrillic.php";
		include "gen/exactczech.php";
		include "gen/exactdutch.php";
		include "gen/exactenglish.php";
		include "gen/exactfrench.php";
		include "gen/exactgerman.php";
		include "gen/exactgreek.php";
		include "gen/exactgreeklatin.php";
		include "gen/exacthebrew.php";
		include "gen/exacthungarian.php";
		include "gen/exactitalian.php";
		include "gen/exactlatvian.php";
		include "gen/exactpolish.php";
		include "gen/exactportuguese.php";
		include "gen/exactromanian.php";
		include "gen/exactrussian.php";
		include "gen/exactturkish.php";
		$this->setExact($exact);


		return $this;
	}
}

?>