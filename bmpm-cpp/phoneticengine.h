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
std::string RedoLanguage(std::string input, std::string type, std::string allLanguagesBitmap, std::string languageRules, std::string rules, std::string finalRules1, std::string finalRules2, bool concat = false);
std::string Phonetic_UTF8(std::string input, std::string type, std::string allLanguagesBitmap, std::string languageRules, std::string rules, std::string finalRules1, std::string finalRules2, std::string languageArg = "", bool concat = false);
std::string ApplyFinalRules(std::string phonetic, std::string finalRules, std::string languageArg, std::string strip, std::string debug);
std::string Mod(std::string x, std::string y);
std::string PhoneticNumber(std::string phonetic, bool hash = true);
std::string Expand(std::string phonetic);
std::string PhoneticNumbersWithLeadingSpace(std::string phonetic);
std::string PhoneticNumbers(std::string phonetic);
std::string isPhoneticVowel(std::string c);
std::string isAOTypeVowel(std::string c);
std::string isEITypeVowel(std::string c);
std::string isSZTypeConsonant(std::string c);
std::string RemoveDuplicateAlternates(std::string phonetic);
std::string NormalizeLanguageAttributes(std::string text, std::string strip);
std::string ApplyRuleIfCompatible(std::string phonetic, std::string target, std::string languageArg);

#else
#endif
