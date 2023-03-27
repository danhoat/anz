<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\ColumnSettings;


class EnqueueStyle
{
    /**
     * @param    string    $hook_suffix
     * @return    void
     */
    public function __invoke( $hook_suffix )
    {
        $screen = get_current_screen();

        if ( $screen->id !== 'edit-brick' ) { return; }

        wp_enqueue_style(
            'qms3_form__columns',
            plugins_url( '../../../assets/css/qms3_form__columns.css', __FILE__ )
        );
    }
}
