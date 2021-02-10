<?php

namespace TwigJs\Compiler;

use Twig\Node\FlushNode;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class FlushCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return FlushNode::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof FlushNode) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    FlushNode::class,
                    get_class($node)
                )
            );
        }

        throw new \LogicException('Flushing is not supported in Javascript templates.');
    }
}
