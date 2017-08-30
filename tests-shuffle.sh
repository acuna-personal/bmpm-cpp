#!/bin/bash

# Usage: tests-shuffle.sh [num-tests-randomly-selected-from-each-suite]

rm tests/suites/suite-all.txt
for f in `find tests/suites/* -maxdepth 1`; do
	if [ $1 > 0 ]
	then
		echo "Randomly picking $1 tests from $f"
		gshuf $f | head -$1 >> tests/suites/suite-all.txt
	else
		echo "Concatenating all tests from $f"
		cat $f >> tests/suites/suite-all.txt
	fi
done

wc -l tests/suites/suite-all.txt
