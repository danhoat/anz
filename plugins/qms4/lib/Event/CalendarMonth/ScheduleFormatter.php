<?php

namespace QMS4\Event\CalendarMonth;

use QMS4\Item\Post\Schedule;


/**
 * TODO: $fields の扱いをもう少し整理する
 */
class ScheduleFormatter
{
	/** @var    array<string,mixed> */
	private $fields;

	/**
	 * @param    array<string,mixed>    $fields
	 */
	public function __construct( array $fields = array() )
	{
		$this->fields = $fields;
	}

	/**
	 * @return    array<string,mixed>
	 */
	public function format( Schedule $schedule ): array
	{
		$data = array(
			'id' => $schedule->id,
			'permalink' => $schedule->permalink,
			'title' => $schedule->title,
			'img' => (string) $schedule->img,
		);

		if ( ! empty( $this->fields[ 'area' ] ) ) {
			$area = $schedule->event->area;
			$data[ 'area' ] = isset( $area ) ? $area->title : null ;
		}

		if ( ! empty( $this->fields[ 'taxonomies' ] ) ) {
			$taxonomies = explode( ',', $this->fields[ 'taxonomies' ] );
			$taxonomies = array_filter( array_map( 'trim', $taxonomies ) );

			$data[ 'terms' ] = array();
			foreach ( $taxonomies as $taxonomy ) {
				$terms = array();
				foreach ( $schedule->event->$taxonomy as $term ) {
					$terms[] = array(
						'name' => $term->name,
						'slug' => $term->slug,
						'color' => $term->color,
					);
				}

				$data[ 'terms' ][ $taxonomy ] = $terms;
			}
		}

		return $data;
	}
}
