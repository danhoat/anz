<?php

namespace QMS4\Item\Util;



class Generate
{
	/** @var    object */
	private $object;

	/** @var    string */
	private $memo;

	/** @var    string */
	private $generate;

	/** @var    string */
	private $method;

	/**
	 * @param    mixed    $object
	 * @param    string    $memo
	 * @param    string    $method
	 */
	public function __construct(
		$object,
		string $memo,
		string $generate,
		string $method
	)
	{
		$this->object = $object;
		$this->memo = $memo;
		$this->generate = $generate;
		$this->method = $method;
	}

	/**
	 * @return    mixed
	 */
	public function invoke()
	{
		$method = new \ReflectionMethod( $this->object, $this->method );
		$method->setAccessible( true );


		$params = $method->getParameters();
		if ( empty( $params ) ) { return $method->invoke( $this->object ); }


		$class = new \ReflectionClass( $this->object );
		$memo = new \ReflectionProperty( $this->object, $this->memo );
		$memo->setAccessible( true );

		$args = array();
		foreach ( $params as $param ) {
			$param_name = $param->getName();

			if ( ! $class->hasMethod( "_{$param_name}" ) ) {
				throw new \LogicException();
			}

			$memo = $memo->getValue( $this->object );
			if ( array_key_exists( $param_name, $memo ) ) {
				$args[] = $memo[ $param_name ];
			} else {
				$generate = new \ReflectionMethod( $this->object, $this->generate );
				$generate->setAccessible( true );
				$args[] = $generate->invoke( $this->object, "_{$this->param_name}" );
			}
		}

		return $method->invoke( $this->object, ...$args );
	}
}
