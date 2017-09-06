#include "cpp/php2cpp.h"


#ifndef RULESFRENCH_CPP
#define RULESFRENCH_CPP

#include "rulesfrench.h"

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
// GENERAL
rulesFrench = {
    // CONSONANTS
    {"lt", "u", "$", "(lt|)"},
    // Renault
    {"c", "n", "$", "(k|)"},
    // Tronc
    //array("f","","","(f|)"), // Clef
    {"d", "", "$", "(t|)"},
    // Durand
    {"g", "n", "$", "(k|)"},
    // Gang
    {"p", "", "$", "(p|)"},
    // Trop, Champ
    {"r", "e", "$", "(r|)"},
    // Barbier
    {"t", "", "$", "(t|)"},
    // Murat, Constant
    {"z", "", "$", "(s|)"},
    {"ds", "", "$", "(ds|)"},
    {"ps", "", "$", "(ps|)"},
    // Champs
    {"rs", "e", "$", "(rs|)"},
    {"ts", "", "$", "(ts|)"},
    {"s", "", "$", "(s|)"},
    // Denis
    {"x", "u", "$", "(ks|)"},
    // Arnoux
    {"s", "[aeéèêiou]", "[^aeéèêiou]", "(s|)"},
    // Deschamps, Malesherbes, Groslot
    {"t", "[aeéèêiou]", "[^aeéèêiou]", "(t|)"},
    // Petitjean
    {"kh", "", "", "x"},
    // foreign
    {"ph", "", "", "f"},
    {"ç", "", "", "s"},
    {"x", "", "", "ks"},
    {"ch", "", "", "S"},
    {"c", "", "[eiyéèê]", "s"},
    {"gn", "", "", "(n|gn)"},
    {"g", "", "[eiy]", "Z"},
    {"gue", "", "$", "k"},
    {"gu", "", "[eiy]", "g"},
    {"aill", "", "e", "aj"},
    // non Jewish
    {"ll", "", "e", "(l|j)"},
    // non Jewish
    {"que", "", "$", "k"},
    {"qu", "", "", "k"},
    {"s", "[aeiouyéèê]", "[aeiouyéèê]", "z"},
    {"h", "[bdgt]", "", ""},
    // translit from Arabic
    {"m", "[aeiouy]", "[aeiouy]", "m"},
    {"m", "[aeiouy]", "", "(m|n)"},
    // nasal
    {"ou", "", "[aeio]", "v"},
    {"u", "", "[aeio]", "v"},
    // VOWELS
    {"aue", "", "", "aue"},
    {"eau", "", "", "o"},
    {"au", "", "", "(o|au)"},
    // non Jewish
    {"ai", "", "", "(e|aj)"},
    // [e] is non Jewish
    {"ay", "", "", "(e|aj)"},
    // [e] is non Jewish
    {"é", "", "", "e"},
    {"ê", "", "", "e"},
    {"è", "", "", "e"},
    {"à", "", "", "a"},
    {"â", "", "", "a"},
    {"où", "", "", "u"},
    {"ou", "", "", "u"},
    {"oi", "", "", "(oj|va)"},
    // [va] (actually "ua") is non Jewish
    {"ei", "", "", "(aj|ej|e)"},
    // [e] is non Jewish
    {"ey", "", "", "(aj|ej|e)"},
    // [e] non Jewish
    {"eu", "", "", "(ej|Y)"},
    // non Jewish
    {"y", "[ou]", "", "j"},
    {"e", "", "$", "(e|)"},
    {"i", "", "[aou]", "j"},
    {"y", "", "[aoeu]", "j"},
    // LATIN ALPHABET
    {"a", "", "", "a"},
    {"b", "", "", "b"},
    {"c", "", "", "k"},
    {"d", "", "", "d"},
    {"e", "", "", "e"},
    {"f", "", "", "f"},
    {"g", "", "", "g"},
    {"h", "", "", "h"},
    {"i", "", "", "i"},
    {"j", "", "", "Z"},
    {"k", "", "", "k"},
    {"l", "", "", "l"},
    {"m", "", "", "m"},
    {"n", "", "", "n"},
    {"o", "", "", "o"},
    {"p", "", "", "p"},
    {"q", "", "", "k"},
    {"r", "", "", "r"},
    {"s", "", "", "s"},
    {"t", "", "", "t"},
    {"u", "", "", "(u|Q)"},
    {"v", "", "", "v"},
    {"w", "", "", "v"},
    {"y", "", "", "i"},
    {"z", "", "", "z"},
    {"rulesfrench"},
};
rules[LanguageIndex("french", languages)] = rulesFrench;

#else
#endif