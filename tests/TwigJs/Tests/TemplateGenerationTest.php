<?php

namespace TwigJs\Tests;

use TwigJs\Twig\TwigJsExtension;
use TwigJs\JsCompiler;

class TemplateGenerationTest extends \TwigJs\Tests\TestCase
{
    /**
     * @dataProvider getGenerationTests
     */
    public function testGenerate($inputFile, $outputFile)
    {
        $env = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__.'/Fixture/templates'));
        $env->addExtension(new TwigJsExtension());
        $env->setCompiler(new JsCompiler($env));

        $source = file_get_contents($inputFile);

        $expected = file_get_contents($outputFile);
        $actual = $env->compileSource($source, $inputFile);

        $expected = \str_replace("\r\n", "\n", $expected);

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    public function getGenerationTests()
    {
        $tests = array();
        $files = new \RecursiveDirectoryIterator(
            __DIR__ . '/Fixture/templates',
            \RecursiveDirectoryIterator::SKIP_DOTS
        );
        foreach ($files as $file) {
            /** @var $file \SplFileInfo */
            if (!$file->isFile()) {
                continue;
            }

            $tests[] = array(
                $file->getRealPath(),
                __DIR__.'/Fixture/generated/'.basename($file, '.twig').'.js',
            );
        }

        return $tests;
    }
}
