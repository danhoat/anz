<?php
function get_list_post_types_registed_by_qms4(){

	$query = new \WP_Query( array(
			'post_type' => 'qms4',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			// 'meta_query' => array(
			// 	array(
			// 		'key' => 'qms4__post_type__name',
			// 		'value' => $names,
			// 		'compare' => 'IN',
			// 	),
			// ),
	) );
	if($query->have_posts() ){
		while($query->have_posts() ){

			$query->the_post();
			global $post;
			echo $post->post_type;
			the_title();
		}
	} else{
		echo 'no post type registered';
	}

}
add_action('wp_head','get_list_post_types_registed_by_qms4');

function get_post_types_register_by_qms4(){


	$list_types = new WP_Query($args);
}
?>