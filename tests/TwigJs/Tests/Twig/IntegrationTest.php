<?php

namespace TwigJs\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Source;
use TwigJs\Twig\TwigJsExtension;

class IntegrationTest extends TestCase
{
    public function testNameIsSetOnModule()
    {
        $env = $this->getEnv();
        $module = $env->parse($env->tokenize(new Source('{% twig_js name="foo" %}', 'bar')));

        $this->assertTrue($module->hasAttribute('twig_js_name'));
        $this->assertEquals('foo', $module->getAttribute('twig_js_name'));
        $this->assertCount(0, $module->getNode('body'));
    }

    private function getEnv()
    {
        $env = new Environment(new ArrayLoader([]));
        $env->addExtension(new TwigJsExtension());

        return $env;
    }
}
