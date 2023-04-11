<?php

namespace QMS4\Action\RestApi;

use QMS4\Event\BorderDate;
use QMS4\Event\BorderDateFactory;
use QMS4\Event\CalendarMonth\CalendarTerm;
use QMS4\Event\CalendarMonth\DateClassFormatterFactory;
use QMS4\Event\CalendarMonth\DayOfWeek;
use QMS4\Event\CalendarMonth\ScheduleFormatter;
use QMS4\Fetch\EventCalendar as FetchEventCalendar;


class RegisterEventCalendarRoute
{
	public function __invoke()
	{
		$call_back = 'get';

		register_rest_route(
			'qms4/v1',
			'/event/calendar/(?P<post_type>[a-z0-9_\\-]+)/(?P<year>\\d{1,4})/(?P<month>\\d{1,2})/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, $call_back ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'qms4/custom',
			'/event/calendar/(?P<post_type>[a-z0-9_\\-]+)/(?P<year>\\d{1,4})/(?P<month>\\d{1,2})/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get_default' ),
				'permission_callback' => '__return_true',
			)
		);


	}

	/**
	 * @param    \WP_REST_Request    $request
	 * @return    \WP_REST_Response|\WP_Error
	 */
	public function get( \WP_REST_Request $request )
	{
		$param  = $request->get_params();
		$style = isset($param['style'] ) ?  $param['style'] :  '1month';


		if( $style == '2months')
			return $this->get_custom($request);

		return $this->get_default($request);
	}
	function get_default(\WP_REST_Request $request){
		$param = $request->get_params();


		$validation_result = $this->validate( $param );
		if ( is_wp_error( $validation_result ) ) {
			return $validation_result;
		}

		$post_type = $param[ 'post_type' ];
		$year = $param[ 'year' ];
		$month = $param[ 'month' ];

		$start_of_week = DayOfWeek::from_week( get_option( 'start_of_week', DayOfWeek::MONDAY ) );

		$factory = new BorderDateFactory();
		$border_date = $factory->from_post_type( $post_type );

		$calendar_term = CalendarTerm::from_year_month( $start_of_week, $year, $month );

		$event_calendar = new FetchEventCalendar( $post_type );
		$calendar_month = $event_calendar->fetch( $calendar_term, array(
			'area' => empty( $_GET[ 'area' ] ) ? null : $_GET[ 'area' ],
		) );
		$calendar_month->set_border_date( $border_date );  // TODO: セッターでやるのやめたい

		$factory = new DateClassFormatterFactory( 'qms4__block__event-calendar__body-cell--' );
		$date_class_formatter = $factory->create( $post_type, $calendar_term );

		$schedule_formatter = new ScheduleFormatter(
			empty( $_GET[ 'fields' ] ) ? array() : $_GET[ 'fields' ]
		);

		return new \WP_REST_Response(
			$calendar_month->to_array( $date_class_formatter, $schedule_formatter ),
			200
		);
	}
	function get_custom($request){
		$param = $request->get_params();

		$validation_result = $this->validate( $param );
		if ( is_wp_error( $validation_result ) ) {
			return $validation_result;
		}

		$post_type = $param[ 'post_type' ];
		$year 		= $param[ 'year' ];
		$month 		= $param[ 'month' ];


		$ymd = $year.'-'.$month.'-01';

		$event = isset($param['event']) ? $param['event']: '';


		$start_of_week = DayOfWeek::from_week( get_option( 'start_of_week', DayOfWeek::MONDAY ) );

		$factory = new BorderDateFactory();

		//$border_date = $factory->fist_date_of_next_2month( );
		$cur_date = $year.'-0'.$month.'-01';

		$border_date = $factory->fist_date_of_left_month($ymd);

		$calendar_term = CalendarTerm::from_base_date( $start_of_week, $border_date->date() );
		$event_calendar = new FetchEventCalendar( $post_type );

		$calendar_month = $event_calendar->fetch( $calendar_term, array(
			'area' => empty( $_GET[ 'area' ] ) ? null : $_GET[ 'area' ],
		) );

		$calendar_month->set_border_date( $border_date );  // TODO: セッターでやるのやめたい


		$factory = new DateClassFormatterFactory( 'qms4__block__event-calendar__body-cell--' );
		$date_class_formatter = $factory->create( $post_type, $calendar_term );

		$schedule_formatter = new ScheduleFormatter(
			empty( $_GET[ 'fields' ] ) ? array() : $_GET[ 'fields' ]
		);

		$calendar_month =  new \WP_REST_Response(
			$calendar_month->to_array( $date_class_formatter, $schedule_formatter ),
			200
		);

		$factory_n = new BorderDateFactory();

		$border_date_right = $factory_n->fist_date_of_right_month($ymd );

		if( $event == 'prev'){
			// $border_date_event = $factory_n->frist_prev_month_date( );
		}

		$calendar_term = CalendarTerm::from_base_date( $start_of_week, $border_date_right->date() );

		$event_calendar = new FetchEventCalendar( $post_type );

		$calendar_next_month = $event_calendar->fetch( $calendar_term, array(
			'area' => empty( $_GET[ 'area' ] ) ? null : $_GET[ 'area' ],
		) );

		$calendar_next_month->set_border_date( $border_date_right );  // TODO: セッターでやるのやめたい


		$factory = new DateClassFormatterFactory( 'qms4__block__event-calendar__body-cell--', $border_date_right );
		$date_class_formatter = $factory->create( $post_type, $calendar_term );

		$schedule_formatter = new ScheduleFormatter(
			empty( $_GET[ 'fields' ] ) ? array() : $_GET[ 'fields' ]
		);

		$calendar_next_month =  new \WP_REST_Response(
			$calendar_next_month->to_array( $date_class_formatter, $schedule_formatter ),
			200
		);

		return array($calendar_month, $calendar_next_month);
	}


	/**
	 * @param    array<string,mixed>    $param
	 * @return    true|\WP_Error
	 */
	private function validate( array $param )
	{
		$post_type_object = get_post_type_object( $param[ 'post_type' ] );

		if ( is_null( $post_type_object ) || ! $post_type_object->public ) {
			return new \WP_Error(
				'unknown_post_type',
				"Unknown post_type: \$post_type: {$param['post_type']}",
				array( 'status' => 400 )
			);
		}

		if (
			! is_numeric( $param[ 'month' ] )
			|| $param[ 'month' ] < 1
			|| $param[ 'month' ] > 12
		) {
			return new \WP_Error(
				'invalid_month',
				"Invalid month: \$month: {$param['month']}",
				array( 'status' => 400 )
			);
		}

		return true;
	}
}
