#!/bin/bash

echo "concatenating test suites from dk data..."
cat tests/suite-ash.txt tests/suite-gen.txt tests/suite-sep.txt > tests/suite-all.txt
wc -l tests/suite-all.txt

echo "running tests..."
php bmpm-php/tests-run.php tests/suite-all.txt
