<?php

function qms4_list_events_by_date(){

	$ymd = isset($_GET['ymd']) ? $_GET['ymd']: date('Y-m-d');
	$meta_value = $ymd;
	$meta_key = 'qms4__event_date';
	global $wpdb;

	$sql =  $wpdb->prepare(
      	"SELECT s.meta_value as id FROM $wpdb->postmeta m
      		LEFT JOIN $wpdb->postmeta s ON m.post_id = s.post_id
      			WHERE   m.meta_key = %s AND m.meta_value = %s
      					AND s.meta_key = %s GROUP BY s.meta_value
      			",
		      $meta_key,
		      $meta_value,
		      'qms4__parent_event_id'
   );

	$parent_ids = $wpdb->get_results($sql, ARRAY_A);
	$ids = array();
	foreach( $parent_ids as $key=>$value){
		$ids[] = (int) $value['id'];
	}

	$args = array(
		'post_type' => 'fair',
		'post_status' => 'publish',
		'post__in' => $ids
	);
	echo '<ul class="p-postList -type-list">';
	$list = new WP_Query($args);
	if($list->have_posts() ){
		while($list->have_posts() ){
			$list->the_post();
			global $post;
			//sample_html($post);
		}
	} else{
		_e('Not post found.');
	}
	echo '</div>';


}
add_shortcode( 'events_date', 'qms4_list_events_by_date' );

function filter_events_by_select_date($query){
	if( is_admin() ) return ;
	if( is_post_type_archive() && $query->is_main_query() ){
		$ymd = isset($_GET['ymd']) ? $_GET['ymd']: '';
		if( !empty($ymd) ){

			$meta_value = $ymd;
			$meta_key = 'qms4__event_date';
			global $wpdb;

			$sql =  $wpdb->prepare(
		      	"SELECT s.meta_value as id FROM $wpdb->postmeta m
		      		LEFT JOIN $wpdb->postmeta s ON m.post_id = s.post_id
		      			WHERE   m.meta_key = %s AND m.meta_value = %s
		      					AND s.meta_key = %s GROUP BY s.meta_value
		      			",
				      $meta_key,
				      $meta_value,
				      'qms4__parent_event_id'
		   );

			$parent_ids = $wpdb->get_results($sql, ARRAY_A);
			$ids = array();
			foreach( $parent_ids as $key=>$value){
				$ids[] = (int) $value['id'];
			}

			$query->set('post__in',$ids);

		}
	}

}

add_action( 'pre_get_posts', 'filter_events_by_select_date' );