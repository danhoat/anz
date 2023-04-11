<?php

namespace QMS4\Event\CalendarMonth;


class DayOfWeek
{
	const SUNDAY = 0;
	const MONDAY = 1;
	const TUESDAY = 2;
	const WEDNESDAY = 3;
	const THURSDAY = 4;
	const FRIDAY = 5;
	const SATURDAY = 6;

	/** @var    int */
	private $dow;

	/**
	 * @param    int    $dow
	 */
	private function __construct( int $dow )
	{
		if ( ! in_array( $dow, array( 0, 1, 2, 3, 4, 5, 6 ), true ) ) {
			throw new \InvalidArgumentException();
		}

		$this->dow = $dow;
	}

	/**
	 * @param    \DateTimeInterface    $datetime
	 * @return    self
	 */
	public static function from_datetime( \DateTimeInterface $datetime ): DayOfWeek
	{
		$dow = (int) $datetime->format( 'w' );

		return new self( $dow );
	}

	/**
	 * @param    int    $dow
	 * @return    self
	 */
	public static function from_week( int $dow ): DayOfWeek
	{
		if ( ! in_array( $dow, array( 0, 1, 2, 3, 4, 5, 6 ), true ) ) {
			throw new \InvalidArgumentException();
		}

		return new self( $dow );
	}

	/**
	 * @param    int    $dow
	 * @return    self
	 */
	public static function from_iso_week( int $dow ): DayOfWeek
	{
		if ( ! in_array( $dow, array( 1, 2, 3, 4, 5, 6, 7 ), true ) ) {
			throw new \InvalidArgumentException();
		}

		return new self( $dow === 7 ? 0 : $dow );
	}

	/**
	 * @return    self
	 */
	public static function sunday(): DayOfWeek
	{
		return new self( self::SUNDAY );
	}

	/**
	 * @return    self
	 */
	public static function monday(): DayOfWeek
	{
		return new self( self::MONDAY );
	}

	/**
	 * @return    self
	 */
	public static function tuesday(): DayOfWeek
	{
		return new self( self::TUESDAY );
	}

	/**
	 * @return    self
	 */
	public static function wednesday(): DayOfWeek
	{
		return new self( self::WEDNESDAY );
	}

	/**
	 * @return    self
	 */
	public static function thursday(): DayOfWeek
	{
		return new self( self::THURSDAY );
	}

	/**
	 * @return    self
	 */
	public static function friday(): DayOfWeek
	{
		return new self( self::FRIDAY );
	}

	/**
	 * @return    self
	 */
	public static function saturday(): DayOfWeek
	{
		return new self( self::SATURDAY );
	}

	// ====================================================================== //

	/**
	 * @return    int    0 = Sunday, ..., 6 = Saturday
	 */
	public function week(): int
	{
		return $this->dow;
	}

	/**
	 * @return    int    1 = Monday, ..., 7 = Sunday
	 */
	public function iso_week(): int
	{
		return $this->dow === 0 ? 7 : $this->dow;
	}

	// ====================================================================== //

	/**
	 * @return    bool
	 */
	public function is_sunday(): bool
	{
		return $this->dow === self::SUNDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_monday(): bool
	{
		return $this->dow === self::MONDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_tuesday(): bool
	{
		return $this->dow === self::TUESDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_wednesday(): bool
	{
		return $this->dow === self::WEDNESDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_thursday(): bool
	{
		return $this->dow === self::THURSDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_friday(): bool
	{
		return $this->dow === self::FRIDAY;
	}

	/**
	 * @return    bool
	 */
	public function is_saturday(): bool
	{
		return $this->dow === self::SATURDAY;
	}

	// ====================================================================== //

	public function is_same( self $other ): bool
	{
		return $this->dow === $other->dow;
	}

	// ====================================================================== //

	/**
	 * @param    string    $format_str   'D'|'N'|'w'
	 * @return    string
	 */
	public function format( string $format_str ): string
	{
		switch ( $format_str ) {
			case 'D': return $this->_format();
			case 'N': return (string) ( $this->dow === 0 ? 7 : $this->dow );
			case 'w': return (string) $this->dow;
			default: throw new \InvalidArgumentException();
		}
	}

	/**
	 * @return    string    'Sun'|'Mon'|'Tue'|'Wed'|'Thu'|'Fri'|'Sat'
	 */
	private function _format(): string
	{
		$map = array(
			0 => 'Sun',
			1 => 'Mon',
			2 => 'Tue',
			3 => 'Wed',
			4 => 'Thu',
			5 => 'Fri',
			6 => 'Sat',
		);

		return $map[ $this->dow ];
	}

	// ====================================================================== //

	/**
	 * @return self
	 */
	public function previous(): DayOfWeek
	{
		return new self( $this->dow === 0 ? 6 : $this->dow - 1 );
	}

	/**
	 * @return self
	 */
	public function next(): DayOfWeek
	{
		return new self( $this->dow === 6 ? 0 : $this->dow + 1 );
	}
}
