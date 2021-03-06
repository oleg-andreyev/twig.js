<?php

namespace TwigJs\Compiler;

use Twig\Node\DoNode;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class DoCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return DoNode::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof DoNode) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    DoNode::class,
                    get_class($node)
                )
            );
        }

        $compiler
            ->addDebugInfo($node)
            ->write('')
            ->subcompile($node->getNode('expr'))
            ->raw(";\n")
        ;
    }
}
