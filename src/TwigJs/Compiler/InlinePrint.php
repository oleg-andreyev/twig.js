<?php

namespace TwigJs\Compiler;

use TwigJs\JsCompiler;

class InlinePrint implements \TwigJs\TypeCompilerInterface
{
    public function getType() {
        return 'Twig\Node\Expression\InlinePrint';
    }

    public function compile(JsCompiler $compiler, \Twig_NodeInterface $node)
    {
        $compiler
            ->raw('document.write(')
            ->subcompile($node->getNode('node'))
            ->raw(');')
        ;
    }
}