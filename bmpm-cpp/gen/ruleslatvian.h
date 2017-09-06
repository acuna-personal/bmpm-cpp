#include "cpp/php2cpp.h"


#ifndef RULESLATVIAN_H
#define RULESLATVIAN_H

/*
 * Copyright Alexander Beider and Stephen P. Morse, 2008, 2017
 * Copyright Olegs Capligins, 2013-2016
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * It is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 *
 * You should have received a copy of the GNU General Public License.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 */
// GENERAL
rulesLatvian = {
    // CONSONANTS
    {"č", "", "", "tS"},
    {"ģ", "", "", "(d|dj)"},
    //array("ķ","","","(t|ti)"),
    {"ķ", "", "", "(t|tj)"},
    //array("ļ","","","lj"),
    {"ļ", "", "", "l"},
    {"š", "", "", "S"},
    {"ņ", "", "", "(n|nj)"},
    {"ž", "", "", "Z"},
    // SPECIAL VOWELS
    {"ā", "", "", "a"},
    {"ē", "", "", "e"},
    {"ī", "", "", "i"},
    {"ū", "", "", "u"},
    // DIPHTONGS
    //array("ai","","","(D|ai)"),
    {"ai", "", "", "aj"},
    //array("ei","","","(D|ei)"),
    {"ei", "", "", "ej"},
    {"io", "", "", "jo"},
    // array("iu","","","(D|iu)"),
    {"iu", "", "", "ju"},
    //array("ie","","","(D|ie)"),
    {"ie", "", "", "je"},
    //array("o","","","(D|uo)"),
    {"o", "", "", "o"},
    //array("ui","","","(D|ui)"),
    {"ui", "", "", "uj"},
    // LATIN ALPHABET
    {"a", "", "", "a"},
    {"b", "", "", "b"},
    {"c", "", "", "ts"},
    {"d", "", "", "d"},
    {"e", "", "", "E"},
    {"f", "", "", "f"},
    {"g", "", "", "g"},
    {"h", "", "", "h"},
    {"i", "", "", "I"},
    {"j", "", "", "j"},
    {"k", "", "", "k"},
    {"l", "", "", "l"},
    {"m", "", "", "m"},
    {"n", "", "", "n"},
    {"p", "", "", "p"},
    {"r", "", "", "r"},
    {"s", "", "", "s"},
    {"t", "", "", "t"},
    {"u", "", "", "u"},
    {"v", "", "", "v"},
    {"z", "", "", "z"},
    {"ruleslatvian"},
};
rules[LanguageIndex("latvian", languages)] = rulesLatvian;

#else
#endif