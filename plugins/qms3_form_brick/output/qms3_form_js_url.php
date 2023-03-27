<?php
declare(strict_types=1);

/**
 * @param    bool    $minified
 * @return    string
 */
function qms3_form_js_url( $minified = true )
{
    if ( ! function_exists( 'plugins_url' ) ) { return ''; }

    return $minified
        ? plugins_url( '../qms3_form.min.js', __FILE__ )
        : plugins_url( '../qms3_form.js', __FILE__ );
}
