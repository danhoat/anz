<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsParser;

use QMS3\Brick\OptionsParser\OptionsParseResult;
use QMS3\Brick\OptionsParser\YearTermParser;


/**
 * label  := /^[\w\s]+$/
 * value  := /^[\w\s]+$/
 * figure := /^\w+$/
 * name   := /^\w+$/
 *
 * label_with_figure   := "[" , label , "](" , figure , ")"
 * label_with_text     := label , "[" , name , "]"
 * label_with_textarea := label , "[[" , name , "]]"
 *
 * right_hand := label_with_figure | label_with_text | label_with_textarea | label
 *
 * line := [ value , "=>" ] , right_hand
 *
 * src := { line , "\n" }
 */
class SelectOptionsParser
{
    /**
     * @param    string    $src
     * @return    OptionsParseResult[]
     */
    public function parse( $src )
    {
        $lines = explode( "\n", $src );
        $lines = array_filter( array_map( 'trim', $lines ) );

        $year_term_parser = new YearTermParser();

        $results = array();
        foreach ( $lines as $line ) {
            try {
                $results = array_merge(
                    $results,
                    $year_term_parser->parse( $line )
                );
                continue;
            }
            catch ( \InvalidArgumentException $e ) {}

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
            $value = $label = trim( $value_and_label[ 0 ] );
        }


        return new OptionsParseResult(
            /* $label = */ $label,
            /* $value = */ $value
        );
    }
}
