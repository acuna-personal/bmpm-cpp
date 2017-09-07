#include "cpp/php2cpp.h"


#ifndef BMPMSEPHARDIC_CPP
#define BMPMSEPHARDIC_CPP

#include "BMPMSephardic.h"

BMPMSephardic::BMPMSephardic()
    {
        this->setType(BMPM::TYPE_SEPHARDIC);
        #include "sep/approxcommon.h"
        this->setApproxCommon(approxCommon);
        #include "sep/exactcommon.h"
        this->setExactCommon(exactCommon);
        #include "sep/hebrewcommon.h"
        this->setHebrewCommon(hebrewCommon);
        #include "sep/languagenames.h"
        this->setLanguageNames(languages);
        #include "sep/lang.h"
        this->setAllLanguagesBitmap(all);
        this->setLanguageRules(languageRules);
        rules = {};
        #include "sep/rulesany.h"
        #include "sep/rulesfrench.h"
        #include "sep/ruleshebrew.h"
        #include "sep/rulesitalian.h"
        #include "sep/rulesportuguese.h"
        #include "sep/rulesspanish.h"
        this->setRules(rules);
        exact = {};
        #include "sep/exactany.h"
        #include "sep/exactfrench.h"
        #include "sep/exacthebrew.h"
        #include "sep/exactitalian.h"
        #include "sep/exactportuguese.h"
        #include "sep/exactspanish.h"
        this->setExact(exact);
        approx = {};
        #include "sep/approxany.h"
        #include "sep/approxfrench.h"
        #include "sep/approxhebrew.h"
        #include "sep/approxitalian.h"
        #include "sep/approxportuguese.h"
        #include "sep/approxspanish.h"
        this->setApprox(approx);
        return this;
    }

#else
#endif