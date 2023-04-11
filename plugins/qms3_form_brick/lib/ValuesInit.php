<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\ServerRequest\ServerRequest;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;
use QMS3\Brick\ValueDecoderFactory\ValueDecoderFactory;
use QMS3\Brick\Option\SelectOption;


/**
 * @since    1.5.2
 */
class ValuesInit
{
    /**
     * @param     Structure              $structure
     * @param     array<string,mixed>    $default_values
     * @return    Values
     */
    public function init(Structure $structure, array $default_values)
    {
        $factory = new ValueDecoderFactory();
        $decoder = $factory->create($structure);
        $df_get =  isset($_GET)   ? $_GET   : [];

        if(isset($_GET['id'])){
            $f_post = array();
            $post = get_post($_GET['id']);
                if($post && !is_wp_error($post)){

                $f_post['fairName'] = $post->post_title;
                // $f_post['fairName'] = 'fairName okk';
                // $f_post['fairTime'] = '20:00';
                $df_get = $f_post;
            }

        }
        $request = new ServerRequest(
            $df_get,
            isset($_POST)  ? $_POST  : [],
            isset($_FILES) ? $_FILES : []
        );


        return $decoder->decode($request, $default_values);
    }
}
