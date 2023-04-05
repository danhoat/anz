<?php

use QMS4\Param\Param;
use QMS4\PostTypeMeta\PostTypeMeta;

use QMS4\QueryBuilder\QueryBuilder;
use QMS4\QueryBuilderPart\AreaQuery;
use QMS4\QueryBuilderPart\AuthorQuery;
use QMS4\QueryBuilderPart\ListQuery;
use QMS4\QueryBuilderPart\PageQuery;
use QMS4\QueryBuilderPart\PostInQuery;
use QMS4\QueryBuilderPart\OffsetQuery;
use QMS4\QueryBuilderPart\TaxonomyFilterQuery;
use QMS4\QueryBuilderPart\TermQuery;
use QMS4\QueryBuilderPart\ValidEventQuery;

use QMS4\Item\Post\Post;
use QMS4\Collection\Items;


/**
 * @param    string|string[]    $post_type
 * @param    array<string,mixed>    $_param
 * @return    Items
 */
function qms4_list( $post_type, array $_param = array() ): Items
{
	$post_type = is_array( $post_type ) ? $post_type : array( $post_type );
	$post_type_meta = PostTypeMeta::from_name( $post_type );

	$_param = array_merge( $_param, array( 'post_type' => $post_type ) );
	$param = Param::new( $post_type_meta, $_param );

	$query_builders = array(
		new ListQuery,
		new PageQuery,
		new AreaQuery,
		new TermQuery,
		new TaxonomyFilterQuery,
		new AuthorQuery,
		new PostInQuery,
	);

	if ( $post_type_meta->func_type() === 'event' ) {
		$query_builders[] = new ValidEventQuery( $post_type_meta->name() );
	}

	$query_builders = apply_filters(
		'qms4_list_queries',
		$query_builders,
		$post_type,
		$param,
		$post_type_meta
	);

	$list_query_builder = QueryBuilder::combine( $query_builders );

	$list_query_builder->add_part( new OffsetQuery( $list_query_builder ) );

	$query_args = $list_query_builder->build( $param );
	$query = new WP_Query( $query_args );

	$debug_mode = false;
	$debug_mode = apply_filters(
		'qms4_list_debug',
		$debug_mode,
		$post_type,
		$param,
		$post_type_meta
	);

	if ( $debug_mode ) {
		echo "<!--\n";
		var_dump(array(
			'$post_type' => $post_type,
			'$param' => $param,
			'$post_type_meta' => $post_type_meta,
			'$found_posts' => $query->found_posts,
			'$request'     => $query->request,
			'$query_args'  => $query_args,
		));
		echo "-->\n";
	}

	$item_class = Post::class;
	$item_class = apply_filters(
		"qms4_list_item_class",
		$item_class,
		$post_type,
		$param,
		$post_type_meta
	);

	$items = [];
	foreach ( $query->posts as $wp_post ) {  // OPTIMIZE: ここもっとメモリ喰わない書き方ありそう
		$items[] =  new $item_class($wp_post, $param);
	}

	return new Items( $items );
}
