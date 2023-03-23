<?php
declare(strict_types=1);

namespace QMS3\Brick\OptionsParser;

use QMS3\Brick\OptionsParser\OptionsParseResult;


class YearTermParser
{
    /**
     * @param    string    $src
     * @return    OptionsParseResult[]
     */
    public function parse( $src )
    {
        $boundary = $this->parse_boundary( $src );
        $base_year = $this->base_year( $boundary );

        list( $start, $end, $template ) = $this->parse_term( $src );

        return $this->create_results( $base_year, $start, $end, $template );
    }

    /**
     * @param    string    $src
     * @return    int[]
     * @throws    \InvalidArgumentException
     */
    private function parse_boundary( $src )
    {
        $regexp = '%^YEAR(\[(?<boundary>\d{1,2}/\d{1,2})\])?\s*=>%';
        if ( ! preg_match( $regexp, trim( $src ), $matches ) ) {
            throw new \InvalidArgumentException();
        }

        if ( ! isset( $matches[ 'boundary' ] ) ) {
            // boundary の初期値は [1, 1]
            // boundary が指定されていなければ 1月1日 が指定されたとみなす
            return array( 1, 1 );
        }

        list( $month, $day ) = explode( '/', $matches[ 'boundary' ], 2 );

        return array( (int) $month, (int) $day );
    }

    /**
     * @param    string    $src
     * @return    string[]
     * @throws    \InvalidArgumentException
     */
    private function parse_term( $src )
    {
        list( , $value ) = explode( '=>', $src, 2 );

        $regexp_basic = '/^\s*(?<term>[+\-]?\d+(\s*[~～〜]\s*[+\-]?\d+)?)\s*$/u';
        $regexp_embed = '/\{\s*(?<term>[+\-]?\d+(\s*[~～〜]\s*[+\-]?\d+)?)\s*\}/u';
        if ( preg_match( $regexp_basic, $value, $matches ) ) {
            $template = '';
        } elseif ( preg_match( $regexp_embed, $value, $matches ) ) {
            $template = preg_replace( $regexp_embed, '%year%', $value, 1 );
        } else {
            throw new \InvalidArgumentException();
        }

        $term = trim( $matches[ 'term' ] );
        $term = str_replace( array( '～', '〜' ), '~', $term );

        if ( strpos( $term, '~' ) === false ) {
            $term = $term[0] === '+' || $term[0] === '-'
                ? $term
                : '+' . $term;

            return array( '+0', $term, $template );
        } else {
            list( $start, $end ) = explode( '~', $term, 2 );

            return array( trim( $start ), trim( $end ), $template );
        }
    }

    /**
     * @param    int[]    $boundary
     * @return    int
     */
    private function base_year( array $boundary )
    {
        $tz = new \DateTimeZone( 'Asia/Tokyo' );
        $today = new \DateTime( 'now', $tz );
        $today_str = $today->format( 'md' );

        list( $month, $day ) = $boundary;
        $month_str = str_pad( (string) $month, 2, '0', STR_PAD_LEFT );
        $day_str = str_pad( (string) $day, 2, '0', STR_PAD_LEFT );
        $boundary_str = $month_str . $day_str;

        // 本日が境界日以前なら "昨年度" とみなす
        return $today_str < $boundary_str
            ? $today->format( 'Y' ) - 1
            : (int) $today->format( 'Y' );
    }

    /**
     * @param    int    $base_year
     * @param    string    $start
     * @param    string    $end
     * @param    string    $template
     * @return    OptionsParseResult[]
     */
    private function create_results( $base_year, $start, $end, $template )
    {
        if ( $start[0] === '+' ) {
            $start = $base_year + substr( $start, 1 );
        } elseif ( $start[0] === '-' ) {
            $start = $base_year - substr( $start, 1 );
        } else {
            $start = (int) $start;
        }

        if ( $end[0] === '+' ) {
            $end = $base_year + substr( $end, 1 );
        } elseif ( $end[0] === '-' ) {
            $end = $base_year - substr( $end, 1 );
        } else {
            $end = (int) $end;
        }

        if ( $start > $end ) {
            list( $end, $start ) = array( $start, $end );
        }

        $results = array();
        foreach ( range( $start, $end ) as $year) {
            $year = empty($template)
                ? (string) $year
                : str_replace( '%year%', (string) $year, $template );

            $results[] = new OptionsParseResult( $year, $year );
        }

        return $results;
    }
}
