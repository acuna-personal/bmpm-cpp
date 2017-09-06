#include "cpp/php2cpp.h"


#ifndef EXACTAPPROXCOMMON_CPP
#define EXACTAPPROXCOMMON_CPP

#include "exactapproxcommon.h"

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
exactApproxCommon = {
    {"h", "", "$", ""},
    // VOICED - UNVOICED CONSONANTS
    {"b", "", "[fktSs]", "p"},
    {"b", "", "p", ""},
    {"b", "", "$", "p"},
    {"p", "", "[gdZz]", "b"},
    {"p", "", "b", ""},
    {"v", "", "[pktSs]", "f"},
    {"v", "", "f", ""},
    {"v", "", "$", "f"},
    {"f", "", "[bgdZz]", "v"},
    {"f", "", "v", ""},
    {"g", "", "[pftSs]", "k"},
    {"g", "", "k", ""},
    {"g", "", "$", "k"},
    {"k", "", "[bdZz]", "g"},
    {"k", "", "g", ""},
    {"d", "", "[pfkSs]", "t"},
    {"d", "", "t", ""},
    {"d", "", "$", "t"},
    {"t", "", "[bgZz]", "d"},
    {"t", "", "d", ""},
    {"s", "", "dZ", ""},
    {"s", "", "tS", ""},
    {"z", "", "[pfkSt]", "s"},
    {"z", "", "[sSzZ]", ""},
    {"s", "", "[sSzZ]", ""},
    {"Z", "", "[sSzZ]", ""},
    {"S", "", "[sSzZ]", ""},
    // SIMPLIFICATION OF CONSONANT CLUSTERS
    {"jnm", "", "", "jm"},
    // DOUBLE --> SINGLE
    {"ji", "^", "", "i"},
    {"jI", "^", "", "I"},
    {"a", "", "[aAB]", ""},
    {"a", "[AB]", "", ""},
    {"A", "", "A", ""},
    {"B", "", "B", ""},
    {"b", "", "b", ""},
    {"d", "", "d", ""},
    {"f", "", "f", ""},
    {"g", "", "g", ""},
    {"k", "", "k", ""},
    {"l", "", "l", ""},
    {"m", "", "m", ""},
    {"n", "", "n", ""},
    {"p", "", "p", ""},
    {"r", "", "r", ""},
    {"t", "", "t", ""},
    {"v", "", "v", ""},
    {"z", "", "z", ""},
};

#else
#endif