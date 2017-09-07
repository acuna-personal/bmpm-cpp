#include "cpp/php2cpp.h"


#ifndef LANGUAGES_CPP
#define LANGUAGES_CPP

#include "languages.h"

php_type Language(php_type name, php_type rules, php_type allLanguagesBitmap)
{
    // convert $name to utf8
    name = utf8_encode(name);
    // takes care of things in the upper half of the ascii chart, e.g., u-umlaut
    if (strpos(name, "&") != /* ORIG: !== */ false) {
        // takes care of ampersand-notation encoding of unicode (&#...;)
        name = html_entity_decode(name, ENT_NOQUOTES, "UTF-8");
    }
    return Language_UTF8(name, rules, allLanguagesBitmap);
}

php_type Language_UTF8(php_type name, php_type rules, php_type allLanguagesBitmap)
{
    //    $name = mb_strtolower($name, mb_detect_encoding($name));
    name = mb_strtolower(name, "UTF-8");
    choicesRemaining = allLanguagesBitmap;
    for (i = 0; i < count(rules); i++) {
        list(letters, languages, accept) = rules[i];
        //echo "testing name=$name letters=$letters languages=$languages accept=$accept<br>";
        if (preg_match(letters, name)) {
            if (accept) {
                choicesRemaining &= languages;
            } else {
                // reject
                choicesRemaining &= ~languages % (allLanguagesBitmap + 1);
            }
        }
    }
    if (choicesRemaining == 0) {
        choicesRemaining = 1;
    }
    return choicesRemaining;
}

php_type LanguageIndex(php_type langName, php_type languages)
{
    for (i = 0; i < count(languages); i++) {
        if (languages[i] == langName) {
            //echo "LanguageIndex: $i\n";
            return i;
        }
    }
    //echo "LanguageIndex Not Found\n";
    return 0;
    // name not found
}

php_type LanguageName(php_type index, php_type languages)
{
    if (index < 0 || index > count(languages)) {
        std::cout << "index out of range: " + index + "\n";
        exit;
        return "any";
        // index out of range
    }
    return languages[index];
}

php_type LanguageCode(php_type langName, php_type languages)
{
    return 1 << LanguageIndex(langName, languages);
}

php_type LanguageIndexFromCode(php_type code, php_type languages)
{
    if (code < 0 || code > 1 << count(languages) - 1) {
        // code out of range
        return 0;
    }
    log = log(code, 2);
    result = floor(log);
    if (result != log) {
        // choice was more than one language, so use "any"
        result = LanguageIndex("any", languages);
    }
    return result;
}


#else
#endif