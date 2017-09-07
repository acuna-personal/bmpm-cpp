#include <string>
#include <vector>
#include <sstream>
#include <iostream>
#include <cstdio>

#ifndef PHP2CPP_H
#define PHP2CPP_H

template<typename T>
std::vector<T> array_merge();

std::string trim(std::string str);
bool in_array();
int preg_match(std::string pattern, std::string subject);
std::string strtr();
std::string preg_replace(std::string pattern, std::string replacement, std::string subject);
std::vector<std::string> preg_split(std::string pattern, std::string subject);

std::size_t count(std::string str);

std::size_t strlen(std::string str);
std::string strtolower(std::string str);
std::string substr(std::string str, std::size_t start, std::size_t length);
std::size_t strpos(std::string haystack, std::string needle);
void print_r(void *val);
std::string str_replace(std::string search, std::string replace, std::string subject);

std::vector<std::string> explode(std::string str, std::string delimiter);

template<typename T>
std::string implode(std::string glue, std::vector<T> pieces);

int ord(std::string str);
std::string dechex(int n);

#else
#endif
