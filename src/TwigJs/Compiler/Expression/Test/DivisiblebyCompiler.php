<?php

namespace TwigJs\Compiler\Expression\Test;

use Twig\Node\Expression\Test\DivisiblebyTest;
use Twig\Node\Expression\TestExpression;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class DivisiblebyCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return DivisiblebyTest::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof DivisiblebyTest) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    DivisiblebyTest::class,
                    get_class($node)
                )
            );
        }

        $compiler->subcompile(
            new TestExpression(
                $node->getNode('node'),
                $node->getAttribute('name'),
                $node->getNode('arguments'),
                $node->getTemplateLine()
            )
        );
    }
}
