<?php

namespace QMS4\Item\Util;

use QMS4\Param\Param;


class Compute
{
	/** @var    object */
	private $object;

	/** @var    \ReflectionMethod */
	private $method;

	/** @var    array<int,string> */
	private $param_names;

	/** @var    array<string,mixed> */
	private $args;

	public function __construct( $object, string $method )
	{
		$this->object = $object;
		$this->method = new \ReflectionMethod( $object, $method );

		$params = $this->method->getParameters();
		array_shift( $params );

		$param_names = array();
		$args = array();
		foreach ( $params as $param ) {
			$param_name = $param->getName();

			$param_names[] = $param_name;
			$args[ $param_name ] = $param->isDefaultValueAvailable()
				? $param->getDefaultValue()
				: null;
		}

		$this->param_names = $param_names;
		$this->args = $args;
	}

	/**
	 * @param    Param    $param
	 * @return    $this
	 */
	public function bind( Param $param ): self
	{
		$new_args = array();
		foreach ( $this->param_names as $param_name ) {
			$new_args[ $param_name ] = isset( $param[ $param_name ] )
				? $param[ $param_name ]
				: $this->args[ $param_name ];
		}

		$this->args = $new_args;

		return $this;
	}

	/**
	 * @param    mixed    $value
	 * @param    mixed[]    $args
	 * @return    mixed
	 */
	public function invoke( $value, array $args = array() )
	{
		$new_args = array();
		foreach ( $this->param_names as $position => $param_name ) {
			if ( isset( $args[ $param_name ] ) ) {
				$new_args[] = $args[ $param_name ];
			} elseif ( isset( $args[ $position ] ) ) {
				$new_args[] = $args[ $position ];
			} else {
				$new_args[] = $this->args[ $param_name ];
			}
		}

		$this->method->setAccessible( true );

		return $this->method->invoke( $this->object, $value, ...$new_args );
	}
}
