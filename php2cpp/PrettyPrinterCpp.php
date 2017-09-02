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
		    return '${' . $this->p($node->name) . '}'; // TODO
		} else {
			if ($node->name == 'this') {
				return 'this';
			} else {
			    return $node->name; // without $
			}
		}
	}

	// Class ivars
	protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node) {
	    return $node->name // without $
	         . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
	}

	protected function pClassCommon(Stmt\Class_ $node, $afterClassToken) {
		$flags = $this->pModifiers($node->flags);
	    return ($flags ? '/* ORIG: ' . $flags . ' */ ' : '') // not supported for C++ classes
	    . 'class' . $afterClassToken
	    . (null !== $node->extends ? ' : ' . $this->p($node->extends) : '')
	    . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
	    . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '};';
	}

	protected function p(Node $node) {
		$s = parent::p($node);
/*
		// For debugging
		if ($s == "\$languageNamesASDF") {
			print_r($node);
		}
*/
		return $s;
	}

	protected function pParam(Node\Param $node) {
	    return ($node->type ? $this->pType($node->type) . ' ' : '/* TODO: Add type */')
	         . ($node->byRef ? '&' : '')
	         . ($node->variadic ? '...' : '')
	         . $node->name
	         . ($node->default ? ' = ' . $this->p($node->default) : '');
	}

	protected function pExpr_ConstFetch(Expr\ConstFetch $node) {
		$str = $this->p($node->name);
		$strLower = strtolower($str);
		if ($strLower == "true" || $strLower == "false") {
			return $strLower;
		} else if ($strLower == "null") {
			return "NULL";
		} else {
		    return $str;
		}
	}

	protected function pExpr_MethodCall(Expr\MethodCall $node) {
		$lhs = $this->pDereferenceLhs($node->var);
		$operator = '.';
		if ($lhs == 'this') {
			$operator = '->'; // this is a pointer in C++
		}
	    return $lhs . $operator . $this->pObjectProperty($node->name)
	         . '(' . $this->pMaybeMultiline($node->args) . ')';
	}

	protected function pExpr_Array(Expr\Array_ $node) {
	    return '{' . $this->pMaybeMultiline($node->items, true) . '}';
	}

	protected function pStmt_Echo(Stmt\Echo_ $node) {
	    return 'std::cout << ' . $this->pStreamSeparated($node->exprs) . ';';
	}

	protected function pStreamSeparated(array $nodes) {
	    return $this->pImplode($nodes, ' << ');
	}

	protected function pExpr_BinaryOp_Concat(BinaryOp\Concat $node) {
	    return $this->pInfixOp('Expr_BinaryOp_Concat', $node->left, ' + ', $node->right);
	}

	protected function pExpr_AssignOp_Concat(AssignOp\Concat $node) {
	    return $this->pInfixOp('Expr_AssignOp_Concat', $node->var, ' += ', $node->expr);
	}

	protected function pExpr_StaticPropertyFetch(Expr\StaticPropertyFetch $node) {
	    return $this->pDereferenceLhs($node->class) . '::' . $this->pObjectProperty($node->name);
	}

	protected function pExpr_ErrorSuppress(Expr\ErrorSuppress $node) {
	    return $this->pPrefixOp('Expr_ErrorSuppress', '/* ORIG: @ */', $node->expr);
	}

	protected function pStmt_ClassMethod(Stmt\ClassMethod $node) {
		$tag = '';
		if (null === $node->returnType) {
			$tag = "/* TODO: Fix return type */\n";
		}
	    return $tag
	         . $this->pModifiers($node->flags)
	         . (null !== $node->returnType ? $this->p($node->returnType) : 'void ') // user will fix manually
 	         . ($node->byRef ? '&' : '') . $node->name
	         . '(' . $this->pCommaSeparated($node->params) . ');'
	         . (null !== $node->stmts
	            ? "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}'
	            : ';');
	}

	protected function pStmt_Function(Stmt\Function_ $node) {
		$tag = '';
		if (null === $node->returnType) {
			$tag = "/* TODO: Fix return type */\n";
		}
	    return $tag
		     . (null !== $node->returnType ? $this->p($node->returnType) : 'void ') // user will fix manually
	    	 . ($node->byRef ? '&' : '') . $node->name
	         . '(' . $this->pCommaSeparated($node->params) . ');'
	         . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}';
	}

	protected function pExpr_Include(Expr\Include_ $node) {
	    static $map = [
	        Expr\Include_::TYPE_INCLUDE      => 'include',
	        Expr\Include_::TYPE_INCLUDE_ONCE => 'include_once',
	        Expr\Include_::TYPE_REQUIRE      => 'require',
	        Expr\Include_::TYPE_REQUIRE_ONCE => 'require_once',
	    ];

	    $str = '';
	    if ($node->type == Expr\Include_::TYPE_INCLUDE_ONCE
	    	|| $node->type == Expr\Include_::TYPE_REQUIRE_ONCE) {
	    	$str .= '/* ORIG: ' . $map[$node->type] . " */\n";
	    }
	    $str .= "#include " . $this->pConvertExtension($this->p($node->expr), 'cpp');
	    return $str;
	}

	protected function pExpr_BinaryOp_Identical(BinaryOp\Identical $node) {
	    return $this->pInfixOp('Expr_BinaryOp_Identical', $node->left, ' == /* ORIG: === */ ', $node->right);
	}

	protected function pExpr_BinaryOp_NotIdentical(BinaryOp\NotIdentical $node) {
	    return $this->pInfixOp('Expr_BinaryOp_NotIdentical', $node->left, ' != /* ORIG: !== */ ', $node->right);
	}

	protected function pExpr_BinaryOp_Pow(BinaryOp\Pow $node) {
	    return 'pow(' . $this->pInfixOp('Expr_BinaryOp_Pow', $node->left, ', ', $node->right) . ') /* TODO: #include <math.h> */ ';
	}

	protected function pExpr_New(Expr\New_ $node) {
	    if ($node->class instanceof Stmt\Class_) {
	        $args = $node->args ? '(' . $this->pMaybeMultiline($node->args) . ')' : '';
	        return '/* TODO: Mem mgmt */ new ' . $this->pClassCommon($node->class, $args);
	    } else {
		    return '/* TODO: Mem mgmt */ new ' . $this->p($node->class) . '(' . $this->pMaybeMultiline($node->args) . ')';
		}
	}

	protected function pStmt_ClassConst(Stmt\ClassConst $node) {
	    return $this->pModifiers($node->flags)
	         . 'static const ' . $this->pCommaSeparated($node->consts) . ';';
	}


	/**** Helpers ****/

	protected function pConvertExtension($pathInQuotes, $ext) {
		return preg_replace("/\.php\"$/", ".$ext\"", $pathInQuotes);
	}

	// TODO: Copy and paste of private methods so we have access

	protected function hasNodeWithComments(array $nodes) {
	    foreach ($nodes as $node) {
	        if ($node && $node->getAttribute('comments')) {
	            return true;
	        }
	    }
	    return false;
	}

	protected function pMaybeMultiline(array $nodes, $trailingComma = false) {
	    if (!$this->hasNodeWithComments($nodes)) {
	        return $this->pCommaSeparated($nodes);
	    } else {
	        return $this->pCommaSeparatedMultiline($nodes, $trailingComma) . "\n";
	    }
	}
}

?>