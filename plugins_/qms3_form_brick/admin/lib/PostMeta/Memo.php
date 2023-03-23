<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostMeta;

use QMS3\BrickAdmin\PostMeta\PostMetaInterface;


class Memo implements PostMetaInterface
{
    const VERSION = "20210516";

    private $post_type;

    /**
     * @param    string    $post_type
     */
    public function __construct( $post_type )
    {
        $this->post_type = $post_type;
    }

    // ====================================================================== //

    /**
     * @return    void
     */
    public function register()
    {
    }

    /**
     * @return    void
     */
    public function enqueue_style()
    {
        wp_enqueue_style(
            /* $handle = */ 'qms3_form__meta_box__memo.css',
            /* $src    = */ plugins_url( '../../assets/css/qms3_form__meta_box__memo.css', __FILE__ ),
            /* $deps   = */ array(),
            /* $ver    = */ self::VERSION
        );
    }

    /**
     * @return    void
     */
    public function add_meta_box()
    {
        add_meta_box(
            /* $id       = */ 'qms3_form__meta_box__memo',
            /* $title    = */ 'メモ',
            /* $callback = */ array( $this, 'render_meta_box' ),
            /* $screen   = */ $this->post_type,
            /* $context  = */ 'side'
        );
    }

    /**
     * @return    void
     */
    public function on_save_post()
    {
        if (
            ! isset( $_POST[ 'post_ID' ] )
            || ! is_numeric( $_POST[ 'post_ID' ] )
        ) { return; }

        $post_id = $_POST[ 'post_ID' ];

        if (
            ! isset( $_POST[ 'qms3_form__meta_box__memo__nonce' ] )
            || ! wp_verify_nonce(
                $_POST[ 'qms3_form__meta_box__memo__nonce' ],
                "qms3_form__meta_box__memo__nonce__{$post_id}"
            )
        ) { return; }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if (
            ! current_user_can( 'atsumaru' )
            && ! current_user_can( 'administrator' )
        ) { return; }

        if ( ! isset( $_POST[ 'qms3_form__meta_box__memo' ] ) ) { return; }


        update_metadata(
            'post',
            $post_id,
            'brick_memo',
            $_POST[ 'qms3_form__meta_box__memo' ]
        );
    }

    // ====================================================================== //

    /**
     * @param    \WP_Post    $wp_post
     */
    public function render_meta_box( \WP_Post $wp_post )
    {
        wp_nonce_field(
            "qms3_form__meta_box__memo__nonce__{$wp_post->ID}",
            'qms3_form__meta_box__memo__nonce'
        );

        $memo = get_metadata(
            'post',
            $wp_post->ID,
            'brick_memo',
            /* $single = */ true
        );

        include( __DIR__ . '/../../assets/templates/qms3_form__meta_box__memo.php' );
    }
}
