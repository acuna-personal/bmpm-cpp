#include "cpp/php2cpp.h"


#ifndef BMPM_H
#define BMPM_H

// bmpm.php
/* ORIG: include_once */
#include "dmsoundex.h"
/* ORIG: include_once */
#include "phoneticengine.h"
/* ORIG: include_once */
#include "languages.h"
/* ORIG: abstract  */ class BMPM
{
    public: static const std::string TYPE_SEPHARDIC;
    public: static const std::string TYPE_ASHKENAZI;
    public: static const std::string TYPE_GENERIC;
    public: static const std::string LANGUAGE_ANY;
    public: static const std::string LANGUAGE_ARABIC;
    public: static const std::string LANGUAGE_CYRILLIC;
    public: static const std::string LANGUAGE_CZECH;
    public: static const std::string LANGUAGE_DUTCH;
    public: static const std::string LANGUAGE_ENGLISH;
    public: static const std::string LANGUAGE_FRENCH;
    public: static const std::string LANGUAGE_GERMAN;
    public: static const std::string LANGUAGE_GREEK;
    public: static const std::string LANGUAGE_GREEKLATIN;
    public: static const std::string LANGUAGE_HEBREW;
    public: static const std::string LANGUAGE_HUNGARIAN;
    public: static const std::string LANGUAGE_ITALIAN;
    public: static const std::string LANGUAGE_LATVIAN;
    public: static const std::string LANGUAGE_POLISH;
    public: static const std::string LANGUAGE_PORTUGUESE;
    public: static const std::string LANGUAGE_ROMANIAN;
    public: static const std::string LANGUAGE_RUSSIAN;
    public: static const std::string LANGUAGE_SPANISH;
    public: static const std::string LANGUAGE_TURKISH;
    private: php_type _type;
    private: php_type _languageNames;
    private: php_type _rules;
    private: php_type _exact;
    private: php_type _exactCommon;
    private: php_type _approx;
    private: php_type _approxCommon;
    private: php_type _languageRules;
    private: php_type _allLanguagesBitmap;
    private: static php_type _bmpmAshkenazi;
    private: static php_type _bmpmGeneric;
    private: static php_type _bmpmSephardic;
    public: static php_type getBMPM(php_type type = BMPM::TYPE_GENERIC);
    public: static php_type getAllBMPMData();
    public: php_type getData();
    public: static php_type getDMData();
    public: static php_type getPhoneticEncoding(php_type name, php_type type = TYPE_GENERIC, php_type language = LANGUAGE_ANY, bool exact = true);
    public: static php_type getDaitchMotokoffSoundex(php_type name);
    public: php_type getType();
    public: void setType(php_type type);
    public: php_type getLanguageNames();
    public: void setLanguageNames(php_type languageNames);
    public: php_type handlesLanguage(php_type language);
    public: php_type getApprox();
    public: void setApprox(php_type approx);
    public: php_type getApproxCommon();
    public: void setApproxCommon(php_type approxCommon);
    public: php_type getExact();
    public: void setExact(php_type exact);
    public: php_type getExactCommon();
    public: void setExactCommon(php_type exactCommon);
    public: php_type getRules();
    public: void setRules(php_type rules);
    public: php_type getLanguageRules();
    public: void setLanguageRules(php_type languageRules);
    public: php_type getAllLanguagesBitmap();
    public: void setAllLanguagesBitmap(php_type allLanguagesBitmap);
};
php_type BMPMTypeForName(php_type typeName);
php_type BMPMLanguageForName(php_type languageName);
/* ORIG: include_once */
#include "BMPMGeneric.h"
/* ORIG: include_once */
#include "BMPMSephardic.h"
/* ORIG: include_once */
#include "BMPMAshkenazi.h"

#else
#endif