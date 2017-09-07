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
    const std::string MATCHING_APPROXIMATE = "approx";
    const std::string MATCHING_EXACT = "exact";
    const std::string MATCHING_HEBREW = "hebrew";
    void BMPM::getBMPM(php_type type)
    {
        static php_type _bmpmAshkenazi = NULL;
        static php_type _bmpmGeneric = NULL;
        static php_type _bmpmSephardic = NULL;
        switch (type) {
            case BMPM::TYPE_SEPHARDIC:
                if (_bmpmSephardic == /* ORIG: === */ NULL) {
                    _bmpmSephardic = /* TODO: Mem mgmt */ new BMPMSephardic();
                }
                return _bmpmSephardic;
            case BMPM::TYPE_ASHKENAZI:
                if (_bmpmAshkenazi == /* ORIG: === */ NULL) {
                    _bmpmAshkenazi = /* TODO: Mem mgmt */ new BMPMAshkenazi();
                }
                return _bmpmAshkenazi;
            case BMPM::TYPE_GENERIC:
            default:
                if (_bmpmGeneric == /* ORIG: === */ NULL) {
                    _bmpmGeneric = /* TODO: Mem mgmt */ new BMPMGeneric();
                }
                return _bmpmGeneric;
        }
    }
    php_type BMPM::getAllBMPMData()
    {
        return {BMPM::TYPE_GENERIC => BMPM::getBMPM(BMPM::TYPE_GENERIC).getData(), BMPM::TYPE_ASHKENAZI => BMPM::getBMPM(BMPM::TYPE_ASHKENAZI).getData(), BMPM::TYPE_SEPHARDIC => BMPM::getBMPM(BMPM::TYPE_SEPHARDIC).getData()};
    }
    php_type BMPM::getData()
    {
        return utf8ize({"languageNames" => this->getLanguageNames(), "rules" => this->getRules(), "exact" => this->getExact(), "exactCommon" => this->getExactCommon(), "approx" => this->getApprox(), "approxCommon" => this->getApproxCommon(), "hebrew" => this->getHebrew(), "hebrewCommon" => this->getHebrewCommon(), "languageRules" => this->getLanguageRules(), "allLanguagesBitmap" => this->getAllLanguagesBitmap()});
    }
    php_type BMPM::getDMData()
    {
        return soundx_data();
    }
    php_type BMPM::getPhoneticEncoding(php_type name, php_type type, php_type language, php_type matching)
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
        matchingRules = bmpm.getMatchingRules(matching);
        matchingRulesCommon = bmpm.getMatchingRulesCommon(matching);
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
        if (matching != BMPM::MATCHING_HEBREW && !matchingRules) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No matching rules for (" + name + ", " + type + ", " + language + ", " + matching + ")\n";
            return NULL;
        }
        if (!matchingRulesCommon) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No matching rules common for (" + name + ", " + type + ", " + language + ", " + matching + ")\n";
            return NULL;
        }
        if (!isset(rules[idx])) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No rules for (" + name + ", " + type + ", " + language + ", " + matching + ")\n";
            return NULL;
        }
        if (matching != BMPM::MATCHING_HEBREW && !isset(matchingRules[idx])) {
            std::cout << "" + name + " => " + LanguageName(idx, languages) + "\n";
            std::cout << "getPhoneticEncoding: No matching rules for (" + name + ", " + type + ", " + language + ", " + matching + ")\n";
            return NULL;
        }
        result = Phonetic_UTF8(
            name,
            type,
            allLanguagesBitmap,
            languageRules,
            rules[idx],
            matchingRulesCommon,
            matching == BMPM::MATCHING_HEBREW ? NULL : matchingRules[idx],
            languageCode,
            matching == BMPM::MATCHING_EXACT);
        numbers = PhoneticNumbers(result);
        return numbers;
    }
    php_type BMPM::getMatchingRules(php_type matching)
    {
        switch (matching) {
            case BMPM::MATCHING_APPROXIMATE:
                return this->getApprox();
            case BMPM::MATCHING_HEBREW:
                return this->getHebrew();
            case BMPM::MATCHING_EXACT:
                return this->getExact();
        }
        return NULL;
    }
    php_type BMPM::getMatchingRulesCommon(php_type matching)
    {
        switch (matching) {
            case BMPM::MATCHING_APPROXIMATE:
                return this->getApproxCommon();
            case BMPM::MATCHING_HEBREW:
                return this->getHebrewCommon();
            case BMPM::MATCHING_EXACT:
                return this->getExactCommon();
        }
        return NULL;
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
    php_type BMPM::getHebrew()
    {
        return this->_hebrew;
    }
    void BMPM::setHebrew(php_type exact)
    {
        this->_hebrew = hbrew;
    }
    php_type BMPM::getHebrewCommon()
    {
        return this->_hebrewCommon;
    }
    void BMPM::setHebrewCommon(php_type hebrewCommon)
    {
        this->_hebrewCommon = hebrewCommon;
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