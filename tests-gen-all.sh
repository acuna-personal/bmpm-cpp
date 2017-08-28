#!/bin/bash

php bmver307/tests-gen.php tests/dk.txt tests/suite-gen.txt gen any
php bmver307/tests-gen.php tests/dk.txt tests/suite-ash.txt ash any
php bmver307/tests-gen.php tests/dk.txt tests/suite-sep.txt sep any
