<?php

namespace App\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class GameExpressionLanguage extends ExpressionLanguage
{
    private static ?ExpressionLanguage $instance = null;

    public static function getInstance(): ExpressionLanguage
    {
        if (self::$instance === null) {
            self::$instance = new ExpressionLanguage(null, [
                new MathExpressionLanguageProvider(),
            ]);
        }

        return self::$instance;
    }
}
