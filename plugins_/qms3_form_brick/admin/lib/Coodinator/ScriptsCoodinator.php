<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\Util\Blocks;


class ScriptsCoodinator
{
    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_register_script' ) );
    }

    /**
     * @return    void
     */
    public function admin_register_script()
    {
        wp_register_script(
            'jsuites',
            plugins_url( '../../assets/js/jsuites.js', __FILE__ )
        );

        wp_register_script(
            'jspreadsheet',
            plugins_url( '../../assets/js/jspreadsheet.js', __FILE__ )
        );
    }
}
