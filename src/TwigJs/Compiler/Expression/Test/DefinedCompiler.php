<?php

namespace TwigJs\Compiler\Expression\Test;

use Twig\Node\Expression\Test\DefinedTest;
use Twig\Node\Expression\TestExpression;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class DefinedCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return DefinedTest::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof DefinedTest) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    DefinedTest::class,
                    get_class($node)
                )
            );
        }

        $compiler->subcompile(
            new TestExpression(
                $node->getNode('node'),
                $node->getAttribute('name'),
                $node->hasNode('arguments') ? $node->getNode('arguments') : null,
                $node->getTemplateLine()
            )
        );
    }
}
