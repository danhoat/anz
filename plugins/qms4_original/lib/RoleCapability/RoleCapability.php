<?php

namespace QMS4\RoleCapability;


abstract class RoleCapability
{
	/** @var    \WP_Role */
	protected $wp_role;

	/**
	 * @param    string|null    $name
	 */
	public function __construct( ?string $name = null )
	{
		$name = $name ?: $this->role_name();
		$wp_role = get_role( $name );

		if ( ! $wp_role ) {
			throw new \RuntimeException( "不明なロール名です: \$name: {$name}" );
		}

		$this->wp_role = $wp_role;
	}

	// ====================================================================== //

	/**
	 * @return    string
	 */
	abstract protected function role_name(): string;

	/**
	 * @param    string    $capability_type
	 * @return    array<string,bool>
	 */
	abstract protected function initial_grants( string $capability_type ): array;

	// ====================================================================== //

	/**
	 * @param    string    $capability_type
	 * @return    void
	 */
	final public function add_caps( string $capability_type ): void
	{
		$grants = $this->initial_grants( $capability_type );
		foreach ( $grants as $cap => $grant ) {
			$this->wp_role->add_cap( $cap, $grant );
		}
	}

	/**
	 * @param    string    $capability_type
	 * @param    string    $new_capability_type
	 * @return    void
	 */
	final public function replace_caps(
		string $capability_type,
		string $new_capability_type
	): void
	{
		$capabilities = $this->wp_role->capabilities;

		$cap_names = array_keys( $this->initial_grants( $capability_type ) );
		$new_cap_names = array_keys( $this->initial_grants( $new_capability_type ) );

		$pairs = array_map( null, $cap_names, $new_cap_names );

		foreach ( $pairs as list( $cap, $new_cap ) ) {
			$grant = isset( $capabilities[ $cap ] )
				? $capabilities[ $cap ]
				: false;

			$this->wp_role->add_cap( $new_cap, $grant );
			$this->wp_role->remove_cap( $cap );
		}
	}

	/**
	 * @param    string    $capability_type
	 * @param    string    $new_capability_type
	 * @return    void
	 */
	final public function clone_caps(
		string $capability_type,
		string $new_capability_type
	): void
	{
		$capabilities = $this->wp_role->capabilities;

		$cap_names = array_keys( $this->initial_grants( $capability_type ) );
		$new_cap_names = array_keys( $this->initial_grants( $new_capability_type ) );

		$pairs = array_map( null, $cap_names, $new_cap_names );

		foreach ( $pairs as list( $cap, $new_cap ) ) {
			$grant = isset( $capabilities[ $cap ] )
				? $capabilities[ $cap ]
				: false;

			$this->wp_role->add_cap( $new_cap, $grant );
		}
	}

	/**
	 * @param    string    $capability_type
	 * @return    void
	 */
	final public function remove_caps( string $capability_type ): void
	{
		$grants = $this->initial_grants( $capability_type );
		foreach ( $grants as $cap => $grant ) {
			$this->wp_role->remove_cap( $cap );
		}
	}
}
