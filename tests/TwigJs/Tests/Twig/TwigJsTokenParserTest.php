<?php

namespace TwigJs\Tests\Twig;

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Source;
use TwigJs\Tests\TestCase;
use TwigJs\Twig\TwigJsTokenParser;
use TwigJs\Twig\TwigJsNode;

class TwigJsTokenParserTest extends TestCase
{
    public function testParse()
    {
        $env = $this->getEnv();
        $stream = $env->tokenize(new Source('{% twig_js name="foo" %}', 'bar'));
        $token = $env->parse($stream)->getNode('body')->getNode(0);

        $this->assertInstanceOf(TwigJsNode::class, $token);
        $this->assertEquals('foo', $token->getAttribute('name'));
    }

    private function getEnv()
    {
        $env = new Environment(new ArrayLoader([]));
        $env->addTokenParser(new TwigJsTokenParser());

        return $env;
    }
}
