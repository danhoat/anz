<?php

namespace QMS4\Item\User;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\PostMeta\PostMeta;
use QMS4\Item\PostMeta\PostMetaFactory;
use QMS4\Item\Util\Compute;
use QMS4\Item\Util\Date;
use QMS4\Param\Param;


class User
{
	/** @var    \WP_User */
	protected $_wp_user;

	/** @var    Param */
	protected $_param;

	/** @var    array<string,mixed> */
	protected $_memo = array();

	/**
	 * @param    \WP_User    $wp_user
	 * @param    Param    $param
	 */
	public function __construct( \WP_User $wp_user, Param $param )
	{
		$this->_wp_user = $wp_user;
		$this->_param = $param;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return $this->display_name;
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
		if ( isset( $this->_memo[ $name ] ) ) {
			$value = $this->_memo[ $name ];
		} else {
			$value = $this->_memo[ $name ] = $this->___proxy( $name );
		}

		return $this->___compute( $name, $value, $args );
	}

	/**
	 * @return    array<string,mixed>
	 */
	public function __debugInfo(): array
	{
		$fields = get_fields( $this->_wp_user, false );
		$fields = empty( $fields ) ? array() : $fields;

		$ref_class = new \ReflectionClass( get_class( $this ) );
		$ref_methods = $ref_class->getMethods();


		$props = array();

		foreach ( $ref_methods as $ref_method ) {
			if ( preg_match( '/^_(?P<name>[^_].*)$/', $ref_method->getName(), $matches ) ) {
				$method_name = $matches[ 'name' ];
				$props[ $method_name ] = $this->$method_name;
			}
		}

		foreach ( $fields as $field_name => $_) {
			$props[ $field_name ] = $this->$field_name;
		}

		ksort( $props );

		return $props;
	}

	// ====================================================================== //

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	private function ___proxy( string $name )
	{
		if (
			function_exists( 'get_field_object' )
			&& ( $field_object = get_field_object( $name, $this->_wp_user, false ) )
		) {
			$factory = new AcfFactory();
			return $factory->from_field_object( $field_object, $this->_param );
		}


		if ( registered_meta_key_exists( 'user', $name ) ) {
			$factory = new PostMetaFactory();
			return $factory->from_name( $this->_wp_user, $name );
		}


		$method_name = "_{$name}";

		if ( method_exists( $this, $method_name ) ) {
			return $this->$method_name();
		}

		return '';
	}

	/**
	 * @param    string    $name
	 * @param    mixed    $value
	 * @param    array<string,mixed>    $args
	 * @return    mixed
	 */
	private function ___compute( string $name, $value, array $args )
	{
		if (
			$value instanceof AcfInterface
			|| $value instanceof PostMeta
		) {
			return $value->invoke( $args );
		}

		$compute_method_name = "__{$name}";

		if ( method_exists( $this, $compute_method_name ) ) {
			$compute = new Compute( $this, $compute_method_name );
			return $compute->bind( $this->_param )->invoke( $value, $args );
		}

		return $value;
	}

	// ====================================================================== //

	/**
	 * @return    string
	 */
	protected function _display_name(): string
	{
		return $this->_wp_user->display_name;
	}


	/**
	 * @return    string
	 */
	protected function _email(): string
	{
		return $this->_wp_user->user_email;
	}


	/**
	 * @return    int
	 */
	protected function _id(): int
	{
		return $this->_wp_user->ID;
	}


	/**
	 * @return    string
	 */
	protected function _login(): string
	{
		return $this->_wp_user->user_login;
	}


	/**
	 * @return    Date
	 */
	protected function _registered(): Date
	{
		$tz = wp_timezone();

		$date_format = isset( $this->_param[ 'date_format' ] )
			? $this->_param[ 'date_format' ]
			: 'Y/m/d';

		return new Date( $this->_wp_user->user_registered, $tz, $date_format );
	}


	/**
	 * @return    string[]
	 */
	protected function _roles(): array
	{
		return $this->_wp_user->roles;
	}
}
