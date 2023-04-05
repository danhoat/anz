<?php

namespace QMS4\CalendarMonth;

use QMS4\CalendarMonth\BorderDate;
use QMS4\CalendarMonth\CalendarTerm;
use QMS4\CalendarMonth\DateClassFormatter;
use QMS4\PostTypeMeta\PostTypeMetaFactory;


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
		return new DateClassFormatter(
			current_datetime(),
			BorderDate::from_post_type( $post_type ),
			$calendar_term,
			$this->prefix
		);
	}
}
