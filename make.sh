#!/bin/bash

for f in `find bmpm-cpp/* -maxdepth 2 | grep \\.cpp\$`; do
    echo g++ -c $f
    g++ -c $f
done

#g++ -obmpm *.o


