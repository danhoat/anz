<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostMeta;

use QMS3\BrickAdmin\PostMeta\PostMetaInterface;
use QMS3\BrickAdmin\Settings\CustomMailSettings;


class MailSettings implements PostMetaInterface
{
    const VERSION = "20210510";

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
            /* $handle = */ 'qms3_form__meta_box__mail_settings.css',
            /* $src    = */ plugins_url( '../../assets/css/qms3_form__meta_box__mail_settings.css', __FILE__ ),
            /* $deps   = */ array( 'jsuites', 'jspreadsheet' ),
            /* $ver    = */ self::VERSION
        );
    }

    /**
     * @return    void
     */
    public function add_meta_box()
    {
        add_meta_box(
            /* $id       = */ 'qms3_form__meta_box__mail_settings',
            /* $title    = */ 'サンクスメール・通知メール 設定',
            /* $callback = */ array( $this, 'render_meta_box' ),
            /* $screen   = */ 'brick'
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
            ! isset( $_POST[ 'qms3_form__meta_box__mail_settings__nonce' ] )
            || ! wp_verify_nonce(
                $_POST[ 'qms3_form__meta_box__mail_settings__nonce' ],
                "qms3_form__meta_box__mail_settings__nonce__{$post_id}"
            )
        ) { return; }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if (
            ! current_user_can( 'atsumaru' )
            && ! current_user_can( 'administrator' )
        ) { return; }

        if ( ! isset( $_POST[ 'qms3_form__meta_box__mail_settings' ] ) ) { return; }


        $mail_settings_json = stripslashes( $_POST[ 'qms3_form__meta_box__mail_settings' ] );
        $mail_settings = json_decode( $mail_settings_json, /* $assoc = */ true );

        if ( empty( $mail_settings ) ) { return; }

        $slug = get_post_field( 'post_name', $post_id );


        $mail = CustomMailSettings::get( $post_id );
        $mail->mail_settings = $mail_settings;
        $mail->main = $slug;
        $mail->save();
    }

    // ====================================================================== //

    /**
     * @param    \WP_Post    $post
     */
    public function render_meta_box( \WP_Post $wp_post )
    {
        wp_nonce_field(
            "qms3_form__meta_box__mail_settings__nonce__{$wp_post->ID}",
            'qms3_form__meta_box__mail_settings__nonce'
        );

        $handle = 'qms3_form__meta_box__mail_settings.js';

        wp_register_script(
            /* $handle   = */ $handle,
            /* $src      = */ plugins_url( '../../assets/js/qms3_form__meta_box__mail_settings.min.js', __FILE__ ),
            /* $deps     = */ array( 'jsuites', 'jspreadsheet' ),
            /* $ver      = */ self::VERSION
        );

        $mail = CustomMailSettings::get( $wp_post->ID );

        wp_localize_script(
            /* $handle = */ $handle,
            /* $name   = */ 'QMS3_FORM__META_BOX__MAIL_SETTINGS',
            /* $data   = */ $mail->mail_settings
        );

        wp_enqueue_script( $handle );

        include( __DIR__ . '/../../assets/templates/qms3_form__meta_box__mail_settings.php' );
    }
}
