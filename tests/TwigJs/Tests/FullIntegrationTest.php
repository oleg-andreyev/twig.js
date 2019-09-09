<?php

namespace TwigJs\Tests;

use DNode;
use Exception;
use React;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use TwigJs\Twig\TwigJsExtension;
use TwigJs\JsCompiler;
use Twig_Environment;
use Twig_Extension_Core;
use Twig_Loader_Array;
use Twig_Loader_Chain;
use Twig_Loader_Filesystem;

class FullIntegrationTest extends TestCase
{
    /** @var \React\EventLoop\StreamSelectLoop */
    private static $loop;
    /** @var DNode\DNode */
    private static $dnode;
    /** @var Twig_Loader_Array */
    private $arrayLoader;
    /** @var Twig_Environment */
    private $env;

    public static function setUpBeforeClass()
    {
        self::$dnode = new DNode\DNode(self::$loop = new React\EventLoop\StreamSelectLoop());
    }

    public static function tearDownAfterClass()
    {
        $exit = function ($remote, $connection) {
            $remote->exit(function () use ($connection) {
                //$connection->end();
            });
        };

        self::$dnode->on('error', function ($e) {
            // Do nothing.
            // This error means the dnode server isn't running, so it doesn't
            // matter that we can't connect to it in order to shut it down.
        });

        self::$dnode->connect(7070, $exit);
        self::$loop->run();
    }

    public function setUp()
    {
        $this->arrayLoader = new Twig_Loader_Array(array());
        $this->env = new Twig_Environment($this->arrayLoader);
        $this->env->addExtension(new TwigJsExtension());
        $this->env->setLoader(
            new Twig_Loader_Chain(
                array(
                    $this->arrayLoader,
                    new Twig_Loader_Filesystem(__DIR__.'/Fixture/integration')
                )
            )
        );
        $this->env->setCompiler(new JsCompiler($this->env));
    }

    /**
     * @test
     * @dataProvider getIntegrationTests
     */
    public function integrationTest($file, $message, $condition, $templates, $exception, $outputs)
    {
        foreach ($outputs as $match) {
            // data
            $templateParameters = $match[1];
            $javascript = '';
            foreach ($templates as $name => $twig) {
                $this->arrayLoader->setTemplate($name, $twig);
            }

            foreach ($templates as $name => $twig) {
                $javascript .= $this->compileTemplate($twig, $name);
            }

            $expectedOutput = trim($match[3], "\n ");
            try {
                $renderedOutput = $this->renderTemplate('index', $javascript, $templateParameters);
            } catch (Exception $e) {
                $this->markTestSkipped($e->getMessage());
            }

            $expectedOutput = \str_replace("\r\n", "\n", $expectedOutput);

            $this->assertEquals($expectedOutput, $renderedOutput);
        }
    }

    public function getIntegrationTests()
    {
        $tests = array();
        $directory = new RecursiveDirectoryIterator(__DIR__ . '/Fixture/integration');
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/\.test/', RecursiveRegexIterator::GET_MATCH);
        $test = $this;
        $tests = array_map(
            function ($file) use ($test) {
                return $test->loadTest($file);
            },
            array_keys(iterator_to_array($regex))
        );
        return $tests;
    }

    public function loadTest($file)
    {
        $test = file_get_contents($file);

        // @codingStandardsIgnoreStart
        if (preg_match('/--TEST--\s*(.*?)\s*(?:--CONDITION--\s*(.*))?\s*((?:--TEMPLATE(?:\(.*?\))?--(?:.*?))+)\s*(?:--DATA--\s*(.*))?\s*--EXCEPTION--\s*(.*)/sx', $test, $match)) {
            $message = $match[1];
            $condition = $match[2];
            $templates = $this->parseTemplates($match[3]);
            $exception = $match[5];
            $outputs = array(array(null, $match[4], null, ''));
        } elseif (preg_match('/--TEST--\s*(.*?)\s*(?:--CONDITION--\s*(.*))?\s*((?:--TEMPLATE(?:\(.*?\))?--(?:.*?))+)--DATA--.*?--EXPECT--.*/s', $test, $match)) {
            $message = $match[1];
            $condition = $match[2];
            $templates = $this->parseTemplates($match[3]);
            $exception = false;
            preg_match_all('/--DATA--(.*?)(?:--CONFIG--(.*?))?--EXPECT--(.*?)(?=\-\-DATA\-\-|$)/s', $test, $outputs, PREG_SET_ORDER);
        } else {
            throw new InvalidArgumentException(sprintf('Test "%s" is not valid.', $file));
        }
        // @codingStandardsIgnoreStart

        return array(
            $file,
            $message,
            $condition,
            $templates,
            $exception,
            $outputs
        );
    }

    protected static function parseTemplates($test)
    {
        $templates = array();
        preg_match_all('/--TEMPLATE(?:\((.*?)\))?--(.*?)(?=\-\-TEMPLATE|$)/s', $test, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $templates[($match[1] ? $match[1] : 'index.twig')] = $match[2];
        }

        return $templates;
    }

    private function compileTemplate($source, $name)
    {
        return $this->env->compileSource(new \Twig_Source($source, $name));
    }

    private function renderTemplate($name, $javascript, $parameters)
    {
        $output = '';
        self::$dnode->connect(7070, function ($remote, $connection) use ($name, $javascript, $parameters, &$output) {
            $remote->render($name, $javascript, $parameters, function ($rendered) use ($connection, &$output) {
                $output = trim($rendered, "\n ");
                $connection->end();
            });
        });
        self::$loop->run();

        return $output;
    }
}
