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
	public $headersOnly = false;
	public $definedFunctions = array();
	public $calledFunctions = array();

	protected function pExpr_Variable(Expr\Variable $node) {
		$str = '';
		if ($node->name instanceof Expr) {
		    $str .= '${' . $this->p($node->name) . '}'; // TODO
		} else {
			if ($node->name == 'this') {
				$str .= 'this';
			} else {
				$str .= $node->name; // without $
			}
		}
		return $str;
	}
/*
	protected function containsName(&$node, $name, &$originalNode) {
		if (isset($node->stmts)) {
			foreach ($node->stmts as $n) {
				if ($n != $originalNode && isset($node->name) && $node->name == $name && $n->name == $name) {
					return true;
				} else if (isset($n->stmts) && $this->containsName($n, $name, $originalNode)) {
					return true;
				}
			}
		} else if (isset($node->name) && $node->name == $name) {
			return true;
		}
		return false;
	}
*/

	protected function pClassCommon(Stmt\Class_ $node, $afterClassToken) {
		$str = '';
		if ($this->headersOnly) {
		    $flags = $this->pModifiers($node->flags);
		    $str .= ($flags ? '/* ORIG: ' . $flags . ' */ ' : '') // not supported for C++ classes
		    . 'class' . $afterClassToken
		    . (null !== $node->extends ? ' : ' . $this->p($node->extends) : '')
		    . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
		    . "\n" . '{';
		}

    	$str .= $this->pStmts($node->stmts);

    	if ($this->headersOnly) {
		    $str .= "\n" . '};';
		}

	    return $str;
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

	protected function pParam(Node\Param $node) {
	    return $this->inferCPPType($node->default, $node->type) . ' '
	         . ($node->byRef ? '&' : '')
	         . ($node->variadic ? '...' : '')
	         . $node->name
	         . ($this->headersOnly && $node->default ? ' = ' . $this->p($node->default) : '');
	}

	protected function pStmt_ClassMethod(Stmt\ClassMethod $node) {
		$tag = '';
		$name = $node->name;
		$parent = $node->getAttribute('parent');
		if ($parent) {
			if ($name == '__construct') {
				$name = $parent->name;
			}

			if (!$this->headersOnly) {
				$name = $parent->name . '::' . $name;
			}
		}
	    return $tag
	         . $this->pModifiers($node->flags)
	         . $this->typeForFuncoid($node)
 	         . ($node->byRef ? '&' : '') . $name
	         . '(' . $this->pCommaSeparated($node->params) . ')'
	         . (null !== $node->stmts && !$this->headersOnly
	            ? "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}'
	            : ';');
	}

	protected function pModifiers($modifiers) {
		if ($this->headersOnly) {
		    return ($modifiers & Stmt\Class_::MODIFIER_PUBLIC    ? 'public: '    : '')
		         . ($modifiers & Stmt\Class_::MODIFIER_PROTECTED ? 'protected: ' : '')
		         . ($modifiers & Stmt\Class_::MODIFIER_PRIVATE   ? 'private: '   : '')
		         . ($modifiers & Stmt\Class_::MODIFIER_STATIC    ? 'static '    : '')
		         . ($modifiers & Stmt\Class_::MODIFIER_ABSTRACT  ? 'abstract '  : '')
		         . ($modifiers & Stmt\Class_::MODIFIER_FINAL     ? 'final '     : '');
		} else {
			return '';
		}
	}

	protected function pStmt_Function(Stmt\Function_ $node) {
		if ($node->name && !in_array('' . $node->name, $this->definedFunctions)) {
			array_push($this->definedFunctions, '' . $node->name);
		}

		$str = '';

		if ($node->name == 'main') {
			$str .= "int main(int argc, const char * argv[]);";
		} else {
			$str .= $this->typeForFuncoid($node)
				. ($node->byRef ? '&' : '') . $node->name
				. '(' . $this->pCommaSeparated($node->params) . ')';
		}

		if (!$this->headersOnly) {
			$str .= "\n" . '{' . $this->pStmts($node->stmts) . "\n" . "}\n";
		} else {
			$str .= ';';
		}

	    return $str;
	}

	protected function pExpr_FuncCall(Expr\FuncCall $node) {
		if ($node->name && !in_array('' . $node->name, $this->calledFunctions)) {
			array_push($this->calledFunctions, '' . $node->name);
		}
	    return parent::pExpr_FuncCall($node);
	}

	protected function typeForFuncoid($node) {
		if ($node->name == '__construct') {
			return ''; // C++ constructor type is already known
		}

		if (!$this->containsReturn($node->stmts)) {
			$type = 'void ';
		} else {
			$type = (null !== $node->returnType ? $this->p($node->returnType) : 'php_type ');
		}
		return $type;
	}

	protected function containsReturn($nodes) {
		foreach ($nodes as $node) {
		    if ($node instanceof Stmt\Return_ && null !== $node->expr) {
		        return true;
		    } else if (isset($node->stmts) && $this->containsReturn($node->stmts)) {
		    	return true;
		    }
		}
		return false;
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
	    $str .= "#include " . $this->pConvertExtension($this->p($node->expr), 'h');
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
		$str = '';
	    $str .= $this->pModifiers($node->flags);
	    if ($this->headersOnly) {
		    $str .= 'public: static ';
	    }

	    $str .= 'const ';
	    $str .= $this->pCommaSeparated($node->consts) . ';';

	    return $str;
	}

// RESUME: This needs to put the default value in the implementation file - for statics only
	protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node) {
		$str = '';
		if ($this->headersOnly) {
		    $str .= $this->inferCPPType($node->default) . ' ';
			$str .= $node->name;
			$str .= (null !== $node->default ? ' = ' . $this->p($node->default) : '');
	     }
	     return $str;
	}

	protected function pConst(Node\Const_ $node) {
		$str = '';

		$str .= $this->inferCPPType($node->value) . ' ';
		$str .= $node->name;
		if (!$this->headersOnly) {
			$str .= ' = ';
			$str .= $this->p($node->value);
		}
	    return $str;
	}

	// currently unused?
	protected function pStmt_StaticVar(Stmt\StaticVar $node) {
	    $str = '';
	    $str .= $this->inferCPPType($node->default) . ' ';
	    $str .= $node->name;
	    if (!$this->headersOnly && null !== $node->default) {
	    	$str .= ' = ' . $this->p($node->default);
	    }
	    return $str;
	}

	protected function inferCPPType($value, $givenType = null) {
		if ($givenType) {
			$str = $givenType;
		} else {
			if ($value instanceof Scalar\String_) {
				$str = 'std::string';
			} else if (isset($value->name) && $this->isBoolean($value->name)) {
				$str = "bool";
			} else {
				$str = "php_type";
			}
		}
		return $str;
	}

	protected function isBoolean($name) {
		return strtolower($name) == "true" || strtolower($name) == "false";
	}

	protected function pScalar_String(Scalar\String_ $node) {
		// Single quotes is just for chars in C++
		$kind = $node->getAttribute('kind', Scalar\String_::KIND_SINGLE_QUOTED);
		if ($kind == Scalar\String_::KIND_SINGLE_QUOTED && strlen($node->value) != 1) {
            $node->setAttribute('kind', Scalar\String_::KIND_DOUBLE_QUOTED);
		}

		return parent::pScalar_String($node);
	}

	protected function pEncapsList(array $encapsList, $quote) {
	    $return = '';
	    foreach ($encapsList as $element) {
	        if ($element instanceof Scalar\EncapsedStringPart) {
	            $return .= $this->escapeString($element->value, $quote);
	        } else {

	            $return .= '" + ' . $this->p($element) . ' + "';
	        }
	    }

	    return $return;
	}

	protected function escapeString($string, $quote) {
		$string = parent::escapeString($string, $quote);
		$string = str_replace('\$', '$', $string); // don't escape $ in C++
		return $string;
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