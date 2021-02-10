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

namespace TwigJs;

use Twig\Environment;

class CompileRequestHandler
{
    private $env;
    private $compiler;

    public function __construct(Environment $env, JsCompiler $compiler)
    {
        $this->env = $env;
        $this->compiler = $compiler;
    }

    public function process(CompileRequest $request)
    {
        $this->env->setCompiler($this->compiler);
        $this->compiler->setDefines($request->getDefines());

        if (!$source = $request->getSource()) {
            $source = $this->env->getLoader()->getSourceContext($request->getName());
        }

        return $this->env->compileSource($source, $request->getName());
    }
}
