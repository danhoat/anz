<?php

namespace QMS4\Block;

use QMS4\Event\BorderDate;
use QMS4\Event\BorderDateFactory;
use QMS4\Event\CalendarMonth\CalendarTerm;
use QMS4\Event\CalendarMonth\DateClassFormatterFactory;
use QMS4\Event\CalendarMonth\DayOfWeek;
use QMS4\Fetch\EventCalendar as FetchEventCalendar;


class EventCalendar
{
	/** @var    string */
	private $name = 'event-calendar';

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
		$show_area = $attributes[ 'showArea' ];
		$show_terms = $attributes[ 'showTerms' ];
		$taxonomies = $attributes[ 'taxonomies' ];
		$link_target = $attributes[ 'linkTarget' ];

		$start_of_week = DayOfWeek::from_week( get_option( 'start_of_week', DayOfWeek::MONDAY ) );

		$factory = new BorderDateFactory();
		$border_date = $factory->from_post_type( $post_type );


		$calendar_term = CalendarTerm::from_base_date( $start_of_week, $border_date->date() );

		$base_date = $border_date->date();

		$event_calendar = new FetchEventCalendar( $post_type );
		$calendar_month = $event_calendar->fetch(
			$calendar_term,
			array(
				'area' => empty( $_GET[ 'area' ] ) ? null : $_GET[ 'area' ],
			)
		);



		$calendar_month->set_border_date( $border_date );  // TODO: セッターでやるのやめたい
		$recent_enable_date = $calendar_month->recent_enable_date( $base_date );

		$factory = new DateClassFormatterFactory( 'qms4__block__event-calendar__body-cell--' );
		$date_class = $factory->create( $post_type, $calendar_term );

		$query_string = parse_url( $_SERVER[ 'REQUEST_URI' ] , PHP_URL_QUERY );

		ob_start();


		if ( $show_posts ) {
			require( QMS4_DIR . '/blocks/templates/event-calendar__show_posts__true.php' );
		} else {


			require( QMS4_DIR . '/blocks/templates/event_calendar-header.php' );
			require( QMS4_DIR . '/blocks/templates/event-calendar__show_posts__false.php' );
		}

		// block 222;


		$start_of_week = DayOfWeek::from_week( get_option( 'start_of_week', DayOfWeek::MONDAY ) );


		$factory_n = new BorderDateFactory();
		$border_date = $factory_n->fist_date_of_next_month( );


		$calendar_term = CalendarTerm::from_base_date( $start_of_week, $border_date->date() );

		$base_date = $border_date->date();

		$event_calendar_n = new FetchEventCalendar( $post_type );
		$calendar_month_n = $event_calendar_n->fetch(
			$calendar_term,
			array(
				'area' => empty( $_GET[ 'area' ] ) ? null : $_GET[ 'area' ],
			)
		);

		$calendar_month_n->set_border_date( $border_date );  // TODO: セッターでやるのやめたい
		$recent_enable_date = $calendar_month_n->recent_enable_date( $base_date );

		$factory_n = new DateClassFormatterFactory( 'qms4__block__event-calendar__body-cell--' );
		$date_class = $factory_n->create( $post_type, $calendar_term );

		$query_string = parse_url( $_SERVER[ 'REQUEST_URI' ] , PHP_URL_QUERY );



		if ( $show_posts ) {
			require( QMS4_DIR . '/blocks/templates/event-calendar__show_posts__true.php' );
		} else {
			require( QMS4_DIR . '/blocks/templates/event-calendar__show_posts__false_next.php' );
			echo '</div>';
		}
		return ob_get_clean();
	}
}
