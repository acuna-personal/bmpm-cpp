<?php

error_reporting(E_ALL);

require_once "php2cpp/vendor/autoload.php";
require_once "php2cpp/Translator.php";
require_once "php2cpp/PrettyPrinterCpp.php";

use PhpParser\ParserFactory;

$inputDirectory = 'bmpm-php';
$outputDirectory = 'bmpm-cpp';
/*
system('rm -rf ' . escapeshellarg($outputDirectory), $retval);
if ($retval) {
	echo "Unable to remove output directory";
	exit(1);
}
*/
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7); // a "reasonable default"
$prettyPrinter = new PrettyPrinterCpp();

$translator = new Translator($parser, $prettyPrinter);

$translator->setIgnores(array(
	'/^\.git$/',
	'/^\.DS_Store$/',
	));

$translator->translate("bmpm-php", "bmpm-cpp");

?>