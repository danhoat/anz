<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\Rest\FormStructureField;


class RestCoodinator
{
    public function __construct()
    {
        add_action( 'rest_api_init', array( $this, 'register_filed' ) );
    }

    /**
     * @param    \WP_REST_Server    $wp_rest_server
     * @return    void
     */
    public function register_filed( \WP_REST_Server $wp_rest_server )
    {
        ( new FormStructureField() )->register();
    }
}
