<?php

use QMS3\Brick\Config\Util as U;
use QMS3\Brick\SubTask\Digima;


/**
 * @since    1.5.2
 *
 * @param     string                 $account_code
 * @param     string                 $form_code
 * @param     array<string,mixed>    $options
 * @return    Digima
 */
function qms3_form_digima(
    $account_code,
    $form_code,
    array $options
)
{
    $normalized_options = [];
    foreach ($options as $label => $config) {
        $normalized_options[$label] = U::wrap($config);
    }

    return new Digima(
        $account_code,
        $form_code,
        $normalized_options
    );
}
