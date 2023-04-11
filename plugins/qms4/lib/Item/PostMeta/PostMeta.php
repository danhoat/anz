<?php

namespace QMS4\Item\PostMeta;


class PostMeta
{
	/** @var    mixed */
	private $_value;

	/**
	 * @param    mixed    $value
	 */
	public function __construct( $value )
	{
		$this->_value = $value;
	}

	/**
	 * @param    array<string|mixed>    $args
	 */
	public function invoke( array $args )
	{
		return $this->_value;
	}
}
