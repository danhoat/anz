<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Util;


class Blocks
{
    /** @var    \WP_Post */
    private $wp_post;

    /**
     * @param    \WP_Post    $wp_post
     */
    public function __construct( \WP_Post $wp_post )
    {
        $this->wp_post = $wp_post;
    }

    /**
     * @return    array|false
     */
    public function find_form()
    {
        $blocks = parse_blocks( $this->wp_post->post_content );

        return $this->find( $blocks );
    }

    /**
     * @param    array[]    $blocks
     * @return    array|false
     */
    public function find( array $blocks )
    {
        foreach ( $blocks as $block ) {
            if ( $block[ 'blockName' ] == 'brick/form' ) { return $block; }

            if (
                ! empty( $block[ 'innerBlocks' ] )
                && ( $data = $this->find( $block[ 'innerBlocks' ] ) )
            ) { return $data; }
        }

        return false;
    }
}
