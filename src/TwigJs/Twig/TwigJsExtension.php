<?php

namespace TwigJs\Twig;

use Twig\Extension\AbstractExtension;

class TwigJsExtension extends AbstractExtension
{
    public function getTokenParsers(): array
    {
        return array(new TwigJsTokenParser());
    }

    public function getNodeVisitors(): array
    {
        return array(new TwigJsNodeVisitor());
    }

    public function getName(): string
    {
        return 'twig_js';
    }
}
