<?php

namespace QMS4\Event;


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

	// ====================================================================== //

	/**
	 * @return    \DateTimeImmutable
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
		return wp_date( $format, $this->date->getTimestamp() );
	}
}
