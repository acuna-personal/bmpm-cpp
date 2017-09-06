#include <iostream>
#include <string>
#include <cstdio>

#ifndef PHP2CPP_H
#define PHP2CPP_H

php_type array_merge();
php_type trim();
php_type debug_print_backtrace();
php_type in_array();
php_type preg_match();
php_type chr();
php_type strtr();
php_type preg_replace();
php_type preg_split();
php_type count();
std::string::size_type strlen(std::string str);
std::string strtolower(std::string str);
php_type substr();
php_type printf();
php_type strpos();
php_type print_r();
php_type utf8_encode();
php_type html_entity_decode();
php_type mb_strtolower();
php_type pow();
php_type log();
php_type floor();
php_type mb_detect_encoding();
php_type str_replace();
php_type explode();
php_type implode();
php_type array_pop();
php_type ord();
php_type dechex();
php_type fopen();
php_type fgets();

#else
#endif
