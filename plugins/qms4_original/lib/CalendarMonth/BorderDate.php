<?php

namespace QMS4\CalendarMonth;

use QMS4\PostTypeMeta\PostTypeMetaFactory;


class BorderDate
{
	/** @var    \DateTimeImmutable */
	private $date;

	/**
	 * @param    \DateTimeImmutable    $date
	 */
	public function __construct( \DateTimeImmutable $date )
	{
		$this->date = $date;
	}

	/**
	 * @param    string    $post_type
	 * @return    self
	 */
	public static function from_post_type( string $post_type ): BorderDate
	{
		$factory = new PostTypeMetaFactory();
		$post_type_meta = $factory->from_name( array( $post_type ) );

		$cal_base_date = $post_type_meta->cal_base_date();

		$today = current_datetime();

		if ( $cal_base_date == 0 ) {
			$border_date = $today;
		} elseif ( $cal_base_date > 0 ) {
			$interval = new \DateInterval( 'P' . $cal_base_date . 'D' );
			$border_date = $today->add( $interval );
		} else {
			$interval = new \DateInterval( 'P' . abs( $cal_base_date ) . 'D' );
			$border_date = $today->sub( $interval );
		}

		return new self( $border_date );
	}

	// ====================================================================== //

	/**
	 * @return    self
	 */
	public function date(): \DateTimeImmutable
	{
		return $this->date;
	}

	/**
	 * @param    string    $format
	 * @return    string
	 */
	public function format( string $format ): string
	{
		return $this->date->format( $format );
	}
}
