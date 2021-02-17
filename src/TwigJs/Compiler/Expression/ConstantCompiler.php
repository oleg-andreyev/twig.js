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

namespace TwigJs\Compiler\Expression;

use Twig\Error\LoaderError;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use TwigJs\JsCompiler;
use TwigJs\TemplateNameGenerator;
use TwigJs\TypeCompilerInterface;

class ConstantCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return ConstantExpression::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof ConstantExpression) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    ConstantExpression::class,
                    get_class($node)
                )
            );
        }

        if ($compiler->isTemplateName || preg_match('/\.twig$/', (string) $node->getAttribute('value'))) {
            $compiler->repr($this->getTemplateNameFromTemplateConstant($compiler, $node));

            return;
        }

        $compiler->repr($node->getAttribute('value'));
    }

    private function getTemplateNameFromTemplateConstant(JsCompiler $compiler, Node $node)
    {
        if (!$compiler->shouldResolveConstantTemplates()) {
            return TemplateNameGenerator::generate($node->getAttribute('value'));
        }

        try {
            $env = $compiler->getEnvironment();
            $source = $env->getLoader()->getSourceContext($node->getAttribute('value'));
            $module = $env->parse($env->tokenize($source, $node->getAttribute('value')));

            return $module->hasAttribute('twig_js_name') ? $module->getAttribute('twig_js_name') : TemplateNameGenerator::generate($node->getAttribute('value'));
        } catch (LoaderError $e) {
            return TemplateNameGenerator::generate($node->getAttribute('value'));
        }
    }
}
