<?php

namespace QMS4\Item\Util;

use QMS4\Param\Param;


class NoDate implements \DateTimeInterface
{
	/** @var    \DateTimeZone */
	private $timezone;

	/** @var    string */
	private $_date_format;

	/**
	 * @param    \DateTimeZone|string    $timezone
	 * @param    string    $date_format
	 */
	public function __construct( $timezone = null, string $date_format )
	{
		if ( $timezone !== null ) {
			$timezone = $timezone instanceof \DateTimeZone
				? $timezone
				: new \DateTimeZone( $timezone );
		}
		$this->timezone = $timezone;

		$this->_date_format = $date_format;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return '';
	}

	// ====================================================================== //

	/**
	 * @param    \DateTimeInterface    $target_object
	 * @param    bool    $absolute
	 * @return    \DateInterval
	 */
	public function diff(
		\DateTimeInterface $targetObject,
		bool $absolute = false
	): \DateInterval
	{
		return new \DateInterval( 'P0S' );
	}

	/**
	 * @param    string    $format
	 * @return    string
	 */
	public function format( string $format ): string
	{
		return '';
	}

	/**
	 * @return    int
	 */
	public function getOffset(): int
	{
		return $this->timezone->getOffset( new \DateTime() );
	}

	/**
	 * @return    int|false
	 */
	public function getTimestamp(): int
	{
		return false;
	}

	/**
	 * @return    \DateTimeZone
	 */
	public function getTimezone(): \DateTimeZone
	{
		return $this->timezone;
	}

	/**
	 * @return    void
	 */
	public function __wakeup(): void
	{
	}
}
