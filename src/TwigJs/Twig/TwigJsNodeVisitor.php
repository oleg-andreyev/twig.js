<?php

namespace TwigJs\Twig;

use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\AbstractNodeVisitor;
use Twig\NodeVisitor\NodeVisitorInterface;

class TwigJsNodeVisitor extends AbstractNodeVisitor
{
    private $moduleNode;

    public function doEnterNode(Node $node, Environment $env)
    {
        if ($node instanceof ModuleNode) {
            return $this->moduleNode = $node;
        }

        return $node;
    }

    public function doLeaveNode(Node $node, Environment $env)
    {
        if ($node instanceof TwigJsNode) {
            if ($node->hasAttribute('name')) {
                $this->moduleNode->setAttribute(
                    'twig_js_name',
                    $node->getAttribute('name')
                );
            }

            return null;
        }

        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
