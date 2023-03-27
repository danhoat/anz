<?php

use QMS3\Brick\SubTask\WordPressPost;

/**
 * @since    1.5.2
 *
 * @param    string                 $post_type
 * @param    array<string,mixed>    $options
 * @return   WordPressPost
 */
function qms3_form_wp_post($post_type = '', array $options = [])
{
    return new WordPressPost($post_type, $options);
}
