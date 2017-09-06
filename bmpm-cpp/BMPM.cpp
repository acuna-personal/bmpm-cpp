#include "cpp/php2cpp.h"


#ifndef BMPM_CPP
#define BMPM_CPP

#include "BMPM.h"

// bmpm.php
/* ORIG: include_once */
#include "dmsoundex.h"
/* ORIG: include_once */
#include "phoneticengine.h"
/* ORIG: include_once */
#include "languages.h"

    const std::string TYPE_SEPHARDIC = "sep";
    const std::string TYPE_ASHKENAZI = "ash";
    const std::string TYPE_GENERIC = "gen";
    const std::string LANGUAGE_ANY = "any";
    const std::string LANGUAGE_ARABIC = "arabic";
    const std::string LANGUAGE_CYRILLIC = "cyrillic";
    const std::string LANGUAGE_CZECH = "czech";
    const std::string LANGUAGE_DUTCH = "dutch";
    const std::string LANGUAGE_ENGLISH = "english";
    const std::string LANGUAGE_FRENCH = "french";
    const std::string LANGUAGE_GERMAN = "german";
    const std::string LANGUAGE_GREEK = "greek";
    const std::string LANGUAGE_GREEKLATIN = "greeklatin";
    const std::string LANGUAGE_HEBREW = "hebrew";
    const std::string LANGUAGE_HUNGARIAN = "hungarian";
    const std::string LANGUAGE_ITALIAN = "italian";
    const std::string LANGUAGE_LATVIAN = "latvian";
    const std::string LANGUAGE_POLISH = "polish";
    const std::string LANGUAGE_PORTUGUESE = "portuguese";
    const std::string LANGUAGE_ROMANIAN = "romanian";
    const std::string LANGUAGE_RUSSIAN = "russian";
    const std::string LANGUAGE_SPANISH = "spanish";
    const std::string LANGUAGE_TURKISH = "turkish";
    php_type BMPM::getBMPM(php_type type)
    {
        bmpm = NULL;
        switch (type) {
            case BMPM::TYPE_SEPHARDIC:
                if (BMPM::_bmpmSephardic == NULL) {
                    BMPM::_bmpmSephardic = /* TODO: Mem mgmt */ new BMPMSephardic();
                }
                bmpm = BMPM::_bmpmSephardic;
                break;
            case BMPM::TYPE_ASHKENAZI:
                if (BMPM::_bmpmAshkenazi == NULL) {
                    BMPM::_bmpmAshkenazi = /* TODO: Mem mgmt */ new BMPMAshkenazi();
                }
                bmpm = BMPM::_bmpmAshkenazi;
                break;
            case BMPM::TYPE_GENERIC:
            default:
                if (BMPM::_bmpmGeneric == NULL) {
                    BMPM::_bmpmGeneric = /* TODO: Mem mgmt */ new BMPMGeneric();
                }
                bmpm = BMPM::_bmpmGeneric;
        }
        return bmpm;
    }
    php_type BMPM::getAllBMPMData()
    {
        return {BMPM::TYPE_GENERIC => BMPM::getBMPM(BMPM::TYPE_GENERIC).getData(), BMPM::TYPE_ASHKENAZI => BMPM::getBMPM(BMPM::TYPE_ASHKENAZI).getData(), BMPM::TYPE_SEPHARDIC => BMPM::getBMPM(BMPM::TYPE_SEPHARDIC).getData()};
    }
    php_type BMPM::getData()
    {
        return utf8ize({"languageNames" => this->getLanguageNames(), "rules" => this->getRules(), "exact" => this->getExact(), "exactCommon" => this->getExactCommon(), "approx" => this->getApprox(), "approxCommon" => this->getApproxCommon(), "languageRules" => this->getLanguageRules(), "allLanguagesBitmap" => this->getAllLanguagesBitmap()});
    }
    php_type BMPM::getDMData()
    {
        return soundx_data();
    }
    php_type BMPM::getPhoneticEncoding(php_type name, php_type type, php_type language, bool exact)
    {
        bmpm = BMPM::getBMPM(type);
        if (bmpm == NULL) {
            std::cout << "Null bmpm";
            return NULL;
        }
        // Fall back to TYPE_GENERIC if we don't have matching for that language in that type.
        if (!bmpm.handlesLanguage(language)) {
            type = BMPM::TYPE_GENERIC;
            bmpm = BMPM::getBMPM(type);
            if (bmpm == NULL) {
                std::cout << "Null bmpm";
                return NULL;
            }
        }
        rules = bmpm.getRules();
        equivalencyRules = exact ? bmpm.getExact() : bmpm.getApprox();
        equivalencyRulesCommon = exact ? bmpm.getExactCommon() : bmpm.getApproxCommon();
        allLanguagesBitmap = bmpm.getAllLanguagesBitmap();
        languages = bmpm.getLanguageNames();
        languageRules = bmpm.getLanguageRules();
        if (language == BMPM::LANGUAGE_ANY || language == "") {
            languageCode = Language_UTF8(name, languageRules, allLanguagesBitmap);
        } else {
            languageCode = LanguageCode(language, languages, allLanguagesBitmap);
        }
        idx = LanguageIndexFromCode(languageCode, languages);
        //echo "$name => " . LanguageName($idx, $languages) . "\n";
        if (!equivalencyRules) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No equivalencyRules for (" + name + ", " + type + ", " + language + ", " + exact + ")\n";
            return NULL;
        }
        if (!equivalencyRulesCommon) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No equivalencyRulesCommon for (" + name + ", " + type + ", " + language + ", " + exact + ")\n";
            return NULL;
        }
        if (!isset(rules[idx])) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No rules for (" + name + ", " + type + ", " + language + ", " + exact + ")\n";
            return NULL;
        }
        if (!isset(equivalencyRules[idx])) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No equivalencyRules for (" + name + ", " + type + ", " + language + ", " + exact + ")\n";
            return NULL;
        }
        result = Phonetic_UTF8(name, type, allLanguagesBitmap, languageRules, rules[idx], equivalencyRulesCommon, equivalencyRules[idx], languageCode, exact);
        numbers = PhoneticNumbers(result);
        return numbers;
    }
    php_type BMPM::getDaitchMotokoffSoundex(php_type name)
    {
        return trim(soundx_name(remove_accents(name)));
    }
    php_type BMPM::getType()
    {
        return this->_type;
    }
    void BMPM::setType(php_type type)
    {
        this->_type = type;
    }
    php_type BMPM::getLanguageNames()
    {
        return this->_languageNames;
    }
    void BMPM::setLanguageNames(php_type languageNames)
    {
        this->_languageNames = languageNames;
    }
    php_type BMPM::handlesLanguage(php_type language)
    {
        if (this->_languageNames == NULL) {
            std::cout << "handlesLanguage: Null languages list\n";
            debug_print_backtrace();
            return false;
        }
        return in_array(language, this->_languageNames);
    }
    php_type BMPM::getApprox()
    {
        return this->_approx;
    }
    void BMPM::setApprox(php_type approx)
    {
        this->_approx = approx;
    }
    php_type BMPM::getApproxCommon()
    {
        return this->_approxCommon;
    }
    void BMPM::setApproxCommon(php_type approxCommon)
    {
        this->_approxCommon = approxCommon;
    }
    php_type BMPM::getExact()
    {
        return this->_exact;
    }
    void BMPM::setExact(php_type exact)
    {
        this->_exact = exact;
    }
    php_type BMPM::getExactCommon()
    {
        return this->_exactCommon;
    }
    void BMPM::setExactCommon(php_type exactCommon)
    {
        this->_exactCommon = exactCommon;
    }
    php_type BMPM::getRules()
    {
        return this->_rules;
    }
    void BMPM::setRules(php_type rules)
    {
        this->_rules = rules;
    }
    php_type BMPM::getLanguageRules()
    {
        return this->_languageRules;
    }
    void BMPM::setLanguageRules(php_type languageRules)
    {
        this->_languageRules = languageRules;
    }
    php_type BMPM::getAllLanguagesBitmap()
    {
        return this->_allLanguagesBitmap;
    }
    void BMPM::setAllLanguagesBitmap(php_type allLanguagesBitmap)
    {
        this->_allLanguagesBitmap = allLanguagesBitmap;
    }
