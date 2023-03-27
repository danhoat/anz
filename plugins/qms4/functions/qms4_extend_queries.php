<?php

/**
 * クエリ文字列同士を合成するためのユーティリティ
 *
 * @param    string    $query_str    合成するクエリ文字列
 * @param    string    $base_query_str    合成の元になるクエリ文字列
 * @return    string    $base_query_str と $query_str とを合成した結果のクエリ文字列
 *
 * @example
 *     qms3_extend_queries( 'banana=3&apple=4', 'apple=1&orange=2' );
 *     // => 'apple=4&orange=2&banana=3'
 */
function qms4_extend_queries( string $query_str, ?string $base_query_str = null ): string
{
    $base_query_str = $base_query_str ?: $_SERVER[ 'QUERY_STRING' ];

    // $query_str の先頭に ? が付いていれば、それを削除して始める
    if ( $query_str[0] === '?' ) { $query_str = substr( $query_str, 1 ); }

    parse_str( $query_str, $query );
    parse_str( $base_query_str, $base_query );

    $new_query = array_merge( $base_query, $query );
    $new_query = array_filter( $new_query );

    return http_build_query( $new_query );
}
