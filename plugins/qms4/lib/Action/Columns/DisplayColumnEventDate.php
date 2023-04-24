<?php

namespace QMS4\Action\Columns;

use QMS4\Event\BorderDate;
use QMS4\Event\BorderDateFactory;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostTypeMeta\PostTypeMeta;


class DisplayColumnEventDate
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	/** @var    BorderDate */
	private $border_date;

	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;

		$factory = new BorderDateFactory();
		$this->border_date = $factory->from_post_type( $post_type_meta->name() );
	}

	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__event_date' ) { return; }

		$query = new \WP_Query( array(
			'post_type' => "{$this->post_type_meta->name()}__schedule",
			'post_status' => 'publish',
			'orderby' => 'meta_value_date',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => ParentEventId::KEY,
					'value' => $post_id,
				),
				array(
					'key' => EventDate::KEY,
					'value' => $this->border_date->format( 'Y-m-d' ),
					'compare' => '>=',
					'type' => 'DATE',
				),
			),
			'posts_per_page' => 999999,
			'fields' => 'ids',
		) );

		if ( ! $query->found_posts ) {
			echo "<p>（日程無し）</p>";
			return;
		}

		$dates = array();
		foreach ( $query->posts as $schedule_id ) {
			$dates[ $schedule_id ] = EventDate::get_post_meta( $schedule_id );
		}

		asort( $dates );

		$lis = array();
		$i = 1;
		$class_css = '';
		$total = count($dates);
		foreach ( $dates as $schedule_id => $date ) {
			$class_css = '';
			if($i > 10 && $i < $total) $class_css = 'style= "display:none" ';

			$date = EventDate::get_post_meta( $schedule_id );
			$lis[] = '<li  item-'.$i.' '.$class_css.'><a href="' . admin_url( "/post.php?post={$schedule_id}&action=edit" ) . '">'
				. wp_date( 'Y年n月j日（D）', $date->getTimestamp() )
				. '</a></li>';

			if( $i == $total -1 && $total > 10 ){
				$number_hidden = $total - 11;
				$lis[] = '<li  class="btn_qms4_show_full_schedules"> ('.$number_hidden.'  件省略 ... )</li>';

			}

			$i++;
		}


		echo '<ul>' . join( '', $lis ) . '</ul>';
	}
}
