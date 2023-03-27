<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostMeta;

use QMS3\BrickAdmin\PostMeta\PostMetaInterface;
use QMS3\BrickAdmin\Settings\NotificationSettings;


class NotificationMasterMetaBox implements PostMetaInterface
{
    const VERSION = "20220617";

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
     * @param    void
     */
    public function register()
    {
        // register_post_meta(
        //     /* $post_type = */ $this->post_type,
        //     /* $meta_key  = */ 'mail_settings',
        //     array(
        //         'type' => 'array',
        //         'single' => true,
        //         'default' => NotificationSettings::default_mail_settings()
        //     )
        // );
    }

    public function enqueue_style()
    {
        wp_enqueue_style(
            /* $handle = */ 'jsuites',
            /* $src    = */ plugins_url( '../../assets/css/jsuites.css', __FILE__ ),
            /* $deps   = */ array()
        );

        wp_enqueue_style(
            /* $handle = */ 'jspreadsheet',
            /* $src    = */ plugins_url( '../../assets/css/jspreadsheet.css', __FILE__ ),
            /* $deps   = */ array()
        );

        wp_enqueue_style(
            /* $handle = */ 'qms3_form__meta_box__notification_master.css',
            /* $src    = */ plugins_url( '../../assets/css/qms3_form__meta_box__notification_master.css', __FILE__ ),
            /* $deps   = */ array( 'jsuites', 'jspreadsheet' ),
            /* $ver    = */ self::VERSION
        );
    }

    public function add_meta_box()
    {
        add_meta_box(
            /* $id       = */ 'qms3_form__meta_box__notification_master',
            /* $title    = */ '通知設定',
            /* $callback = */ array( $this, 'render_meta_box' ),
            /* $screen   = */ $this->post_type
        );
    }

    public function on_save_post()
    {
        if ( ! isset( $_POST[ 'post_ID' ] ) || ! is_numeric( $_POST[ 'post_ID' ] ) ) {
            return;
        }

        $post_id = $_POST[ 'post_ID' ];

        if (
            ! isset( $_POST[ 'qms3_form__meta_box__notification_master__nonce' ] )
            || ! wp_verify_nonce(
                $_POST[ 'qms3_form__meta_box__notification_master__nonce' ],
                "qms3_form__meta_box__notification_master__nonce__{$post_id}"
            )
        ) { return; }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if ( ! current_user_can( 'edit_posts' ) ) { return; }

        if ( ! isset( $_POST[ 'qms3_form__meta_box__notification_master' ] ) ) { return; }


        $mail_settings_json = stripslashes( $_POST[ 'qms3_form__meta_box__notification_master' ] );
        $mail_settings = json_decode( $mail_settings_json, /* $assoc = */ true );

        $notification = NotificationSettings::from_post_id( $post_id );

        $notification->mail_settings = $mail_settings;

        $notification->save();
    }

    // ====================================================================== //

    /**
     * @param    \WP_Post    $post
     */
    public function render_meta_box( \WP_Post $post )
    {
        wp_nonce_field(
            "qms3_form__meta_box__notification_master__nonce__{$post->ID}",
            'qms3_form__meta_box__notification_master__nonce'
        );

        $handle = 'qms3_form__meta_box__notification_master.js';

        wp_register_script(
            /* $handle   = */ $handle,
            /* $src      = */ plugins_url( '../../assets/js/qms3_form__meta_box__notification_master.min.js', __FILE__ ),
            /* $deps     = */ array( 'jsuites', 'jspreadsheet' ),
            /* $ver      = */ self::VERSION
        );

        $notification = NotificationSettings::from_post_id( $post->ID );

        wp_localize_script(
            /* $handle = */ $handle,
            /* $name   = */ 'QMS3_FORM__META_BOX__NOTIFICATION_MASTER',
            /* $data   = */ $notification->mail_settings
        );

        wp_enqueue_script( $handle );

        include( __DIR__ . '/../../assets/templates/qms3_form__meta_box__notification_master.php' );
    }
}
