#!/bin/bash

php bmver307/tests-gen.php tests/dk.txt tests/suite-gen.txt gen auto
php bmver307/tests-gen.php tests/dk.txt tests/suite-ash.txt ash auto
php bmver307/tests-gen.php tests/dk.txt tests/suite-sep.txt sep auto
