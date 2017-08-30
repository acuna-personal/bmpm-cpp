#!/bin/bash

# Usage: tests/run.sh [num-tests-randomly-selected-from-each-suite]

rm tests/suites/suite-all.txt
for f in `find tests/suites/* -maxdepth 1`; do
	if [ $1 > 0 ]
	then
		echo "Randomly picking $1 tests from each suite"
		gshuf $f | head -500 >> tests/suites/suite-all.txt
	else
		echo "Concatenating all tests from all suite"
		cat $f >> tests/suites/suite-all.txt
	fi
done

wc -l tests/suites/suite-all.txt

echo "Running tests..."
php bmpm-php/tests-run.php tests/suites/suite-all.txt
