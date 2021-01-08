<?php

namespace TwigJs\Compiler;

use Twig\Node\Node;
use TwigJs\JsCompiler;
use Twig\Node\Expression\InlinePrint;
use TwigJs\TypeCompilerInterface;

class InlinePrintCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return InlinePrint::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof InlinePrint) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    InlinePrint::class,
                    get_class($node)
                )
            );
        }

        $compiler
            ->addDebugInfo($node)
            ->write('sb.append(')
            ->subcompile($node->getNode('node'))
            ->raw(')')
        ;
    }
}