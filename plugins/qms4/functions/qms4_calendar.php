<?php
/**
 * define style of block calendar.
 * 1 : defalt - 1 show 1month + block list event of date selected.
 * 2:  custom - show 2 months and hidden the list event in the right site.
 **/
function qms4_calendar_style()
{

	return apply_filters('block_canlendar_style', 'custom');

}


function qms4_get_color( $post_id)
{
	$color_term_name = apply_filters('get_color_term_name', 'fair__special'); //event__category
	$terms 	= get_the_terms($post_id, $color_term_name );
	if( !$terms  || is_wp_error($terms) ){
		return 0;
	}

	$term 	= $terms[0];

	$color = get_field( 'field_62fb7354562ba', $term->taxonomy . '_' . $term->term_id );
	if( empty($color) ) return 0;

   return (object) array('slug'=>$term->term_id,'color' => $color);
}

function qms4_list_events_by_date()
{

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
function js_calendar_style(){?>
	<script type="text/javascript">
		var event_clendar_new_style = '<?php  echo  qms4_calendar_style() !== 'custom' ? 0 :1 ?>';
	</script>
<?php }

add_action('admin_head','js_calendar_style');