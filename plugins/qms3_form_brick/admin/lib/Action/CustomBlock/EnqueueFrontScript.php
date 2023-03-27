<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;

use QMS3\BrickAdmin\Block\BlockUtil;


class EnqueueFrontScript
{
    /**
     * @return    void
     */
    public function __invoke()
    {
        if ( BlockUtil::used( 'brick/form' ) ) {
            wp_enqueue_script(
                'qms3_form',
                plugins_url( '../../../../qms3_form.min.js', __FILE__ ),
                array( 'jquery' ),
                false,
                /* $in_footer = */ true
            );

            wp_enqueue_script(
                'qms3_form__init',
                plugins_url( '../../../assets/js/qms3_form__init.js', __FILE__ ),
                array( 'jquery', 'qms3_form' ),
                false,
                /* $in_footer = */ true
            );
        }
    }
}
