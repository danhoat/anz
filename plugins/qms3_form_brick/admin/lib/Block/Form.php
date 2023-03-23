<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Block;


class Form
{
    /** @var    string */
    private $name = 'form';

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
        $post_id = $attributes[ 'postId' ];
        $wp_post = get_post( $post_id );

        if ( ! $wp_post || $wp_post->post_status != 'publish' ) {
            return '';
        }

        $param = [];

        if ( ! empty( $attributes[ 'thanksPath' ] ) ) {
            $param[ 'thanks_path' ] = $attributes[ 'thanksPath' ];
        }
        if ( ! empty( $attributes[ 'pcThanksPath' ] ) ) {
            $param[ 'pc_thanks_path' ] = $attributes[ 'pcThanksPath' ];
        }
        if ( ! empty( $attributes[ 'spThanksPath' ] ) ) {
            $param[ 'sp_thanks_path' ] = $attributes[ 'spThanksPath' ];
        }

        $form = qms3_form_init( $wp_post->post_name, $param );

        $inner_content = str_replace(
            'href="#"',
            'href="' . get_privacy_policy_url() . '"',
            $inner_content
        );
        $inner_content = str_replace(
            "href='#'",
            'href="' . get_privacy_policy_url() . '"',
            $inner_content
        );

        ob_start();
        if (file_exists( __DIR__ . "/../../blocks/templates/{$this->name}.php" )) {
            require( __DIR__ . "/../../blocks/templates/{$this->name}.php" );
        }
        return ob_get_clean();
    }
}
