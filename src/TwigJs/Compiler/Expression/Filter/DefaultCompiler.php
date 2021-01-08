<?php

namespace TwigJs\Compiler\Expression\Filter;

use Twig\Node\Expression\Filter\DefaultFilter;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class DefaultCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return DefaultFilter::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof DefaultFilter) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    DefaultFilter::class,
                    get_class($node)
                )
            );
        }

        $compiler->subcompile($node->getNode('node'));
    }
}
