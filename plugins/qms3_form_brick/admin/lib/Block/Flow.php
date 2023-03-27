<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Block;

use QMS3\Brick\Flow\Flow as FormFlow;
use QMS3\Brick\Step\Step;


class Flow
{
    /** @var    string */
    private $name = 'flow';

    /**
     * @param    void
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
     * @param    array<string,mixed>    $attributes
     * @param    string|null    $content
     * @return    string
     */
    public function render( array $attributes, $content )
    {
        $steps = array(
            'input' => $attributes[ 'labelInput' ],
            'confirm' => $attributes[ 'labelConfirm' ],
            'thanks' => $attributes[ 'labelThanks' ],
        );

        if ( isset( $_GET[ 'param' ] ) && $_GET[ 'param' ] == 'confirm' ) {
            $current = new Step( Step::CONFIRM );
        } elseif ( isset( $_GET[ 'param' ] ) && $_GET[ 'param' ] == 'submit' ) {
            $current = new Step( Step::SUBMIT );
        } else {
            $current = new Step( Step::INPUT );
        }

        $flow = new FormFlow( $current, $steps );

        return (string) $flow;
    }
}
