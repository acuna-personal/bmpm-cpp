<?php

require_once "vendor/autoload.php";

use PhpParser\PrettyPrinter;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinterAbstract;

class PrettyPrinterCpp extends PrettyPrinter\Standard {
	protected function pExpr_Variable(Expr\Variable $node) {
		if ($node->name instanceof Expr) {
		    return 's_{' . $this->p($node->name) . '}';
		} else {
			if ($node->name == '$this') {
				return 'this';
			} else {
			    return $node->name; // without $
			}
		}
	}
}

?>