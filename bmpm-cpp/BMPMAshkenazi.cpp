#include "cpp/php2cpp.h"


#ifndef BMPMASHKENAZI_CPP
#define BMPMASHKENAZI_CPP

#include "BMPMAshkenazi.h"

BMPMAshkenazi::BMPMAshkenazi()
    {
        this->setType(BMPM::TYPE_ASHKENAZI);
        #include "ash/approxcommon.h"
        this->setApproxCommon(approxCommon);
        #include "ash/exactcommon.h"
        this->setExactCommon(exactCommon);
        #include "ash/languagenames.h"
        this->setLanguageNames(languages);
        #include "ash/lang.h"
        this->setAllLanguagesBitmap(all);
        this->setLanguageRules(languageRules);
        rules = {};
        #include "ash/rulesany.h"
        #include "ash/rulescyrillic.h"
        #include "ash/rulesenglish.h"
        #include "ash/rulesfrench.h"
        #include "ash/rulesgerman.h"
        #include "ash/ruleshebrew.h"
        #include "ash/ruleshungarian.h"
        #include "ash/rulespolish.h"
        #include "ash/rulesromanian.h"
        #include "ash/rulesrussian.h"
        #include "ash/rulesspanish.h"
        this->setRules(rules);
        exact = {};
        #include "ash/exactany.h"
        #include "ash/exactcyrillic.h"
        #include "ash/exactenglish.h"
        #include "ash/exactfrench.h"
        #include "ash/exactgerman.h"
        #include "ash/exacthebrew.h"
        #include "ash/exacthungarian.h"
        #include "ash/exactpolish.h"
        #include "ash/exactromanian.h"
        #include "ash/exactrussian.h"
        #include "ash/exactspanish.h"
        this->setExact(exact);
        approx = {};
        #include "ash/approxany.h"
        #include "ash/approxcyrillic.h"
        #include "ash/approxenglish.h"
        #include "ash/approxfrench.h"
        #include "ash/approxgerman.h"
        #include "ash/approxhebrew.h"
        #include "ash/approxhungarian.h"
        #include "ash/approxpolish.h"
        #include "ash/approxromanian.h"
        #include "ash/approxrussian.h"
        #include "ash/approxspanish.h"
        this->setApprox(approx);
        return this;
    }

#else
#endif