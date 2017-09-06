#include "cpp/php2cpp.h"


#ifndef LANG_CPP
#define LANG_CPP

#include "lang.h"

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
// GENERIC
arabic = LanguageCode("arabic", languages);
cyrillic = LanguageCode("cyrillic", languages);
czech = LanguageCode("czech", languages);
dutch = LanguageCode("dutch", languages);
english = LanguageCode("english", languages);
french = LanguageCode("french", languages);
german = LanguageCode("german", languages);
greek = LanguageCode("greek", languages);
greeklatin = LanguageCode("greeklatin", languages);
hebrew = LanguageCode("hebrew", languages);
hungarian = LanguageCode("hungarian", languages);
italian = LanguageCode("italian", languages);
latvian = LanguageCode("latvian", languages);
polish = LanguageCode("polish", languages);
portuguese = LanguageCode("portuguese", languages);
romanian = LanguageCode("romanian", languages);
russian = LanguageCode("russian", languages);
spanish = LanguageCode("spanish", languages);
turkish = LanguageCode("turkish", languages);
// make the sum of all languages be visible in the function
all = english + german + polish + romanian + russian + hungarian + spanish + french + cyrillic + hebrew + portuguese + italian + dutch + czech + turkish + greek + greeklatin + arabic + latvian;
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
    {"/^o’/", english, true},
    {"/^o'/", english, true},
    {"/^mc/", english, true},
    {"/^fitz/", english, true},
    {"/ceau/", french + romanian, true},
    {"/eau/", romanian, true},
    {"/ault$/", french, true},
    {"/oult$/", french, true},
    {"/eux$/", french, true},
    {"/eix$/", french, true},
    {"/glou$/", greeklatin, true},
    {"/uu/", dutch, true},
    {"/tx/", spanish, true},
    {"/witz/", german, true},
    {"/tz$/", german + russian + english, true},
    {"/^tz/", russian + english, true},
    {"/poulos$/", greeklatin, true},
    {"/pulos$/", greeklatin, true},
    {"/iou/", greeklatin, true},
    {"/sj$/", dutch, true},
    {"/^sj/", dutch, true},
    {"/güe/", spanish, true},
    {"/güi/", spanish, true},
    {"/ghe/", romanian + greeklatin, true},
    {"/ghi/", romanian + greeklatin, true},
    {"/escu$/", romanian, true},
    {"/esco$/", romanian, true},
    {"/vici$/", romanian, true},
    {"/schi$/", romanian, true},
    {"/ii$/", russian, true},
    {"/iy$/", russian, true},
    {"/yy$/", russian, true},
    {"/yi$/", russian, true},
    {"/^rz/", polish, true},
    {"/rz$/", polish + german, true},
    {"/[bcdfgklmnpstwz]rz/", polish, true},
    {"/rz[bcdfghklmnpstw]/", polish, true},
    {"/cki$/", polish, true},
    {"/ska$/", polish, true},
    {"/cka$/", polish, true},
    {"/ae/", german + russian + english, true},
    {"/oe/", german + french + russian + english + dutch, true},
    {"/th$/", german + english, true},
    {"/^th/", german + english + greeklatin, true},
    {"/mann/", german, true},
    {"/cz/", polish, true},
    {"/cy/", polish + greeklatin, true},
    {"/niew/", polish, true},
    {"/etti$/", italian, true},
    {"/eti$/", italian, true},
    {"/ati$/", italian, true},
    {"/ato$/", italian, true},
    {"/[aoei]no$/", italian, true},
    {"/[aoei]ni$/", italian, true},
    {"/esi$/", italian, true},
    {"/oli$/", italian, true},
    {"/field$/", english, true},
    {"/stein/", german, true},
    {"/heim$/", german, true},
    {"/heimer$/", german, true},
    {"/thal/", german, true},
    {"/zweig/", german, true},
    {"/[aeou]h/", german, true},
    {"/äh/", german, true},
    {"/öh/", german, true},
    {"/üh/", german, true},
    {"/[ln]h[ao]$/", portuguese, true},
    {"/[ln]h[aou]/", portuguese + french + german + dutch + czech + spanish + turkish, true},
    {"/chsch/", german, true},
    {"/tsch/", german, true},
    {"/sch$/", german + russian, true},
    {"/^sch/", german + russian, true},
    {"/ck$/", german + english, true},
    {"/c$/", polish + romanian + hungarian + czech + turkish, true},
    {"/sz/", polish + hungarian, true},
    {"/cs$/", hungarian, true},
    {"/^cs/", hungarian, true},
    {"/dzs/", hungarian, true},
    {"/zs$/", hungarian, true},
    {"/^zs/", hungarian, true},
    {"/^wl/", polish, true},
    {"/^wr/", polish + english + german + dutch, true},
    {"/gy$/", hungarian, true},
    {"/gy[aeou]/", hungarian, true},
    {"/gy/", hungarian + russian + french + greeklatin, true},
    {"/guy/", french, true},
    {"/gu[ei]/", spanish + french + portuguese, true},
    {"/gu[ao]/", spanish + portuguese, true},
    {"/gi[aou]/", italian + greeklatin, true},
    {"/ly/", hungarian + russian + polish + greeklatin, true},
    {"/ny/", hungarian + russian + polish + spanish + greeklatin, true},
    {"/ty/", hungarian + russian + polish + greeklatin, true},
    // 1.2 special characters
    {"/ā/", latvian, true},
    {"/ć/", polish, true},
    {"/ç/", french + spanish + portuguese + turkish, true},
    {"/č/", czech + latvian, true},
    {"/ď/", czech, true},
    {"/ē/", latvian, true},
    {"/ğ/", turkish, true},
    {"/ģ/", latvian, true},
    {"/ī/", latvian, true},
    {"/ķ/", latvian, true},
    {"/ļ/", latvian, true},
    {"/ł/", polish, true},
    {"/ņ/", latvian, true},
    {"/ń/", polish, true},
    {"/ñ/", spanish, true},
    {"/ň/", czech, true},
    {"/ř/", czech, true},
    {"/ś/", polish, true},
    {"/ş/", romanian + turkish, true},
    {"/š/", czech + latvian, true},
    {"/ţ/", romanian, true},
    {"/ť/", czech, true},
    {"/ź/", polish, true},
    {"/ž/", czech + latvian, true},
    {"/ż/", polish, true},
    {"/ß/", german, true},
    {"/ä/", german, true},
    {"/á/", hungarian + spanish + portuguese + czech + greeklatin, true},
    {"/â/", romanian + french + portuguese, true},
    {"/ă/", romanian, true},
    {"/ą/", polish, true},
    {"/à/", portuguese, true},
    {"/ã/", portuguese, true},
    {"/ę/", polish, true},
    {"/é/", french + hungarian + czech + greeklatin, true},
    {"/è/", french + spanish + italian, true},
    {"/ê/", french, true},
    {"/ě/", czech, true},
    {"/ê/", french + portuguese, true},
    {"/í/", hungarian + spanish + portuguese + czech + greeklatin, true},
    {"/î/", romanian + french, true},
    {"/ı/", turkish, true},
    {"/ó/", polish + hungarian + spanish + italian + portuguese + czech + greeklatin, true},
    {"/ö/", german + hungarian + turkish, true},
    {"/ô/", french + portuguese, true},
    {"/õ/", portuguese + hungarian, true},
    {"/ò/", italian + spanish, true},
    {"/ű/", hungarian, true},
    {"/ú/", hungarian + spanish + portuguese + czech + greeklatin, true},
    {"/ü/", german + hungarian + spanish + portuguese + turkish, true},
    {"/ù/", french, true},
    {"/ů/", czech, true},
    {"/ý/", czech + greeklatin, true},
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
    // Every Greek word has at least one Greek vowel
    {"/α/", greek, true},
    {"/ε/", greek, true},
    {"/η/", greek, true},
    {"/ι/", greek, true},
    {"/ο/", greek, true},
    {"/υ/", greek, true},
    {"/ω/", greek, true},
    // Arabic (only initial)
    {"/ا/", arabic, true},
    // alif (isol + init)
    {"/ب/", arabic, true},
    // ba'
    {"/ت/", arabic, true},
    // ta'
    {"/ث/", arabic, true},
    // tha'
    {"/ج/", arabic, true},
    // jim
    {"/ح/", arabic, true},
    // h.a'
    {"/خ'/", arabic, true},
    // kha'
    {"/د/", arabic, true},
    // dal (isol + init)
    {"/ذ/", arabic, true},
    // dhal (isol + init)
    {"/ر/", arabic, true},
    // ra' (isol + init)
    {"/ز/", arabic, true},
    // za' (isol + init)
    {"/س/", arabic, true},
    // sin
    {"/ش/", arabic, true},
    // shin
    {"/ص/", arabic, true},
    // s.ad
    {"/ض/", arabic, true},
    // d.ad
    {"/ط/", arabic, true},
    // t.a'
    {"/ظ/", arabic, true},
    // z.a'
    {"/ع/", arabic, true},
    // 'ayn
    {"/غ/", arabic, true},
    // ghayn
    {"/ف/", arabic, true},
    // fa'
    {"/ق/", arabic, true},
    // qaf
    {"/ك/", arabic, true},
    // kaf
    {"/ل/", arabic, true},
    // lam
    {"/م/", arabic, true},
    // mim
    {"/ن/", arabic, true},
    // nun
    {"/ه/", arabic, true},
    // ha'
    {"/و/", arabic, true},
    // waw (isol + init)
    {"/ي/", arabic, true},
    // ya'
    {"/آ/", arabic, true},
    // alif madda
    {"/إ/", arabic, true},
    // alif + diacritic
    {"/أ/", arabic, true},
    // alif + hamza
    {"/ؤ/", arabic, true},
    //  waw + hamza
    {"/ئ/", arabic, true},
    //  ya' + hamza
    //    array("/لا/‎", $arabic, true), // ligature l+a
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
    {"/a/", cyrillic + hebrew + greek + arabic, false},
    {"/o/", cyrillic + hebrew + greek + arabic, false},
    {"/e/", cyrillic + hebrew + greek + arabic, false},
    {"/i/", cyrillic + hebrew + greek + arabic, false},
    {"/y/", cyrillic + hebrew + greek + arabic + romanian + latvian + dutch, false},
    {"/u/", cyrillic + hebrew + greek + arabic, false},
    {"/j/", italian, false},
    {"/j[^aoeiuy]/", french + spanish + portuguese + greeklatin, false},
    {"/g/", czech, false},
    {"/k/", romanian + spanish + portuguese + french + italian, false},
    {"/q/", hungarian + polish + russian + latvian + romanian + czech + dutch + turkish + greeklatin, false},
    {"/v/", polish, false},
    {"/w/", french + romanian + spanish + latvian + hungarian + russian + czech + turkish + greeklatin, false},
    {"/x/", czech + hungarian + dutch + turkish + latvian, false},
    // polish excluded from the list
    {"/dj/", spanish + turkish, false},
    {"/v[^aoeiu]/", german, false},
    // in german, "v" can be found before a vowel only
    {"/y[^aoeiu]/", german, false},
    // in german, "y" usually appears only in the last position; sometimes before a vowel
    {"/c[^aohk]/", german, false},
    {"/dzi/", german + english + french + turkish, false},
    {"/ou/", german, false},
    {"/a[eiou]/", turkish, false},
    // no diphthongs in Turkish
    {"/ö[eaiou]/", turkish, false},
    {"/ü[eaiou]/", turkish, false},
    {"/e[aiou]/", turkish, false},
    {"/i[aeou]/", turkish, false},
    {"/o[aieu]/", turkish, false},
    {"/u[aieo]/", turkish, false},
    {"/aj/", german + english + french + dutch, false},
    {"/ej/", german + english + french + dutch, false},
    {"/oj/", german + english + french + dutch, false},
    {"/uj/", german + english + french + dutch, false},
    {"/eu/", russian + polish, false},
    {"/ky/", polish, false},
    {"/kie/", french + spanish + greeklatin, false},
    {"/gie/", portuguese + romanian + spanish + greeklatin, false},
    {"/ch[aou]/", italian, false},
    {"/ch/", turkish, false},
    {"/son$/", german, false},
    {"/sc[ei]/", french, false},
    {"/sch/", hungarian + polish + french + spanish, false},
    {"/^h/", russian, false},
};

#else
#endif