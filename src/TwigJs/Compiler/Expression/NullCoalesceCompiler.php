<?php

declare(strict_types=1);

namespace TwigJs\Compiler\Expression;

use Twig\Node\Expression\NullCoalesceExpression;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class NullCoalesceCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return NullCoalesceExpression::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
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
