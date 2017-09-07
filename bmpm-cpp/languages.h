#include "cpp/php2cpp.h"


#ifndef LANGUAGES_H
#define LANGUAGES_H

int Language_UTF8(std::string name, std::vector<std::vector<std::string>> rules, int allLanguagesBitmap);
int LanguageIndex(std::string langName, std::vector<std::string> languages);
std::string LanguageName(std::size_t index, std::vector<std::string> languages);
int LanguageCode(std::string langName, std::vector<std::string> languages);
int LanguageIndexFromCode(int code, std::vector<std::string> languages);

#else
#endif
