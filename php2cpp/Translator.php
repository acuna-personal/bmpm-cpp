<?php

require 'vendor/autoload.php';

use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class Translator {
	private $_ignores = null;
	private $_parser = null;
	private $_prettyPrinter = null;
	private $_includes = '';

	function __construct($parser, $prettyPrinter) {
		$this->_parser = $parser;
		$this->_prettyPrinter = $prettyPrinter;
		$this->_includes = ''
			. "#include <iostream>\n"
			. "#include <string>\n"
			. "#include <cstdio>";
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

			if (preg_match("/^(.*)\.php$/", $outPath, $fileParts)) {
				$outPathH = $fileParts[1] . '.h';
				$outPathCpp = $fileParts[1] . '.cpp';

				$input = $this->preProcess($input, $fileParts[1]); // we don't know if it's the h or cpp yet
				$stmts = $this->stmtsForCode($input);

				// .h
				$this->_prettyPrinter->headersOnly = true;
				$outputH = $this->_prettyPrinter->prettyPrint($stmts);
				$outputH = $this->postProcess($outputH, $outPathH);
				$this->_writeFile($outputH, $outPathH);				

				// .cpp
				$this->_prettyPrinter->headersOnly = false;
				$outputCpp = $this->_prettyPrinter->prettyPrint($stmts);
				$outputCpp = '#include "' . basename($outPathH) . "\"\n\n"
					. $outputCpp;
				$outputCpp = $this->postProcess($outputCpp, $outPathCpp);
				$this->_writeFile($outputCpp, $outPathCpp);
			} else {
				$output = $input;
				$this->_writeFile($output, $outPath);
			}
		}

		$definedFunctions = $this->_prettyPrinter->definedFunctions;
		$calledFunctions = $this->_prettyPrinter->calledFunctions;
		$undefinedFunctions = array();
		foreach ($calledFunctions as $func) {
			$idx = array_search($func, $definedFunctions);
			if ($idx === false) {
				array_push($undefinedFunctions, $func);
			}
		}

		echo "defined:\n";
		print_r($undefinedFunctions);		
		echo "called:\n";
		print_r($calledFunctions);
		echo "undefined:\n";
		print_r($undefinedFunctions);
	}

	protected function preProcess($php, $outPath) {
		$php = preg_replace('~/\*\*\*IFNCPP\*\*\*/(.+?)/\*\*\*ENDIFNCPP\*\*\*/~sm', '', $php);
		if (basename($outPath) == "tests-run.php") {
			echo "$php\n";
		}
		return $php;
	}

	protected function postProcess($cpp, $outPath) {
		// Remove ; after #includes
		$cpp = preg_replace('/(#include "(.*)");/', '$1', $cpp);

		// Remove ; on empty line from omitted statements
		$cpp = preg_replace('/^\s*;\n/m', '', $cpp);

		// Add basic includes that are dependencies for any translated code
		$const = $this->constFromFilePath($outPath);
		$top = $this->_includes . "\n\n"
			. "#ifndef $const\n"
			. "#define $const\n"
			. "#else\n\n";
		$bottom = "\n\n#endif";

		return $top . $cpp . $bottom;
	}

	protected function constFromFilePath($path) {
		return strtoupper(preg_replace('/[^a-zA-Z]+/', '_', basename($path)));
	}

	protected function _writeFile($output, $outPath) {
		@mkdir(dirname($outPath), 0755, true); // $path is a file
		return file_put_contents($outPath, $output);
	}

	function stmtsForCode($code) {
		$stmts = null;

		try {
		    $stmts = $this->_parser->parse($code);

		    $traverser = new NodeTraverser;
		    $traverser->addVisitor(new ParentConnector);

		    $stmts = $traverser->traverse($stmts);

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


/*** https://github.com/nikic/PHP-Parser/blob/master/doc/5_FAQ.markdown#how-can-the-parent-of-a-node-be-obtained ***/


class ParentConnector extends NodeVisitorAbstract {
    private $stack;
    public function beforeTraverse(array $nodes) {
        $this->stack = [];
    }
    public function enterNode(Node $node) {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack)-1]);
        }
        $this->stack[] = $node;
    }
    public function leaveNode(Node $node) {
        array_pop($this->stack);
    }
}

?>