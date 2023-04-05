<?php

namespace QMS4\Block;

use QMS4\Fetch\EventSchedules;
use QMS4\PostTypeMeta\PostTypeMetaFactory;

class EventCalendar
{
	/** @var    array<int,string> */
	const DOW = array(
		0 => 'sunday',
		1 => 'monday',
		2 => 'tuesday',
		3 => 'wednesday',
		4 => 'thursday',
		5 => 'friday',
		6 => 'saturday',
	);

	const COMPONENT_NAME = 'qms4__block__event-calendar';

	/** @var    string */
	private $name = 'event-calendar';

	/** @var string */
	private $today_str;

	/** @var string */
	private $base_date_str;

	/** @var string */
	private $start_of_month_str;

	/** @var string */
	private $end_of_month_str;

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param    array<string,mixed>    $attributes
	 * @param    string|null    $content
	 * @return    string
	 */
	public function render( array $attributes, ?string $content ): string
	{
		$post_type = $attributes[ 'postType' ];
		$show_posts = $attributes[ 'showPosts' ];
		$show_items = $attributes[ 'showItems' ];
		$link_format = $attributes[ 'linkFormat' ];
		$link_target = $attributes[ 'linkTarget' ];

		if ( empty( $post_type ) ) { return ''; }

		$factory = new PostTypeMetaFactory();
		$post_type_meta = $factory->from_name( array( $post_type ) );

		list( $start_of_term, $start_of_month, ,$end_of_term ) = $this->term(
			$post_type_meta->cal_base_date(),
			wp_timezone(),
			get_option( 'start_of_week', 1 )
		);

		$event_schedules = new EventSchedules( $post_type );
		$events = $event_schedules->fetch( $start_of_term, $end_of_term );

		ob_start();
		if ( $show_posts ) {
			require( QMS4_DIR . "/blocks/templates/{$this->name}__show_posts__true.php" );
		} else {
			require( QMS4_DIR . "/blocks/templates/{$this->name}__show_posts__false.php" );
		}
		return ob_get_clean();
	}

	/**
	 * @param    int    $cal_base_date
	 * @return    \DateTimeImmutable[]
	 */
	private function term(
		int $cal_base_date,
		\DateTimeZone $tz,
		int $start_of_week
	): array
	{
		$today = new \DateTimeImmutable( 'now', $tz );

		if ( $cal_base_date == 0 ) {
			$dase_date = $today;
		} elseif ( $cal_base_date > 0 ) {
			$interval = new \DateInterval( 'P' . $cal_base_date . 'D' );
			$dase_date = $today->add( $interval );
		} elseif ( $cal_base_date < 0 ) {
			$interval = new \DateInterval( 'P' . abs( $cal_base_date ) . 'D' );
			$dase_date = $today->sub( $interval );
		}

		$start_of_month = $dase_date->modify( 'first day of this month' );
		$start_of_term = $start_of_month->format( 'w' ) == $start_of_week
			? $start_of_month
			: $start_of_month->modify( 'previous ' . self::DOW[ $start_of_week ] );

		$end_of_month = $dase_date->modify( 'last day of this month' );
		$end_of_week = $start_of_week == 0 ? 6 : $start_of_week - 1;
		$end_of_term = $end_of_month->format( 'w' ) == $end_of_week
			? $end_of_month->modify( 'next day' )
			: $end_of_month->modify( 'next ' . self::DOW[ $start_of_week ] );

		$this->today_str = $today->format( 'Y-m-d' );
		$this->base_date_str = $dase_date->format( 'Y-m-d' );
		$this->start_of_month_str = $start_of_month->format( 'Y-m-d' );
		$this->end_of_month_str = $end_of_month->format( 'Y-m-d' );

		return array( $start_of_term, $start_of_month, $end_of_month, $end_of_term );
	}

	private function _( \DateTimeImmutable $date )
	{
		$name = self::COMPONENT_NAME;
		$date_str = $date->format( 'Y-m-d' );
		$dow = strtolower( $date->format( 'D' ) );
		$is_weekend = $dow == 'sat' || $dow == 'sun';

		$is_out_of_month_before = $date_str < $this->start_of_month_str;
		$is_out_of_month_after = $this->end_of_month_str < $date_str;

		return array_filter( array(
			$date_str < $this->today_str ? "{$name}__body-cell--past" : '',
			$date_str == $this->today_str ? "{$name}__body-cell--today" : '',
			$date_str > $this->today_str ? "{$name}__body-cell--future" : '',

			$date_str < $this->base_date_str ? "{$name}__body-cell--disable" : '',
			$date_str == $this->base_date_str ? "{$name}__body-cell--border-date" : '',
			$date_str >= $this->base_date_str ? "{$name}__body-cell--enable": '',

			"{$name}__body-cell--{$dow}",
			$is_weekend ? "{$name}__body-cell--weekend" : '',
			! $is_weekend ? "{$name}__body-cell--weekday" : '',

			$is_out_of_month_before || $is_out_of_month_after ? "{$name}__body-cell--out-of-month" : '',
			$is_out_of_month_before ? "{$name}__body-cell--out-of-month-before" : '',
			$is_out_of_month_after ? "{$name}__body-cell--out-of-month-after" : '',
			! $is_out_of_month_before && ! $is_out_of_month_after ? "{$name}__body-cell--in-month" : '',

			"{$name}__body-cell--year-{$date->format('Y')}",
			"{$name}__body-cell--month-{$date->format('m')}",
			"{$name}__body-cell--day-{$date->format('d')}",
		) );
	}

	/**
	 * @param    \DateTimeInterface    $left
	 * @param    \DateTimeInterface    $right
	 * @return    bool
	 */
	private function is_same_day( \DateTimeInterface $left, \DateTimeInterface $right ): bool
	{
		return $left->format( 'Y-m-d' ) == $right->format( 'Y-m-d' );
	}
}
