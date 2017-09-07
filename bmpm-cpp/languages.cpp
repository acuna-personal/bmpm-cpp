#include "cpp/php2cpp.h"


#ifndef LANGUAGES_CPP
#define LANGUAGES_CPP

#include "languages.h"
#include <math.h>

int Language_UTF8(std::string name, std::vector<std::vector<std::string>> rules, int allLanguagesBitmap)
{
    //    $name = mb_strtolower($name, mb_detect_encoding($name));
    name = mb_strtolower(name, "UTF-8");
    choicesRemaining = allLanguagesBitmap;
    for (i = 0; i < count(rules); i++) {
        // ORIG: list(letters, languages, accept) = rules[i];
        std::string letters = rules[i][0];
        std::string languages = rules[i][1];
        std::string accept = rules[i][2];

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

int LanguageIndex(std::string langName, std::vector<std::string> languages)
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

std::string LanguageName(std::size_t index, std::vector<std::string> languages)
{
    if (index < 0 || index > count(languages)) {
        std::cout << "index out of range: " + index + "\n";
        exit;
        return "any";
        // index out of range
    }
    return languages[index];
}

int LanguageCode(std::string langName, std::vector<std::string> languages)
{
    return 1 << LanguageIndex(langName, languages);
}

int LanguageIndexFromCode(int code, std::vector<std::string> languages)
{
    if (code < 0 || code > 1 << count(languages) - 1) {
        // code out of range
        return 0;
    }
    float log = log(code, 2);
    int result = floor(log);
    if (result != log) {
        // choice was more than one language, so use "any"
        result = LanguageIndex("any", languages);
    }
    return result;
}


#else
#endif
