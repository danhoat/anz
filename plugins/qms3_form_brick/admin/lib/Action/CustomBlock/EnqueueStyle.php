<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;


class EnqueueStyle
{
    /**
     * @return    void
     */
    public function __invoke()
    {
        wp_enqueue_style(
            'qms3_form__custom_block__base_form',
            plugins_url( '../../../../css/base_form.css', __FILE__ )
        );
    }
}
