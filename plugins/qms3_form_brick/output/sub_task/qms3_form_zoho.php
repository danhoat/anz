<?php

use QMS3\Brick\SubTask\Zoho;
use QMS3\Brick\Config\Util as U;


/**
 * @since    1.5.2
 *
 * @param     string                 $client_id
 * @param     string                 $client_secret
 * @param     string                 $reflesh_token
 * @param     array<string,mixed>    $options
 * @return    Zoho
 */
function qms3_form_zoho(
    $client_id,
    $client_secret,
    $reflesh_token,
    array $options
)
{
    $normalized_options = [];
    foreach ($options as $label => $config) {
        $normalized_options[$label] = U::wrap($config);
    }

    return new Zoho(
        $client_id,
        $client_secret,
        $reflesh_token,
        $normalized_options
    );
}
