<?php

declare(strict_types=1);

namespace App\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class MathExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            ExpressionFunction::fromPhp('pow'),
            ExpressionFunction::fromPhp('floor'),
            ExpressionFunction::fromPhp('ceil'),
        ];
    }
}
