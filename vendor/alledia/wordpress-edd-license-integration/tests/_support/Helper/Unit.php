<?php

namespace Helper;

use BusinessRule\ErrorHandling;
use BusinessRule\StringExpressionLanguageProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

require_once 'tests/_support/BusinessRule/ErrorHandling.php';

class Unit extends \Codeception\Module
{
    use ErrorHandling;
}
