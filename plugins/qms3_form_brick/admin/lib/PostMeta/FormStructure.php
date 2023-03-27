<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostMeta;

use QMS3\BrickAdmin\PostMeta\PostMetaInterface;
use QMS3\BrickAdmin\Settings\FormStructureSettings;


class FormStructure implements PostMetaInterface
{
    const VERSION = "20210510";

    private $post_type;

    /**
     * @param    string    $post_type
     */
    public function __construct( $post_type )
    {
        $this->post_type = $post_type;
    }

    /**
     * @param    void
     */
    public function register()
    {
        // register_post_meta(
        //     /* $post_type = */ $this->post_type,
        //     /* $meta_key  = */ 'form_structure',
        //     array(
        //         'type' => 'array',
        //         'single' => true,
        //         'default' => array(),
        //         'show_in_rest' => array(
        //             'schema' => array(
        //                 'type' => 'array',
        //                 'items' => array(
        //                     'type' => 'object',
        //                     'properties' => array(
        //                         'block' => array( 'type' => 'string' ),
        //                         'label' => array( 'type' => 'string' ),
        //                         'type' => array( 'type' => 'string' ),
        //                         'name' => array( 'type' => 'string' ),
        //                         'default' => array( 'type' => 'string' ),
        //                         'prepend' => array( 'type' => 'string' ),
        //                         'append' => array( 'type' => 'string' ),
        //                         'header_notice' => array( 'type' => 'string' ),
        //                         'body_notice' => array( 'type' => 'string' ),
        //                         'options' => array( 'type' => 'string' ),
        //                         'placeholder' => array( 'type' => 'string' ),
        //                         'for_bcc' => array( 'type' => 'boolean' ),
        //                         'thanks_to' => array( 'type' => 'boolean' ),
        //                         'required' => array( 'type' => 'boolean' ),
        //                         'attributes' => array( 'type' => 'string' ),
        //                     ),
        //                 ),
        //             ),
        //         ),
        //     )
        // );
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
            /* $handle = */ 'qms3_form__meta_box__form_structure.css',
            /* $src    = */ plugins_url( '../../assets/css/qms3_form__meta_box__form_structure.css', __FILE__ ),
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
            /* $id       = */ 'qms3_form__meta_box__form_structure',
            /* $title    = */ 'フォーム項目 設定',
            /* $callback = */ array( $this, 'render_meta_box' ),
            /* $screen   = */ $this->post_type
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
            ! isset( $_POST[ 'qms3_form__meta_box__form_structure__nonce' ] )
            || ! wp_verify_nonce(
                $_POST[ 'qms3_form__meta_box__form_structure__nonce' ],
                "qms3_form__meta_box__form_structure__nonce__{$post_id}"
            )
        ) { return; }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        if (
            ! current_user_can( 'atsumaru' )
            && ! current_user_can( 'administrator' )
        ) { return; }

        if ( ! isset( $_POST[ 'qms3_form__meta_box__form_structure' ] ) ) { return; }


        $form_structure_json = stripslashes( $_POST[ 'qms3_form__meta_box__form_structure' ] );
        $form_structure = json_decode( $form_structure_json, /* $assoc = */ true );


        $form_structure_setting = FormStructureSettings::get( $post_id );
        $form_structure_setting->form_structure = $form_structure;
        $form_structure_setting->save();
    }

    // ====================================================================== //

    /**
     * @param    \WP_Post    $post
     */
    public function render_meta_box( \WP_Post $wp_post )
    {
        wp_nonce_field(
            "qms3_form__meta_box__form_structure__nonce__{$wp_post->ID}",
            'qms3_form__meta_box__form_structure__nonce'
        );

        $handle = 'qms3_form__meta_box__form_structure.js';

        wp_register_script(
            /* $handle   = */ $handle,
            /* $src      = */ plugins_url( '../../assets/js/qms3_form__meta_box__form_structure.min.js', __FILE__ ),
            /* $deps     = */ array( 'jsuites', 'jspreadsheet' ),
            /* $ver      = */ self::VERSION
        );

        $form_structure_settings = FormStructureSettings::get( $wp_post->ID );

        wp_localize_script(
            /* $handle = */ $handle,
            /* $name   = */ 'QMS3_FORM__META_BOX__FORM_STRUCTURE',
            /* $data   = */ $form_structure_settings->form_structure
        );

        wp_enqueue_script( $handle );

        include( __DIR__ . '/../../assets/templates/qms3_form__meta_box__form_structure.php' );
    }
}
