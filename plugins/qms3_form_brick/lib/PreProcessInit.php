<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Param\Param;
use QMS3\Brick\PreProcess\PreProcessInterface as PreProcess;
use QMS3\Brick\PreProcess\SimplePreProcess;
use QMS3\Brick\PreProcess\RecaptchaVerifier;
use QMS3\Brick\PreProcessContainer\PreProcessContainer;


/**
 * @since    1.5.2
 */
class PreProcessInit
{
    /** @var    PreProcess[] */
    private $pre_processes;

    /** @var    Param */
    private $param;

    /**
     * @param    PreProcess[]    $pre_processes
     * @param    Param           $param
     */
    public function __construct(array $pre_processes, Param $param)
    {
        $this->pre_processes = $pre_processes;
        $this->param         = $param;
    }

    public function init()
    {
        $pre_processes = [];
        foreach ($this->pre_processes as $pre_process) {
            if ($pre_process instanceof PreProcess) {
                $pre_processes[] = $pre_process;
            } else if (is_callable($pre_process)) {
                $pre_processes[] = new SimplePreProcess($pre_process);
            }
        }

        $recaptcha_verifier = $this->recaptcha_verifier();
        if ($recaptcha_verifier) {
            $pre_processes[] = $recaptcha_verifier;
        }

        return new PreProcessContainer($pre_processes);
    }

    /**
     * @return    RecaptchaVerifier|null
     */
    private function recaptcha_verifier()
    {
        if (
            !$this->param->recaptcha_activate
            || !$this->param->recaptcha_sitekey
            || !$this->param->recaptcha_secret
        ) { return null; }

        return new RecaptchaVerifier(
            $this->param->recaptcha_sitekey,
            $this->param->recaptcha_secret
        );
    }
}
