#include "cpp/php2cpp.h"


#ifndef BMPMGENERIC_CPP
#define BMPMGENERIC_CPP

#include "BMPMGeneric.h"

BMPMGeneric::BMPMGeneric()
    {
        this->setType(BMPM::TYPE_GENERIC);
        #include "gen/approxcommon.h"
        this->setApproxCommon(approxCommon);
        #include "gen/exactcommon.h"
        this->setExactCommon(exactCommon);
        #include "gen/languagenames.h"
        this->setLanguageNames(languages);
        #include "gen/lang.h"
        this->setAllLanguagesBitmap(all);
        this->setLanguageRules(languageRules);
        rules = {};
        #include "gen/rulesany.h"
        #include "gen/rulesarabic.h"
        #include "gen/rulescyrillic.h"
        #include "gen/rulesczech.h"
        #include "gen/rulesdutch.h"
        #include "gen/rulesenglish.h"
        #include "gen/rulesfrench.h"
        #include "gen/rulesgerman.h"
        #include "gen/rulesgreek.h"
        #include "gen/rulesgreeklatin.h"
        #include "gen/ruleshebrew.h"
        #include "gen/ruleshungarian.h"
        #include "gen/rulesitalian.h"
        #include "gen/ruleslatvian.h"
        #include "gen/rulespolish.h"
        #include "gen/rulesportuguese.h"
        #include "gen/rulesromanian.h"
        #include "gen/rulesrussian.h"
        #include "gen/rulesspanish.h"
        #include "gen/rulesturkish.h"
        this->setRules(rules);
        exact = {};
        #include "gen/exactany.h"
        #include "gen/exactarabic.h"
        #include "gen/exactcyrillic.h"
        #include "gen/exactczech.h"
        #include "gen/exactdutch.h"
        #include "gen/exactenglish.h"
        #include "gen/exactfrench.h"
        #include "gen/exactgerman.h"
        #include "gen/exactgreek.h"
        #include "gen/exactgreeklatin.h"
        #include "gen/exacthebrew.h"
        #include "gen/exacthungarian.h"
        #include "gen/exactitalian.h"
        #include "gen/exactlatvian.h"
        #include "gen/exactpolish.h"
        #include "gen/exactportuguese.h"
        #include "gen/exactromanian.h"
        #include "gen/exactrussian.h"
        #include "gen/exactspanish.h"
        #include "gen/exactturkish.h"
        this->setExact(exact);
        approx = {};
        #include "gen/approxany.h"
        #include "gen/approxarabic.h"
        #include "gen/approxcyrillic.h"
        #include "gen/approxczech.h"
        #include "gen/approxdutch.h"
        #include "gen/approxenglish.h"
        #include "gen/approxfrench.h"
        #include "gen/approxgerman.h"
        #include "gen/approxgreek.h"
        #include "gen/approxgreeklatin.h"
        #include "gen/approxhebrew.h"
        #include "gen/approxhungarian.h"
        #include "gen/approxitalian.h"
        #include "gen/approxlatvian.h"
        #include "gen/approxpolish.h"
        #include "gen/approxportuguese.h"
        #include "gen/approxromanian.h"
        #include "gen/approxrussian.h"
        #include "gen/approxspanish.h"
        #include "gen/approxturkish.h"
        this->setApprox(approx);
        return this;
    }

#else
#endif