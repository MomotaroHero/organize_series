<?php

namespace BusinessRule;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

use function tad\WPBrowser\vendorDir;

class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @return array|\Symfony\Component\ExpressionLanguage\ExpressionFunction[]
     */
    public function getFunctions()
    {
        return [
            ExpressionFunction::fromPhp('is_string', 'isString'),
            new ExpressionFunction(
                'stringContainsString',
                function ($expression) {
                    return sprintf('stringContainsString(%s)', implode(', ', \func_get_args()));
                },
                function ($arguments, $string) {
                    reset($arguments);
                    $haystack = current($arguments);

                    next($arguments);
                    $needle = current($arguments);

                    $position = strpos($haystack, $needle);

                    return false !== $position;
                }
            )
        ];
    }
}