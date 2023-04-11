<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Field\Field;
use QMS3\Brick\FieldFactory\FieldFactory;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * @since    1.5.2
 */
class FieldsInit
{
    /**
     * @param     Structure    $structure
     * @param     Values       $values
     * @param     Step         $step
     * @return    array<string,Field>
     */
    public function init(Structure $structure, Values $values, Step $step)
    {
        $factory = new FieldFactory();
        //var_dump($values);
        $fields = [];
        foreach ($structure as $name => $structure_row) {

            // if(isset($_GET['id'])){
            //     $post = get_post($_GET['id']);
            //      if( $post && !is_wp_error($post)){
            //         if( $name == 'fairDate'){

            //             $structure_row->options =  qms4_list_schedules_of_fair($post->ID);

            //         }
            //         if($name == 'fairTime'){
            //             $structure_row->options =  "
            //              10:00
            //             11:30
            //             15:00
            //             21:30";
            //         }


            //     }


            // }
            $fields[$name] = $factory->create($structure_row, $values, $step);
        }

        return $fields;
    }
}
