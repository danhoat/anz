<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Rest;

use QMS3\BrickAdmin\Settings\FormStructureSettings;


class FormStructureField
{
    /**
     * @return    void
     */
    public function register()
    {
        register_rest_field(
            'brick',
            'structure',
            array(
                'get_callback' => array( $this, 'get' ),
                'update_callback' => null,
                'schema' => null,
            )
        );
    }

    /**
     * @param    array<string,mixed>    $object
     * @param    string    $field_name
     * @param    \WP_REST_Request    $request
     * @return    array[]
     */
    public function get( array $object, $field_name, \WP_REST_Request $request )
    {
        $settings = FormStructureSettings::get( $object[ 'id' ] );
        $form_structure = $settings->form_structure;

        $blocks = array();
        foreach ( $this->group_by_block( $form_structure ) as $block ) {
            $blocks[] = $this->group_by_row( $block );
        }

        return $blocks;
    }

    /**
     * @param    array[]    $form_structure
     * @return    array[]
     */
    private function group_by_block( array $form_structure )
    {
        $bucket = array();
        foreach ( $form_structure as $row ) {
            if ( ! isset( $bucket[ $row[ 'block' ] ] ) ) {
                $bucket[ $row[ 'block' ] ] = array();
            }

            $bucket[ $row[ 'block' ] ][] = $row;
        }

        return array_values( $bucket );
    }

    /**
     * @param    array[]    $form_structure
     * @return    array[]
     */
    private function group_by_row( array $form_structure )
    {
        $bucket = [];
        foreach ( $form_structure as $row ) {
            if ( ! isset( $bucket[ $row[ 'label' ] ] ) ) {
                $bucket[ $row[ 'label' ] ] = [];
            }

            $bucket[ $row[ 'label' ] ][] = $row;
        }

        return array_values( $bucket );
    }
}
