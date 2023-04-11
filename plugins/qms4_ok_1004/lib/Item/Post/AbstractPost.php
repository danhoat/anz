<?php

namespace QMS4\Item\Post;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\Term\Terms;
use QMS4\Item\Term\TermsFactory;
use QMS4\Item\PostMeta\PostMeta;
use QMS4\Item\PostMeta\PostMetaFactory;
use QMS4\Item\Util\PropProxyFailedException;
use QMS4\Param\Param;


abstract class AbstractPost
{
	/** @var    \WP_Post */
	protected $_wp_post;

	/** @var    Param */
	protected $_param;

	/** @var    array<string,mixed> */
	protected $_memo = array();

	/**
	 * @param    \WP_Post    $wp_post
	 * @param    Param    $param
	 */
	public function __construct( \WP_Post $wp_post, Param $param )
	{
		$this->_wp_post = $wp_post;
		$this->_param = $param;
	}

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	public function __get( $name )
	{
		return $this->__call( $name, array() );
	}

	/**
	 * @param    string    $name
	 * @param    array    $args
	 * @return    mixed
	 */
	public function __call( string $name, array $args )
	{
		try {
			$value = $this->___proxy( $name );
			return $value->invoke( $args );
		}
		catch ( PropProxyFailedException $e )
		{ /* continue */ }

		$generator = "_{$name}";
		$computer = "__{$name}";

		if ( method_exists( $this, $computer ) ) {
			list( $values, $options ) = $this->___preprocess( $computer );
			return $this->___compute( $computer, $values, $options, $args );
		} elseif ( array_key_exists( $name, $this->_memo ) ) {
			return $this->_memo[ $name ];
		} elseif ( method_exists( $this, $generator ) ) {
			return $this->_memo[ $name ] = $this->$generator();
		}
	}

	// ====================================================================== //

	/**
	 * @param    string    $name
	 * @return    AcfInterface|PostMeta|Terms
	 * @throws    ItemProxyFailed
	 */
	private function ___proxy( string $name )
	{
		if (
			array_key_exists( $name, $this->_memo )
			&& (
				$this->_memo[ $name ] instanceof AcfInterface
				|| $this->_memo[ $name ] instanceof PostMeta
				|| $this->_memo[ $name ] instanceof Terms
			)
		) {
			return $this->_memo[ $name ];
		}

		if (
			function_exists( 'get_field_object' )
			&& ( $field_object = get_field_object( $name, $this->_wp_post, false ) )
		) {
			$factory = new AcfFactory();
			return $this->_memo[ $name ]
				= $factory->from_field_object( $field_object, $this->_param );
		}


		if ( registered_meta_key_exists( $this->_wp_post->post_type, $name ) ) {
			$factory = new PostMetaFactory();
			return $this->_memo[ $name ]
				= $factory->from_name( $this->_wp_post, $name );
		}


		$taxonomy = "{$this->_wp_post->post_type}__{$name}";
		if ( taxonomy_exists( $taxonomy ) ) {
			$factory = new TermsFactory();
			return $this->_memo[ $name ]
				= $factory->from_taxonomy( $this->_wp_post, $taxonomy, $this->_param );
		}

		if ( taxonomy_exists( $name ) ) {
			$factory = new TermsFactory();
			return $this->_memo[ $name ]
				= $factory->from_taxonomy( $this->_wp_post, $name, $this->_param );
		}

		throw new PropProxyFailedException();
	}

	/**
	 * @param    string    $computer
	 * @return    array[]
	 */
	private function ___preprocess( string $computer ): array
	{
		$ref_method = new \ReflectionMethod( $this, $computer );
		$ref_params = $ref_method->getParameters();

		$value_names = array();
		$options = array();
		foreach ( $ref_params as $ref_param ) {
			$param_name = $ref_param->getName();

			try {
				$option_value = $ref_param->getDefaultValue();
				$options[ $param_name ] = $option_value;
			}
			catch ( \ReflectionException $e ) {
				$value_names[] = $param_name;
				continue;
			}
		}

		$values = array();
		foreach ( $value_names as $value_name ) {
			if ( array_key_exists( $value_name, $this->_memo ) ) {
				$values[ $value_name ] = $this->_memo[ $value_name ];
			} else {
				$generator = "_{$value_name}";
				$values[ $value_name ] = method_exists( $this, $generator )
					? ( $this->_memo[ $value_name ] = $this->$generator() )
					: $this->_wp_post->$value_name;
			}
		}

		return array( $values, $options );
	}

	/**
	 * @param    string    $computer
	 * @param    array<string,mixed>    $values
	 * @param    array<string,mixed>    $options
	 * @param    array<string,mixed>    $args
	 * @return    mixed
	 */
	private function ___compute(
		string $computer,
		array $values,
		array $options,
		array $args
	)
	{
		$values = array_values( $values );

		$option_names = array_keys( $options );
		$new_args = array();
		foreach ( $option_names as $position => $option_name ) {
			if ( isset( $args[ $option_name ] ) ) {
				$new_args[] = $args[ $option_name ];
			} elseif ( isset( $args[ $position ] ) ) {
				$new_args[] = $args[ $position ];
			} elseif ( $this->_param[ $option_name ] ) {
				$new_args[] = $this->_param[ $option_name ];
			} else {
				$new_args[] = $options[ $option_name ];
			}
		}

		return $this->$computer( ...$values, ...$new_args );
	}
}
