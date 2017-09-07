#include "cpp/php2cpp.h"


#ifndef TESTS_RUN_CPP
#define TESTS_RUN_CPP

#include "tests-run.h"

/* ORIG: include_once */
#include "BMPM.h"
void beep()
{
    std::cout << "\7";
}

int main(int argc, const char * argv[]);
{
    inputFileName = argv[1];
    // process each line in the input file
    fails = 0;
    handle = fopen(inputFileName, "r");
    if (!handle) {
        std::cout << "Unable to open: " + inputFileName + "\n";
        return 1;
    }
    ln = 1;
    while ((line = fgets(handle)) != /* ORIG: !== */ false) {
        if (ln % 100 == 0) {
            std::cout << "" + inputFileName + ":" + ln + "\n";
        }
        comps = explode("\t", trim(line, "\n\r"));
        // some lines may have trailing tabs which we must keep
        if (count(comps) != 7) {
            std::cout << "" + inputFileName + ":" + ln + " invalid line: " + line + "\n";
            fails++;
            beep();
            continue;
        }
        list(name, typeName, languageName, bmpmExactExpected, bmpmApproxExpected, bmpmHebrewExpected, soundexExpected) = comps;
        if (debug) {
            std::cout << "" + inputFileName + ":" + ln + " [[" + name + " " + typeName + " " + languageName + "]]\n";
        }
        try {
            // TODO: Order shouldn't matter for any of these
            bmpmApproxActual = BMPM::getPhoneticEncoding(name, BMPMTypeForName(typeName), BMPMLanguageForName(languageName), BMPM::MATCHING_APPROXIMATE);
            if (bmpmApproxActual != bmpmApproxExpected) {
                std::cout << "" + inputFileName + ":" + ln + " BMPM::getPhoneticEncoding failed: [[" + name + " " + typeName + " " + languageName + " approx]]\n[[expected]] " + bmpmApproxExpected + "\n  [[actual]] " + bmpmApproxActual + "\n";
                fails++;
                beep();
            }
            bmpmExactActual = BMPM::getPhoneticEncoding(name, BMPMTypeForName(typeName), BMPMLanguageForName(languageName), BMPM::MATCHING_EXACT);
            if (bmpmExactActual != bmpmExactExpected) {
                std::cout << "" + inputFileName + ":" + ln + " BMPM::getPhoneticEncoding failed: [[" + name + " " + typeName + " " + languageName + " exact]]\n[[expected]] " + bmpmExactExpected + "\n  [[actual]] " + bmpmExactActual + "\n";
                fails++;
                beep();
            }
            /*
                  $bmpmHebrewActual = BMPM::getPhoneticEncoding($name, BMPMTypeForName($typeName), BMPMLanguageForName($languageName), BMPM::MATCHING_HEBREW);
                  if ($bmpmHebrewActual != $bmpmHebrewExpected) {
                    echo "$inputFileName:" . $ln . " BMPM::getPhoneticEncoding failed: [[$name $typeName $languageName hebrew]]\n[[expected]] $bmpmHebrewExpected\n  [[actual]] $bmpmHebrewActual\n";
                    $fails++;
                    beep();
                  }
            */
            soundexExpected = trim(soundexExpected);
            soundexActual = BMPM::getDaitchMotokoffSoundex(name);
            if (soundexExpected != soundexActual) {
                std::cout << "" + inputFileName + ":" + ln + " BMPM::getDaitchMotokoffSoundex failed: [[" + name + "]]\n[[expected]] " + soundexExpected + "\n  [[actual]] " + soundexActual + "\n";
                fails++;
                beep();
            }
        } catch (Exception $e) {
            std::cout << "" + inputFileName + ":" + ln + " [[" + name + " " + typeName + " " + languageName + "]]\n";
            std::cout << e.getMessage() + "\n";
            std::cout << e.getTraceAsString() + "\n";
            return 1;
        }
        ln++;
    }
    std::cout << "" + fails + " tests failed\n";
    return 0;
}


#else
#endif