<?php

namespace QMS4\Event\CalendarMonth;

use QMS4\Event\BorderDate;
use QMS4\Event\BorderDateFactory;
use QMS4\Event\CalendarMonth\CalendarTerm;
use QMS4\Event\CalendarMonth\DateClassFormatter;


class DateClassFormatterFactory
{
	/** @var    string */
	private $prefix;

	/**
	 * @param    string    $prefix
	 */
	public function __construct( string $prefix )
	{
		$this->prefix = $prefix;
	}

	/**
	 * @param    string    $post_type
	 * @param    CalendarTerm    $calendar_term
	 * @return    DateClassFormatter
	 */
	public function create(
		string $post_type,
		CalendarTerm $calendar_term
	): DateClassFormatter
	{
		$factory = new BorderDateFactory();

		return new DateClassFormatter(
			current_datetime(),
			$factory->from_post_type( $post_type ),
			$calendar_term,
			$this->prefix
		);
	}
}
