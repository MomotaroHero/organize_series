<?php

namespace BusinessRule;

use Codeception\DomainRule;

class Rule extends DomainRule
{
    public function __construct($rule, array $values = array())
    {
        parent::__construct($rule, $values);

        $this->language->registerProvider(new StringExpressionLanguageProvider());
    }
}