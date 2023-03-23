<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Coodinator;

use QMS3\BrickAdmin\PostType\BrickPostType;
use QMS3\BrickAdmin\PostType\NotificationMasterPostType;
use QMS3\BrickAdmin\SubmenuPage\BrickMasterPage;


class PostTypesCoodinator
{
    public function __construct()
    {
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_filter( 'default_hidden_meta_boxes', array( $this, 'show_meta_box' ), 10, 2 );

        $brick_master_page = new BrickMasterPage();
        add_action( 'admin_menu', array( $brick_master_page, 'add_submenu_page' ) );
        add_action( 'admin_print_styles', array( $brick_master_page, 'enqueue_style' ) );
    }

    public function register_post_types()
    {
        ( new BrickPostType() )->register();
        ( new NotificationMasterPostType() )->register();
    }

    public function show_meta_box( array $hidden, \WP_Screen $screen )
    {
        if ( ! in_array( $screen->post_type, array( 'brick', 'brick__notification' ), /* $strict = */ true ) ) {
            return $hidden;
        }

        if ( ! in_array( 'slugdiv', $hidden, /* $strict = */ true ) ) {
            return $hidden;
        }

        unset( $hidden[ array_search( 'slugdiv', $hidden, /* $strict = */ true ) ] );

        return $hidden;
    }
}
