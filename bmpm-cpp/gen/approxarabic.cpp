#include "cpp/php2cpp.h"


#ifndef APPROXARABIC_CPP
#define APPROXARABIC_CPP

#include "approxarabic.h"

/*
 *
 * Copyright Alexander Beider and Stephen P. Morse, 2011
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
approxArabic = {{"1a", "", "", "(D|a)"}, {"1i", "", "", "(D|i|e)"}, {"1u", "", "", "(D|u|o)"}, {"j1", "", "", "(ja|je|jo|ju|j)"}, {"1", "", "", "(a|e|i|o|u|)"}, {"u", "", "", "(o|u)"}, {"i", "", "", "(i|e)"}, {"p", "", "$", "p"}, {"p", "", "", "(p|b)"}, {"approxarabic"}};
approx[LanguageIndex("arabic", languages)] = approxArabic;

#else
#endif