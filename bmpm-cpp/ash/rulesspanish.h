#include "cpp/php2cpp.h"


#ifndef RULESSPANISH_H
#define RULESSPANISH_H

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
// Ashkenazic = Argentina
rulesSpanish = {
    // CONSONANTS
    {"ñ", "", "", "(n|nj)"},
    {"ch", "", "", "(tS|dZ)"},
    // dZ is typical for Argentina
    {"h", "[bdgt]", "", ""},
    // translit. from Arabic
    {"h", "", "$", ""},
    // foreign
    {"j", "", "", "x"},
    {"x", "", "", "ks"},
    {"ll", "", "", "(l|Z)"},
    // Z is typical for Argentina, only Ashkenazic
    {"w", "", "", "v"},
    // foreign words
    {"v", "", "", "(b|v)"},
    {"b", "", "", "(b|v)"},
    {"m", "", "[bpvf]", "(m|n)"},
    {"c", "", "[ei]", "s"},
    {"c", "", "", "k"},
    {"z", "", "", "(z|s)"},
    // as "c" befoire "e" or "i", in Spain it is like unvoiced English "th"
    {"gu", "", "[ei]", "(g|gv)"},
    // "gv" because "u" can actually be "ü"
    {"g", "", "[ei]", "(x|g)"},
    // "g" only for foreign words
    {"qu", "", "", "k"},
    {"q", "", "", "k"},
    {"uo", "", "", "(vo|o)"},
    {"u", "", "[aei]", "v"},
    {"y", "", "", "(i|j|S|Z)"},
    // S or Z are peculiar to South America; only Ashkenazic
    // VOWELS
    {"ü", "", "", "v"},
    {"á", "", "", "a"},
    {"é", "", "", "e"},
    {"í", "", "", "i"},
    {"ó", "", "", "o"},
    {"ú", "", "", "u"},
    // TRIVIAL
    {"a", "", "", "a"},
    {"d", "", "", "d"},
    {"e", "", "", "E"},
    // Only Ashkenazic
    {"f", "", "", "f"},
    {"g", "", "", "g"},
    {"h", "", "", "h"},
    {"i", "", "", "I"},
    // Only Ashkenazic
    {"k", "", "", "k"},
    {"l", "", "", "l"},
    {"m", "", "", "m"},
    {"n", "", "", "n"},
    {"o", "", "", "o"},
    {"p", "", "", "p"},
    {"r", "", "", "r"},
    {"s", "", "", "s"},
    {"t", "", "", "t"},
    {"u", "", "", "u"},
    {"rulesspanish"},
};
rules[LanguageIndex("spanish", languages)] = rulesSpanish;

#else
#endif