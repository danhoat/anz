<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\Util\Blocks;


class FormRouteCoodinator
{
    public function __construct()
    {
        add_action( 'template_redirect', array( $this, 'send_form' ) );
    }

    public function send_form()
    {
        if ( ! isset( $_GET[ 'param' ] ) || $_GET[ 'param' ] !== 'submit' ) {
            return;
        }

        $wp_post = get_queried_object();

        if ( ! ( $wp_post instanceof \WP_post ) ) { return; }

        $blocks = new Blocks( $wp_post );
        $block = $blocks->find_form();

        if ( ! $block ) { return; }

        $post_id = $block[ 'attrs' ][ 'postId' ];
        $wp_post = get_post( $post_id );

        if ( ! $wp_post || $wp_post->post_status != 'publish' ) {
            return;
        }

        $param = [];

        $param[ 'thanks_path' ] = empty( $block[ 'attrs' ][ 'thanksPath' ] )
            ? './thanks/'
            : $block[ 'attrs' ][ 'thanksPath' ];

        if ( ! empty( $block[ 'attrs' ][ 'pcThanksPath' ] ) ) {
            $param[ 'pc_thanks_path' ] = $block[ 'attrs' ][ 'pcThanksPath' ];
        }
        if ( ! empty( $block[ 'attrs' ][ 'spThanksPath' ] ) ) {
            $param[ 'sp_thanks_path' ] = $block[ 'attrs' ][ 'spThanksPath' ];
        }

        qms3_form_init( $wp_post->post_name, $param );
    }
}
