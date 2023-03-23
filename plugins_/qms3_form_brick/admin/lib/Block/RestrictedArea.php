<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Block;

use Detection\MobileDetect;


class RestrictedArea
{
    /** @var    string */
    private $name = 'restricted-area';

    /**
     * @return    void
     */
    public function register()
    {
        register_block_type(
            __DIR__ . "/../../blocks/build/{$this->name}",
            array(
                'render_callback' => array( $this, 'render' ),
            )
        );
    }

    /**
     * @param   array<string,mixed>    $attributes
     * @param   string|null    $inner_content
     * @return    string
     */
    public function render( array $attributes, $inner_content )
    {
        $screen = $attributes[ 'screen' ];

        $detect = new MobileDetect();
        $isMobile = $detect->isMobile();

        return ( $screen === 'sp' && $isMobile ) || ( $screen === 'pc' && ! $isMobile )
            ? $inner_content
            : '';
    }
}
