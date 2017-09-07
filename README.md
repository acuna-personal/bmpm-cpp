# bmpm-cpp
Beider-Morse phonetic matching in C++ with bindings for Ruby

## php2cpp
Translate a subset of PHP to C++-esque code to facilitate easy ports of simple PHP libraries
- Generates ORIG comments to show original code that couldn't be translated to C++
- Generates TODO comments about memory management, etc
- Uses php_type as the type for functions and parameters, so it can be changed later
- Does not declare any variables, so you can use the compiler to figure out where they should be defined
- Outputs a list of functions that were called but not defined - these are probably PHP funcs that need to be implemented
- Preprocessor ignores PHP code between /***IFnCPP***/ and /***ENDIFNCPP***/
- Includes php2cpp-before.php, if present in the original code, for purposes of generating static data files etc
