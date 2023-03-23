<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsParser;

use QMS3\Brick\Enum\ExtraInputType;
use QMS3\Brick\OptionsParser\OptionsParseResult;


/**
 * label       := /^[\w\s]+$/
 * value       := /^[\w\s]+$/
 * figure      := /^\w+$/
 * name        := /^\w+$/
 * placeholder := /^[\w\s]+$/
 *
 * label_with_figure   := "[" , label , "](" , figure , ")"
 * label_with_text     := label , "[" , name , [ "|" , placeholder ] , "]"
 * label_with_textarea := label , "[[" , name , [ "|" , placeholder ] , "]]"
 *
 * right_hand := label_with_figure | label_with_text | label_with_textarea | label
 *
 * line := [ value , "=>" ] , right_hand
 *
 * src := { line , "\n" }
 */
class CheckboxOptionsParser
{
    /**
     * @param    string    $src
     * @return    OptionsParseResult[]
     */
    public function parse( $src )
    {
        $lines = explode( "\n", $src );
        $lines = array_filter( array_map( 'trim', $lines ) );

        $results = array();
        foreach ( $lines as $line ) {
            $results[] = $this->parse_item( $line );
        }

        return $results;
    }

    /**
     * @param    string    $src
     * @return    OptionsParseResult
     */
    private function parse_item( $line )
    {
        $value_and_label = explode( '=>', $line, /* $limit = */ 2 );

        if ( ! $value_and_label ) { throw new \RuntimeException(); }


        if ( count( $value_and_label ) == 2 ) {
            list( $value, $label ) = $value_and_label;
            $value = trim( $value );
            $label = trim( $label );
        } else {
            $label = trim( $value_and_label[ 0 ] );
        }


        try {
            list(
                $label,
                $extra_input_name,
                $extra_input_placeholder,
                $extra_input_type
            ) = $this->parse_extra_input( $label );
        }
        catch ( \Exception $e ) {}

        try {
            list( $label, $figure ) = $this->parse_figure( $label );
        }
        catch ( \Exception $e ) {}


        // TODO: extra_input_value を設定するルールや解析するロジックはまだ無い
        return new OptionsParseResult(
            /* $label = */ $label,
            /* $value = */ isset( $value ) ? $value : $label,
            /* $figure = */ isset( $figure ) ? $figure : null,
            /* $extra_input_type = */ isset( $extra_input_type ) ? $extra_input_type : null,
            /* $extra_input_name = */ isset( $extra_input_name ) ? $extra_input_name : null,
            /* $extra_input_value = */ null,
            /* $extra_input_placeholder = */ isset( $extra_input_placeholder ) ? $extra_input_placeholder : null
        );
    }

    /**
     * @param    string    $label
     * @return    mixed[]
     */
    private function parse_extra_input( $label )
    {
        $regexp_extra_input_textarea = '/^(?<label>.+?)\[\[(?<name>.+?)(?:\|(?<placeholder>.+))?\]\]$/u';
        $regexp_extra_input_text = '/^(?<label>.+?)\[(?<name>.+?)(?:\|(?<placeholder>.+))?\]$/u';

        $label = trim( $label );

        if ( preg_match( $regexp_extra_input_textarea, $label, $matches ) ) {
            return array(
                trim( $matches[ 'label' ] ),
                trim( $matches[ 'name' ] ),
                isset( $matches[ 'placeholder' ] ) ? trim( $matches[ 'placeholder' ] ) : '',
                new ExtraInputType( ExtraInputType::TEXTAREA ),
            );
        }

        if ( preg_match( $regexp_extra_input_text, $label, $matches ) ) {
            return array(
                trim( $matches[ 'label' ] ),
                trim( $matches[ 'name' ] ),
                isset( $matches[ 'placeholder' ] ) ? trim( $matches[ 'placeholder' ] ) : '',
                new ExtraInputType( ExtraInputType::TEXT ),
            );
        }

        // TODO: RuntimeException を投げるのは適切ではないのでもっといい感じの Exception にする
        throw new \RuntimeException();
    }

    /**
     * @param    string    $label
     * @return    mixed[]
     */
    private function parse_figure( $label )
    {
        $regexp_figure = '/^\[(?<label>.+?)\]\s*\((?<figure>.+)\)$/';

        $label = trim( $label );

        if ( preg_match( $regexp_figure, $label, $matches ) ) {
            return array(
                trim( $matches[ 'label' ] ),
                trim( $matches[ 'figure' ] ),
            );
        }

        // TODO: RuntimeException を投げるのは適切ではないのでもっといい感じの Exception にする
        throw new \RuntimeException();
    }
}
