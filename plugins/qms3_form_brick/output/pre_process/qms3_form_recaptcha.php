<?php

use QMS3\Brick\PreProcess\RecaptchaVerifier;


/**
 * @param     string    $sitekey
 * @param     string    $secret
 * @return    RecaptchaVerifier
 */
function qms3_form_recaptcha($sitekey, $secret)
{
    return new RecaptchaVerifier($sitekey, $secret);
}
