#!/bin/bash

rm tests/suites/*

echo "Generating Beider tests..."

php tests-gen-beider.php tests/sources/djsara3.txt tests/suites/suite-djsara3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarb3.txt tests/suites/suite-djsarb3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarc3.txt tests/suites/suite-djsarc3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsard3.txt tests/suites/suite-djsard3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsare3.txt tests/suites/suite-djsare3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarf3.txt tests/suites/suite-djsarf3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarg3.txt tests/suites/suite-djsarg3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarh3.txt tests/suites/suite-djsarh3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsari3.txt tests/suites/suite-djsari3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarj3.txt tests/suites/suite-djsarj3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsark3.txt tests/suites/suite-djsark3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarl3.txt tests/suites/suite-djsarl3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/djsarm3.txt tests/suites/suite-djsarm3.txt spanish+german+polish+romanian ash
php tests-gen-beider.php tests/sources/faiga3.txt tests/suites/suite-faiga3.txt auto sep
php tests-gen-beider.php tests/sources/faigb3.txt tests/suites/suite-faigb3.txt auto sep
php tests-gen-beider.php tests/sources/faigc3.txt tests/suites/suite-faigc3.txt auto sep
php tests-gen-beider.php tests/sources/faigd3.txt tests/suites/suite-faigd3.txt auto sep
php tests-gen-beider.php tests/sources/tello3.txt tests/suites/suite-tello3.txt auto sep
php tests-gen-beider.php tests/sources/sebag3.txt tests/suites/suite-sebag3.txt auto sep
php tests-gen-beider.php tests/sources/xdjsara3.txt tests/suites/suite-xdjsara3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarb3.txt tests/suites/suite-xdjsarb3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarc3.txt tests/suites/suite-xdjsarc3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsard3.txt tests/suites/suite-xdjsard3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsare3.txt tests/suites/suite-xdjsare3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarf3.txt tests/suites/suite-xdjsarf3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarg3.txt tests/suites/suite-xdjsarg3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarh3.txt tests/suites/suite-xdjsarh3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsari3.txt tests/suites/suite-xdjsari3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarj3.txt tests/suites/suite-xdjsarj3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsark3.txt tests/suites/suite-xdjsark3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarl3.txt tests/suites/suite-xdjsarl3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xdjsarm3.txt tests/suites/suite-xdjsarm3.txt spanish+german+polish+romanian gen
php tests-gen-beider.php tests/sources/xtello3.txt tests/suites/suite-xtello3.txt auto gen
php tests-gen-beider.php tests/sources/xsebag3.txt tests/suites/suite-xsebag3.txt auto gen

echo "Generating DK tests..."

php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-gen.txt gen auto
php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-ash.txt ash auto
php bmver307/tests-gen.php tests/sources/dk.txt tests/suites/suite-sep.txt sep auto

./tests-shuffle.sh