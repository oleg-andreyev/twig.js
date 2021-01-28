<?php

declare(strict_types=1);

namespace TwigJs\Compiler\Expression;

use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class NullCoalesceExpression implements TypeCompilerInterface
{

    public function getType()
    {
        return 'Twig\Node\Expression\NullCoalesceExpression';
    }

    public function compile(JsCompiler $compiler, \Twig_NodeInterface $node)
    {
        if (!$node instanceof \Twig_Node_Expression_Conditional) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of \Expression_Conditional, but got "%s".',
                    get_class($node)
                )
            );
        }

        $compiler
            ->raw('((')
            ->subcompile($node->getNode('expr2'))
            ->raw(') ?? (')
            ->subcompile($node->getNode('expr3'))
            ->raw('))')
        ;
    }
}
