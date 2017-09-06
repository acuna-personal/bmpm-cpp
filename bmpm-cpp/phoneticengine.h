#include "cpp/php2cpp.h"


#ifndef PHONETICENGINE_H
#define PHONETICENGINE_H

/*
 *
 * Copyright Alexander Beider and Stephen P. Morse, 2008
 *
 * This file is part of the Beider-Morse Phonetic Matching (BMPM) System. 
 * BMPM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BMPM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BMPM.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
php_type RedoLanguage(php_type input, php_type type, php_type allLanguagesBitmap, php_type languageRules, php_type rules, php_type finalRules1, php_type finalRules2, bool concat = false);
php_type Phonetic_UTF8(php_type input, php_type type, php_type allLanguagesBitmap, php_type languageRules, php_type rules, php_type finalRules1, php_type finalRules2, std::string languageArg = "", bool concat = false);
php_type ApplyFinalRules(php_type phonetic, php_type finalRules, php_type languageArg, php_type strip, php_type debug);
php_type Mod(php_type x, php_type y);
php_type PhoneticNumber(php_type phonetic, bool hash = true);
php_type Expand(php_type phonetic);
php_type PhoneticNumbersWithLeadingSpace(php_type phonetic);
php_type PhoneticNumbers(php_type phonetic);
php_type isPhoneticVowel(php_type c);
php_type isAOTypeVowel(php_type c);
php_type isEITypeVowel(php_type c);
php_type isSZTypeConsonant(php_type c);
php_type RemoveDuplicateAlternates(php_type phonetic);
php_type NormalizeLanguageAttributes(php_type text, php_type strip);
php_type ApplyRuleIfCompatible(php_type phonetic, php_type target, php_type languageArg);

#else
#endif