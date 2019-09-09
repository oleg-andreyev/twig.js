<?php declare(strict_types = 1);
/*
* This file is part of the JMSTwigJsBundle software.
*
* (c) 2019, ecentria group, inc
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/


namespace TwigJs\Tests;

if (\class_exists('\PHPUnit\Framework\TestCase')) {
    class TestCase extends \PHPUnit\Framework\TestCase {}
} else {
    class TestCase extends \PHPUnit_Framework_TestCase {}
}