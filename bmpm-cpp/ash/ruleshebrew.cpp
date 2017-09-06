#include "cpp/php2cpp.h"


#ifndef RULESHEBREW_CPP
#define RULESHEBREW_CPP

#include "ruleshebrew.h"

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
// Ashkenazic
rulesHebrew = {
    {"אי", "", "", "i"},
    {"עי", "", "", "i"},
    {"עו", "", "", "VV"},
    {"או", "", "", "VV"},
    {"ג׳", "", "", "Z"},
    {"ד׳", "", "", "dZ"},
    {"א", "", "", "L"},
    {"ב", "", "", "b"},
    {"ג", "", "", "g"},
    {"ד", "", "", "d"},
    {"ה", "^", "", "1"},
    {"ה", "", "$", "1"},
    {"ה", "", "", ""},
    {"וו", "", "", "V"},
    {"וי", "", "", "WW"},
    {"ו", "", "", "W"},
    {"ז", "", "", "z"},
    {"ח", "", "", "X"},
    {"ט", "", "", "T"},
    {"יי", "", "", "i"},
    {"י", "", "", "i"},
    {"ך", "", "", "X"},
    {"כ", "^", "", "K"},
    {"כ", "", "", "k"},
    {"ל", "", "", "l"},
    {"ם", "", "", "m"},
    {"מ", "", "", "m"},
    {"ן", "", "", "n"},
    {"נ", "", "", "n"},
    {"ס", "", "", "s"},
    {"ע", "", "", "L"},
    {"ף", "", "", "f"},
    {"פ", "", "", "f"},
    {"ץ", "", "", "C"},
    {"צ", "", "", "C"},
    {"ק", "", "", "K"},
    {"ר", "", "", "r"},
    {"ש", "", "", "s"},
    {"ת", "", "", "TB"},
    // only Ashkenazic
    {"ruleshebrew"},
};
rules[LanguageIndex("hebrew", languages)] = rulesHebrew;

#else
#endif