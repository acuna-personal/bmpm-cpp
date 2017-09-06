#include "cpp/php2cpp.h"


#ifndef LANG_H
#define LANG_H

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
// ASHKENAZIC
cyrillic = LanguageCode("cyrillic", languages);
english = LanguageCode("english", languages);
french = LanguageCode("french", languages);
german = LanguageCode("german", languages);
hebrew = LanguageCode("hebrew", languages);
hungarian = LanguageCode("hungarian", languages);
polish = LanguageCode("polish", languages);
romanian = LanguageCode("romanian", languages);
russian = LanguageCode("russian", languages);
spanish = LanguageCode("spanish", languages);
// make the sum of all languages be visible in the function
all = english + german + polish + romanian + russian + hungarian + spanish + french + cyrillic + hebrew;
// format of entries in $languageRules table is
//    (pattern, language, acceptance)
// where
//    pattern is a regular expression
//      e.g., ^ means start of word, $ means end of word, [^ei] means anything but e or i, etc.
//    language is one or more of the languages defined above separated by + signs
//    acceptance is true or false
// meaning is:
//    if "pattern" matches and acceptance is true, name is in one of the languages indicated and no others
//    if "pattern" matches and acceptance is false, name is not in any of the languages indicated
languageRules = {
    // 1. following are rules to accept the language
    // 1.1 Special letter combinations
    {"/zh/", polish + russian + german + english, true},
    {"/eau/", french, true},
    {"/[aoeiuäöü]h/", german, true},
    {"/^vogel/", german, true},
    {"/vogel$/", german, true},
    {"/witz/", german, true},
    {"/tz$/", german + russian + english, true},
    {"/^tz/", russian + english, true},
    {"/güe/", spanish, true},
    {"/güi/", spanish, true},
    {"/ghe/", romanian, true},
    {"/ghi/", romanian, true},
    {"/vici$/", romanian, true},
    {"/schi$/", romanian, true},
    {"/chsch/", german, true},
    {"/tsch/", german, true},
    {"/ssch/", german, true},
    {"/sch$/", german + russian, true},
    {"/^sch/", german + russian, true},
    {"/^rz/", polish, true},
    {"/rz$/", polish + german, true},
    {"/[^aoeiuäöü]rz/", polish, true},
    {"/rz[^aoeiuäöü]/", polish, true},
    {"/cki$/", polish, true},
    {"/ska$/", polish, true},
    {"/cka$/", polish, true},
    {"/ue/", german + russian, true},
    {"/ae/", german + russian + english, true},
    {"/oe/", german + french + russian + english, true},
    {"/th$/", german, true},
    {"/^th/", german, true},
    {"/th[^aoeiu]/", german, true},
    {"/mann/", german, true},
    {"/cz/", polish, true},
    {"/cy/", polish, true},
    {"/niew/", polish, true},
    {"/stein/", german, true},
    {"/heim$/", german, true},
    {"/heimer$/", german, true},
    {"/ii$/", russian, true},
    {"/iy$/", russian, true},
    {"/yy$/", russian, true},
    {"/yi$/", russian, true},
    {"/yj$/", russian, true},
    {"/ij$/", russian, true},
    {"/gaus$/", russian, true},
    {"/gauz$/", russian, true},
    {"/gauz$/", russian, true},
    {"/goltz$/", russian, true},
    {"/gol'tz$/", russian, true},
    {"/golts$/", russian, true},
    {"/gol'ts$/", russian, true},
    {"/^goltz/", russian, true},
    {"/^gol'tz/", russian, true},
    {"/^golts/", russian, true},
    {"/^gol'ts/", russian, true},
    {"/gendler$/", russian, true},
    {"/gejmer$/", russian, true},
    {"/gejm$/", russian, true},
    {"/geimer$/", russian, true},
    {"/geim$/", russian, true},
    {"/geymer/", russian, true},
    {"/geym$/", russian, true},
    {"/gof$/", russian, true},
    {"/thal/", german, true},
    {"/zweig/", german, true},
    {"/ck$/", german + english, true},
    {"/c$/", polish + romanian + hungarian, true},
    {"/sz/", polish + hungarian, true},
    {"/gue/", spanish + french, true},
    {"/gui/", spanish + french, true},
    {"/guy/", french, true},
    {"/cs$/", hungarian, true},
    {"/^cs/", hungarian, true},
    {"/dzs/", hungarian, true},
    {"/zs$/", hungarian, true},
    {"/^zs/", hungarian, true},
    {"/^wl/", polish, true},
    {"/^wr/", polish + english + german, true},
    {"/gy$/", hungarian, true},
    {"/gy[aeou]/", hungarian, true},
    {"/gy/", hungarian + russian, true},
    {"/ly/", hungarian + russian + polish, true},
    {"/ny/", hungarian + russian + polish, true},
    {"/ty/", hungarian + russian + polish, true},
    // 1.2 special characters
    {"/â/", romanian + french, true},
    {"/ă/", romanian, true},
    {"/à/", french, true},
    {"/ä/", german, true},
    {"/á/", hungarian + spanish, true},
    {"/ą/", polish, true},
    {"/ć/", polish, true},
    {"/ç/", french, true},
    {"/ę/", polish, true},
    {"/é/", french + hungarian + spanish, true},
    {"/è/", french, true},
    {"/ê/", french, true},
    {"/í/", hungarian + spanish, true},
    {"/î/", romanian + french, true},
    {"/ł/", polish, true},
    {"/ń/", polish, true},
    {"/ñ/", spanish, true},
    {"/ó/", polish + hungarian + spanish, true},
    {"/ö/", german + hungarian, true},
    {"/õ/", hungarian, true},
    {"/ş/", romanian, true},
    {"/ś/", polish, true},
    {"/ţ/", romanian, true},
    {"/ü/", german + hungarian, true},
    {"/ù/", french, true},
    {"/ű/", hungarian, true},
    {"/ú/", hungarian + spanish, true},
    {"/ź/", polish, true},
    {"/ż/", polish, true},
    {"/ß/", german, true},
    // Every Cyrillic word has at least one Cyrillic vowel (аёеоиуыэюя)
    {"/а/", cyrillic, true},
    {"/ё/", cyrillic, true},
    {"/о/", cyrillic, true},
    {"/е/", cyrillic, true},
    {"/и/", cyrillic, true},
    {"/у/", cyrillic, true},
    {"/ы/", cyrillic, true},
    {"/э/", cyrillic, true},
    {"/ю/", cyrillic, true},
    {"/я/", cyrillic, true},
    // Hebrew
    {"/א/", hebrew, true},
    {"/ב/", hebrew, true},
    {"/ג/", hebrew, true},
    {"/ד/", hebrew, true},
    {"/ה/", hebrew, true},
    {"/ו/", hebrew, true},
    {"/ז/", hebrew, true},
    {"/ח/", hebrew, true},
    {"/ט/", hebrew, true},
    {"/י/", hebrew, true},
    {"/כ/", hebrew, true},
    {"/ל/", hebrew, true},
    {"/מ/", hebrew, true},
    {"/נ/", hebrew, true},
    {"/ס/", hebrew, true},
    {"/ע/", hebrew, true},
    {"/פ/", hebrew, true},
    {"/צ/", hebrew, true},
    {"/ק/", hebrew, true},
    {"/ר/", hebrew, true},
    {"/ש/", hebrew, true},
    {"/ת/", hebrew, true},
    // 2. following are rules to reject the language
    // Every Latin character word has at least one Latin vowel
    {"/a/", cyrillic + hebrew, false},
    {"/o/", cyrillic + hebrew, false},
    {"/e/", cyrillic + hebrew, false},
    {"/i/", cyrillic + hebrew, false},
    {"/y/", cyrillic + hebrew + romanian, false},
    {"/u/", cyrillic + hebrew, false},
    {"/v[^aoeiuäüö]/", german, false},
    // in german, "v" can be found before a vowel only
    {"/y[^aoeiu]/", german, false},
    // in german, "y" usually appears only in the last position; sometimes before a vowel
    {"/c[^aohk]/", german, false},
    {"/dzi/", german + english + french, false},
    {"/ou/", german, false},
    {"/aj/", german + english + french, false},
    {"/ej/", german + english + french, false},
    {"/oj/", german + english + french, false},
    {"/uj/", german + english + french, false},
    {"/k/", romanian, false},
    {"/v/", polish, false},
    {"/ky/", polish, false},
    {"/eu/", russian + polish, false},
    {"/w/", french + romanian + spanish + hungarian + russian, false},
    {"/kie/", french + spanish, false},
    {"/gie/", french + romanian + spanish, false},
    {"/q/", hungarian + polish + russian + romanian, false},
    {"/sch/", hungarian + polish + french + spanish, false},
    {"/^h/", russian, false},
};

#else
#endif