<?php

namespace Digima\Modules;

use Digima\AuthManager;

class AbstractModule
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @return AuthManager
     */
    public function getAuthManager()
    {
        return $this->authManager;
    }
}
