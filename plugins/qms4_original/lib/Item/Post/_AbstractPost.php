<?php

namespace QMS4\Item\Post;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\Term\Terms;
use QMS4\Item\Term\TermsFactory;
use QMS4\Item\PostMeta\PostMeta;
use QMS4\Item\PostMeta\PostMetaFactory;
use QMS4\Item\Util\Compute;
use QMS4\Item\Util\Generate;
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
		$value = $this->___generate( $name );

		return $this->___compute( $name, $value, $args );
	}

	public function __debugInfo(): array
	{
		$post_type = $this->_wp_post->post_type;

		$fields = get_fields( $this->_wp_post, false );
		$fields = empty( $fields ) ? array() : $fields;

		$taxonomies = get_taxonomies( array(
			'object_type' => array( $this->_wp_post->post_type )
		) );

		$ref_class = new \ReflectionClass( get_class( $this ) );
		$ref_methods = $ref_class->getMethods();


		$props = array();

		foreach ( $ref_methods as $ref_method ) {
			if ( preg_match( '/^_(?P<name>[^_].*)$/', $ref_method->getName(), $matches ) ) {
				$method_name = $matches[ 'name' ];
				$props[ $method_name ] = $this->$method_name;
			}
		}

		foreach ( $taxonomies as $taxonomy => $_ ) {
			if ( preg_match( "/^{$post_type}__(?P<name>.+)$/", $taxonomy, $matches ) ) {
				$taxonomy_name = $matches[ 'name' ];
				$props[ $taxonomy_name ] = $this->$taxonomy_name;
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
	 * @param    string    $name;
	 * @return    mixed
	 */
	private function ___generate( string $name )
	{
		return isset( $this->_memo[ $name ] )
			? $this->_memo[ $name ]
			: ( $this->_memo[ $name ] = $this->___proxy( $name ) );
	}

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	private function ___proxy( string $name )
	{
		if (
			function_exists( 'get_field_object' )
			&& ( $field_object = get_field_object( $name, $this->_wp_post, false ) )
		) {
			$factory = new AcfFactory();
			return $factory->from_field_object( $field_object, $this->_param );
		}


		if ( registered_meta_key_exists( $this->_wp_post->post_type, $name ) ) {
			$factory = new PostMetaFactory();
			return $factory->from_name( $this->_wp_post, $name );
		}


		$taxonomy = "{$this->_wp_post->post_type}__{$name}";
		if ( taxonomy_exists( $taxonomy ) ) {
			$factory = new TermsFactory();
			return $factory->from_taxonomy( $this->_wp_post, $taxonomy, $this->_param );
		}


		$method_name = "_{$name}";

		if ( ! method_exists( $this, $method_name ) ) { return null; }

		$generate = new Generate( $this, '_memo', '___generate', $method_name );
		return $generate->invoke();
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
			|| $value instanceof Terms
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
