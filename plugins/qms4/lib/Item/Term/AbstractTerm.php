<?php

namespace QMS4\Item\Term;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\PostMeta\PostMeta;
use QMS4\Item\PostMeta\PostMetaFactory;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


abstract class AbstractTerm
{
	/** @var    \WP_Term */
	protected $_wp_term;

	/** @var    Param */
	protected $_param;

	/** @var    array<string,mixed> */
	protected $_memo;

	/**
	 * @param    \WP_Term    $wp_term
	 * @param    Param    $param
	 */
	public function __construct(
		\WP_Term $wp_term,
		Param $param,
		array $memo = array()
	)
	{
		$this->_wp_term = $wp_term;
		$this->_param = $param;
		$this->_memo = $memo;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		$template = $this->_param[ 'term_html' ];

		$map = array(
			'[id]' => $this->id,
			'[name]' => $this->name,
			'[slug]' => $this->slug,
			'[color]' => $this->color,
			'[text-color]' => $this->color ? "color: {$this->color};" : '',
			'[border-color]' => $this->color ? "border-color: {$this->color};" : '',
			'[background-color]' => $this->color ? "background-color: {$this->color};" : '',
		);

		return str_replace( array_keys( $map ), $map, $template );
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
		$fields = get_fields( $this->_wp_term, false );
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
			&& ( $field_object = get_field_object( $name, $this->_wp_term, false ) )
		) {
			$factory = new AcfFactory();
			return $factory->from_field_object( $field_object, $this->_param );
		}


		if ( registered_meta_key_exists( 'term', $name ) ) {
			$factory = new PostMetaFactory();
			return $factory->from_name( $this->_wp_term, $name );
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
}
