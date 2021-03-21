<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace TwigJs\Compiler;

use Twig\Node\Expression\NameExpression;
use Twig\Node\ImportNode;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class ImportCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return ImportNode::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof ImportNode) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    ImportNode::class,
                    get_class($node)
                )
            );
        }

        $compiler
            ->addDebugInfo($node)
            ->write("")
            ->subcompile($node->getNode('var'))
            ->raw(' = ')
        ;

        if ($node->getNode('expr') instanceof NameExpression && '_self' === $node->getNode('expr')->getAttribute('name')) {
            $compiler->raw("this");
        } else {
            $compiler
                ->setTemplateName(true)
                ->raw('this.env_.createTemplate(')
                ->write('twig.templates[')
                ->subcompile($node->getNode('expr'))
                ->raw(']')
                ->raw(")")
                ->setTemplateName(false)
            ;
        }

        $compiler->raw(";\n");
    }
}
