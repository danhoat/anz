<?php
/**
 * define style of block calendar.
 * 1 : defalt - 1 show 1month + block list event of date selected.
 * 2:  custom - show 2 months and hidden the list event in the right site.
 **/

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
	wp_reset_query();
	echo '</div>';

}
add_shortcode( 'events_date', 'qms4_list_events_by_date' );


function filter_events_by_select_date($query){
	if( is_admin() ) return ;
	if( is_post_type_archive('fair') && $query->is_main_query() ){
		$ymd = isset($_GET['ymd']) ? $_GET['ymd']: '';
		$check = is_valid_date($ymd);
		if( !empty($ymd) && is_valid_date($ymd) ){

			$meta_value = $ymd;
			$meta_key = 'qms4__event_date';
			global $wpdb;
			$sql =  $wpdb->prepare("
	           SELECT SQL_CALC_FOUND_ROWS mt1.meta_value as parent_id
	           FROM $wpdb->posts p
	           	INNER JOIN $wpdb->postmeta m ON ( p.ID = m.post_id )
	           	INNER JOIN $wpdb->postmeta AS mt1 ON ( p.ID = mt1.post_id ) WHERE 1=1 AND
	           		( ( m.meta_key = %s AND CAST(m.meta_value AS DATE) = %s ) AND( mt1.meta_key = %s ) )
	           		AND p.post_type = %s AND
	           		((p.post_status = 'publish'))
	           		 GROUP BY mt1.meta_value",
	                'qms4__event_date',
		         	$ymd,
		         	'qms4__parent_event_id',
		         	'fair__schedule'
		         );
	        global $wpdb;
	        $result = $wpdb->get_results($sql, ARRAY_A);
	        $event_ids = array(-1);
	        foreach($result as $id){
	        	$event_ids[] = $id['parent_id'];
	        }

			$query->set('post__in',$event_ids);

		}
	}

}
add_action( 'pre_get_posts', 'filter_events_by_select_date', 9999);


/**
 * @param    int    $event_id
 * @param
 * @return    String - Date
 */
function qms4_get_event_date($event_id)
{

	global $wpdb;
	$sql = $wpdb->prepare("
		SELECT SQL_CALC_FOUND_ROWS p.ID, m.meta_value as event_date FROM $wpdb->posts p
			INNER JOIN $wpdb->postmeta m
			ON ( p.ID = m.post_id )
				INNER JOIN $wpdb->postmeta AS mt1
				ON ( p.ID = mt1.post_id ) WHERE 1=1 AND ( ( m.meta_key = 'qms4__event_date' AND CAST( m.meta_value AS DATE) >= CURDATE() ) AND
					( mt1.meta_key = 'qms4__parent_event_id' AND mt1.meta_value = %d ) ) AND
					 p.post_type = 'fair__schedule' AND
					  ((p.post_status = 'publish'))

					  GROUP BY p.ID, m.meta_value
					  ORDER BY m.meta_value ASC
					  LIMIT 0, 1", $event_id);

	$result  = $wpdb->get_results($sql, ARRAY_A);
	if($result){
		return $result[0]['event_date'];
	} else {
		$sql = $wpdb->prepare("
		SELECT SQL_CALC_FOUND_ROWS p.ID, m.meta_value as event_date FROM $wpdb->posts p
			INNER JOIN $wpdb->postmeta m
			ON ( p.ID = m.post_id )
				INNER JOIN $wpdb->postmeta AS mt1
				ON ( p.ID = mt1.post_id ) WHERE 1=1 AND (
					( mt1.meta_key = 'qms4__parent_event_id' AND mt1.meta_value = %d ) ) AND
					 p.post_type = 'fair__schedule' AND
					  ((p.post_status = 'publish'))
					  GROUP BY p.ID, m.meta_value
					  ORDER BY m.meta_value DESC
					  LIMIT 0, 1", $event_id);


		$result  = $wpdb->get_results($sql, ARRAY_A);

		return ($result) ? $result[0]['event_date'] : 0;
	}
}

/**  $date ('Y-m-d')
 * check date is valid or not
 * @return true or false
 */
function is_valid_date($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

/**
 * show event relate title in the list fair_schedule for amdin check.
 **/
function add_event_to_schedule_title($title)
{
	global $post;
	if( $post->post_type == 'fair__schedule' && is_admin() ){

		$event_id = get_post_meta($post->ID,'qms4__parent_event_id', true);
		$event = get_post($event_id);
		if($event){
			$title = $title.=' |'.$event->post_title;
		}
	}
	return $title;

}
add_filter('the_title','add_event_to_schedule_title');


function admin_inline_js(){ ?>
	<script type="text/javascript">

		(function($){
			$(document).ready(function(){

				$(".btn_qms4_show_full_schedules").click(function(event) {
					console.log('click btn_qms4_show_full_schedules');
					$(this).closest('ul').find('li').show();
					$(this).hide();
				});
			})
		})(jQuery)
	</script>
   <?php
}
add_action( 'admin_print_scripts', 'admin_inline_js',999 );