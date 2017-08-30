#!/bin/bash

rm tests/suites/suite-gen.txt
rm tests/suites/suite-ash.txt
rm tests/suites/suite-sep.txt

php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-gen.txt gen auto
php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-ash.txt ash auto
php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-sep.txt sep auto
