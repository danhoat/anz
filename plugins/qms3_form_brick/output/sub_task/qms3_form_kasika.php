<?php

use QMS3\Brick\Config\Util as U;
use QMS3\Brick\SubTask\Kasika;


/**
 * @param     string                 $pid
 * @param     array<string,mixed>    $options
 * @param     bool                   $overwrite
 * @param     string                 $enc
 * @return    Kasika
 */
function qms3_form_kasika(
    $pid,
    array $options = array(),
    $overwrite     = false,
    $enc           = "UTF-8"
)
{
    $normalized_options = [];
    foreach ($options as $label => $config) {
        $normalized_options[$label] = U::wrap($config);
    }

    return new Kasika(
        $pid,
        $normalized_options,
        $overwrite,
        $enc
    );
}
