<?php

namespace QMS4\Collection;

use QMS4\Util\DOW;


class CalendarMonth
{
	/** @var    \DateTimeInterface */
	private $base_date;

	/** @var    DOW */
	private $start_of_week;

	/** @var    \WP_Post[] */
	private $wp_posts;

	/**
	 * @param    \DateTimeInterface    $base_date
	 * @param    DOW    $base_date
	 * @param    \WP_Post[]    $wp_posts
	 */
	public function __construct(
		\DateTimeInterface $base_date,
		DOW $start_of_week,
		array $wp_posts
	)
	{
		$this->base_date = $base_date;
		$this->start_of_week = $start_of_week;
		$this->wp_posts = $wp_posts;
	}


}
