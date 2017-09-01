<?php

require 'vendor/autoload.php';

use PhpParser\ParserFactory;

class Translator {
	private $_ignores = null;
	private $_parser = null;
	private $_prettyPrinter = null;

	function __construct($parser, $prettyPrinter) {
		$this->_parser = $parser;
		$this->_prettyPrinter = $prettyPrinter;
	}

	function translate($inputDirectory, $outputDirectory) {
		if ($inputDirectory == '' || $outputDirectory == '') {
			echo "Must have input and output directory: inputDirectory=$inputDirectory outputDirectory=$outputDirectory\n";
			return false;
		}

		mkdir($outputDirectory, 0755, true);

		$directory = new \RecursiveDirectoryIterator($inputDirectory, \FilesystemIterator::FOLLOW_SYMLINKS);
		$filter = new \RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) {
			if ($this->getIgnores()) {
				foreach ($this->getIgnores() as $ignore) {
					if ($current->getFilename() == '.'
						|| $current->getFilename() == '..'
						|| preg_match($ignore, $current->getFilename())) {
						return false;
					}
				}
			}

			return true;
			});

		$iterator = new \RecursiveIteratorIterator($filter);
		$files = array();
		foreach ($iterator as $info) {
			if ($info->isDir()) {
				continue;
			}

			$inPath = $info->getPathname();
			$outPath = $outputDirectory . '/' . substr($inPath, strpos($inPath, '/') + 1);
			echo "in=$inPath out=$outPath\n";
			$input = file_get_contents($inPath);
			$output = '';

			if (preg_match("/^(.*)\.php$/", $outPath, $fileParts)) {
				$outPath = $fileParts[1] . '.cpp';

				$stmts = $this->translationForCode($input);
				$output = $this->_prettyPrinter->prettyPrint($stmts);
			} else {
				$output = $input;
			}

			@mkdir(dirname($outPath), 0755, true); // $path is a file

			file_put_contents($outPath, $output);
		}
	}

	function translationForCode($code) {
		$stmts = null;

		try {
		    $stmts = $this->_parser->parse($code);
		    // $stmts is an array of statement nodes
		} catch (Error $e) {
		    echo 'Parse Error: ', $e->getMessage();
		}

		return $stmts;
	}

	/**** Setters/getters ****/

	function setIgnores($ignores) {
		$this->_ignores = $ignores;
	}

	function getIgnores() {
		return $this->_ignores;
	}
}

?>