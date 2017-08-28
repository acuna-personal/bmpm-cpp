#!/bin/bash

echo "concatenating test suites from dk data..."
rm tests/suite-all.txt
gshuf tests/suite-ash.txt | head -500 >> tests/suite-all.txt
gshuf tests/suite-sep.txt | head -500 >> tests/suite-all.txt
gshuf tests/suite-gen.txt | head -500 >> tests/suite-all.txt
#cat tests/suite-ash.txt tests/suite-gen.txt tests/suite-sep.txt > tests/suite-all.txt
wc -l tests/suite-all.txt

echo "running tests..."
php bmpm-php/tests-run.php tests/suite-all.txt
