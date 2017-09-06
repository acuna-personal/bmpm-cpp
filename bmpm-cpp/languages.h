#include "cpp/php2cpp.h"


#ifndef LANGUAGES_H
#define LANGUAGES_H

php_type Language(php_type name, php_type rules, php_type allLanguagesBitmap);
php_type Language_UTF8(php_type name, php_type rules, php_type allLanguagesBitmap);
php_type LanguageIndex(php_type langName, php_type languages);
php_type LanguageName(php_type index, php_type languages);
php_type LanguageCode(php_type langName, php_type languages);
php_type LanguageIndexFromCode(php_type code, php_type languages);

#else
#endif