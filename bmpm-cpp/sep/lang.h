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
french = LanguageCode("french", languages);
hebrew = LanguageCode("hebrew", languages);
italian = LanguageCode("italian", languages);
portuguese = LanguageCode("portuguese", languages);
spanish = LanguageCode("spanish", languages);
all = italian + spanish + french + portuguese + hebrew;
languageRules = {
    // 1. following are rules to accept the language
    // 1.1 Special letter combinations
    {"/eau/", french, true},
    {"/ou/", french, true},
    {"/gni/", italian + french, true},
    {"/tx/", spanish, true},
    {"/tj/", spanish, true},
    {"/gy/", french, true},
    {"/guy/", french, true},
    {"/sh/", spanish + portuguese, true},
    // English, but no sign for /sh/ in these languages
    {"/lh/", portuguese, true},
    {"/nh/", portuguese, true},
    {"/ny/", spanish, true},
    {"/gue/", spanish + french, true},
    {"/gui/", spanish + french, true},
    {"/gia/", italian, true},
    {"/gie/", italian, true},
    {"/gio/", italian, true},
    {"/giu/", italian, true},
    // 1.2 special characters
    {"/ñ/", spanish, true},
    {"/â/", portuguese + french, true},
    {"/á/", portuguese + spanish, true},
    {"/à/", portuguese, true},
    {"/ã/", portuguese, true},
    {"/ê/", french + portuguese, true},
    {"/í/", portuguese + spanish, true},
    {"/î/", french, true},
    {"/ô/", french + portuguese, true},
    {"/õ/", portuguese, true},
    {"/ò/", italian + spanish, true},
    {"/ú/", portuguese + spanish, true},
    {"/ù/", french, true},
    {"/ü/", portuguese + spanish, true},
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
    {"/a/", hebrew, false},
    {"/o/", hebrew, false},
    {"/e/", hebrew, false},
    {"/i/", hebrew, false},
    {"/y/", hebrew, false},
    {"/u/", hebrew, false},
    {"/kh/", spanish, false},
    {"/gua/", italian, false},
    {"/guo/", italian, false},
    {"/ç/", italian, false},
    {"/cha/", italian, false},
    {"/cho/", italian, false},
    {"/chu/", italian, false},
    {"/j/", italian, false},
    {"/dj/", spanish, false},
    {"/sce/", french, false},
    {"/sci/", french, false},
    {"/ó/", french, false},
    {"/è/", portuguese, false},
};

#else
#endif