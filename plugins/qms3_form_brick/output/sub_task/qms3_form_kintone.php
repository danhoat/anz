<?php

use QMS3\Brick\Config\Util as U;
use QMS3\Brick\SubTask\Kintone;

/**
 * @since    1.5.0
 *
 * @param     string                 $domain
 * @param     string                 $api_token
 * @param     int                    $app_id
 * @param     array<string,mixed>    $options
 * @return    Kintone
 */
function qms3_form_kintone($domain, $api_token, $app_id, array $options = [])
{
    $normalized_options = [];
    foreach ($options as $label => $config) {
        $normalized_options[$label] = U::wrap($config);
    }

    return new Kintone($domain, $api_token, $app_id, $normalized_options);
}
