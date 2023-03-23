<?php

namespace Digima;

use Digima\Modules\Form\Form;
use Exception;

/**
 * Digima PHP Client.
 *
 * @copyright (c) 2019, COMVEX Co., Ltd.
 */
class Client
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @param string $accountCode
     * @return void
     */
    public function __construct($accountCode)
    {
        // If we cannot resolve the AuthManager,
        // we assume the library is not being used as a Composer package but standalone.
        // In this case, we need to manually require the autoloader.
        if (! class_exists('Digima\\AuthManager')) {
            $this->requireAutoload();
        }

        $this->authManager = new AuthManager($accountCode);
    }

    /**
     * @param string $formCode
     * @return Form
     */
    public function makeForm($formCode)
    {
        return new Form($this->authManager, $formCode);
    }

    /**
     * @return bool
     */
    private function composerAutoload()
    {
        $autoloadFile = __DIR__.'/../../../../vendor/autoload.php';

        if (! file_exists($autoloadFile)) {
            return false;
        }

        if (! is_readable($autoloadFile)) {
            return false;
        }

        require_once $autoloadFile;

        return true;
    }

    /**
     * @return void
     */
    private function legacyAutoload()
    {
        include __DIR__.'/AutoLoader.php';

        $autoLoader = new AutoLoader(__DIR__.'/../');

        $autoLoader->load();
    }

    /**
     * @return void
     */
    private function requireAutoload()
    {
        // If we cannot autoload using Composer, fallback to the legacy method
        if (! $this->composerAutoload()) {
            $this->legacyAutoload();
        }
    }
}
