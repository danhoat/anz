<?php

namespace QMS4\Item\Util;


class Date extends \DateTimeImmutable
{
	/** @var    string */
	private $_date_format;

	/**
	 * @param    string    $datetime
	 * @param    \DateTimeZone|string    $timezone
	 * @param    string    $date_format
	 */
	public function __construct(
		string $datetime = 'now',
		$timezone = null,
		string $date_format = 'Y/m/d'
	)
	{
		if ( $timezone !== null ) {
			$timezone = $timezone instanceof \DateTimeZone
				? $timezone
				: new \DateTimeZone( $timezone );
		}

		parent::__construct( $datetime, $timezone );

		$this->_date_format = $date_format;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return wp_date( $this->_date_format, $this->getTimestamp() );
	}

	/**
	 * @param    string|null    $date_format
	 * @return    string
	 */
	public function __invoke( ?string $date_format = null ): string
	{
		$date_format = empty( $date_format ) ? $this->_date_format : $date_format;

		return $this->format( $date_format );
	}

	/**
	 * FIXME: この設定、効いてない？
	 *
	 * @param    array<string,mixed>
	 */
	public function __debugInfo(): array
	{
		$datetime_str = $this->format( 'Y-m-d H:i:s' );

		$timezone = $this->getTimezone();
		$timezone_str = $timezone ? $timezone->getName() : null;

		return array(
			"\0" . self::class . "\0datetime" => $datetime_str,
			"\0" . self::class . "\0timezone" => $timezone_str,
		);
	}

	// ====================================================================== //

	/**
	 * @param    string    $format
	 * @return    string
	 */
	public function format( $format ): string
	{
		$date_str = wp_date( $format, $this->getTimestamp() );

		return str_replace(
			array( 'J-Sun', 'J-Mon', 'J-Tue', 'J-Wed', 'J-Thu', 'J-Fri', 'J-Sat' ),
			array( '日', '月', '火', '水', '木', '金', '土'),
			$date_str
		);
	}
}