php_type BMPMTypeForName(php_type typeName)
{
    switch (typeName) {
        case "sep":
            return BMPM::TYPE_SEPHARDIC;
        case "ash":
            return BMPM::TYPE_ASHKENAZI;
    }
    return BMPM::TYPE_GENERIC;
}

php_type BMPMLanguageForName(php_type languageName)
{
    switch (languageName) {
        case "arabic":
            return BMPM::LANGUAGE_ARABIC;
        case "cyrillic":
            return BMPM::LANGUAGE_CYRILLIC;
        case "czech":
            return BMPM::LANGUAGE_CZECH;
        case "dutch":
            return BMPM::LANGUAGE_DUTCH;
        case "english":
            return BMPM::LANGUAGE_ENGLISH;
        case "french":
            return BMPM::LANGUAGE_FRENCH;
        case "german":
            return BMPM::LANGUAGE_GERMAN;
        case "greek":
            return BMPM::LANGUAGE_GREEK;
        case "greeklatin":
            return BMPM::LANGUAGE_GREEKLATIN;
        case "hebrew":
            return BMPM::LANGUAGE_HEBREW;
        case "hungarian":
            return BMPM::LANGUAGE_HUNGARIAN;
        case "italian":
            return BMPM::LANGUAGE_ITALIAN;
        case "latvian":
            return BMPM::LANGUAGE_LATVIAN;
        case "polish":
            return BMPM::LANGUAGE_POLISH;
        case "portuguese":
            return BMPM::LANGUAGE_PORTUGUESE;
        case "romanian":
            return BMPM::LANGUAGE_ROMANIAN;
        case "russian":
            return BMPM::LANGUAGE_RUSSIAN;
        case "spanish":
            return BMPM::LANGUAGE_SPANISH;
        case "turkish":
            return BMPM::LANGUAGE_TURKISH;
    }
    return BMPM::LANGUAGE_ANY;
}

/* ORIG: include_once */
#include "BMPMGeneric.h"
/* ORIG: include_once */
#include "BMPMSephardic.h"
/* ORIG: include_once */
#include "BMPMAshkenazi.h"

#else
#endif