<?php
declare(strict_types=1);

/**
 * @since    1.6.1
 *
 * @param    bool    $minified
 * @return    string
 */
function qms3_form_css_url( $minified = true )
{
    if ( ! function_exists( 'plugins_url' ) ) { return ''; }

    return $minified
        ? plugins_url( '../css/base_form.css', __FILE__ )
        : plugins_url( '../css/base_form.css', __FILE__ );
}
