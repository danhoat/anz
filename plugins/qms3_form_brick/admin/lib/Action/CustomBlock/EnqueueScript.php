<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;


class EnqueueScript
{
    /**
     * @return    void
     */
    public function __invoke()
    {
        wp_enqueue_script(
            'qms3_form__block_script',
            plugins_url( '../../../blocks/js/qms3_form__block_script.js', __FILE__ ),
            array(),
            false,
            /* $in_footer = */ true
        );
    }
}
