<?php

namespace Digima;

class AuthManager
{
    /**
     * @var string
     */
    public $accountCode;

    /**
     * @param string $accountCode
     * @return void
     */
    public function __construct($accountCode = null)
    {
        $this->accountCode = $accountCode;
    }
}
