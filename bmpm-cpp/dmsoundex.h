#include "cpp/php2cpp.h"


#ifndef DMSOUNDEX_H
#define DMSOUNDEX_H

/*
 *
 * Copyright Stephen P. Morse, 2005
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
php_type soundx_data();
php_type soundx_name(php_type MyStrArg);
php_type soundx_place(php_type MyStrArg);
php_type dmsoundex2(php_type MyStrArg);

#else
#endif