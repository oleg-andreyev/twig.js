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

use Twig\Node\Node;
use Twig\Node\SandboxNode;
use TwigJs\JsCompiler;
use TwigJs\TypeCompilerInterface;

class SandboxCompiler implements TypeCompilerInterface
{
    public function getType()
    {
        return SandboxNode::class;
    }

    public function compile(JsCompiler $compiler, Node $node)
    {
        if (!$node instanceof SandboxNode) {
            throw new \RuntimeException(
                sprintf(
                    '$node must be an instanceof of %s, but got "%s".',
                    SandboxNode::class,
                    get_class($node)
                )
            );
        }

        throw new \LogicException('Sandbox is not supported in Javascript templates.');

//         $compiler
//             ->addDebugInfo($this)
//             ->write("\$sandbox = \$this->env->getExtension('sandbox');\n")
//             ->write("if (!\$alreadySandboxed = \$sandbox->isSandboxed()) {\n")
//             ->indent()
//             ->write("\$sandbox->enableSandbox();\n")
//             ->outdent()
//             ->write("}\n")
//             ->subcompile($this->getNode('body'))
//             ->write("if (!\$alreadySandboxed) {\n")
//             ->indent()
//             ->write("\$sandbox->disableSandbox();\n")
//             ->outdent()
//             ->write("}\n")
//         ;
    }
}
