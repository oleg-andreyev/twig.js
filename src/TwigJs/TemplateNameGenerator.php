<?php

namespace TwigJs;

class TemplateNameGenerator
{
    /**
     * Generates template name from template path
     *
     * @param string $templatePath
     *
     * @return string
     */
    public static function generate($templatePath)
    {
        $templateName = basename($templatePath);
        $templateName = str_replace(':', '.', $templateName);
        $templateName = preg_replace('/\.+/', '.', $templateName);
        $templateName = trim($templateName, '.');

        return $templateName;
    }

}
