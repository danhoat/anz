<?php

namespace QMS4\QueryBuilder;


class ArgsMerge
{
	public function run( array $args, array $other_args ): array
	{
		if ( empty( $args ) ) { return $other_args; }

		foreach ( $other_args as $key => $value ) {
			$args = $this->merge($args, $key, $value);
		}

		return $args;
	}

	public function merge( array $args, string $key, $value ): array
	{
		$keys = array( 'meta_query', 'tax_query', 'date_query' );

		if ( in_array( $key, $keys, /* $strict = */ true ) ) {
			$args[ $key ] = empty( $args[ $key ] )
				? $value
				: array( $args[ $key ], $value );
		} else {
			$args[ $key ] = $value;
		}

		return $args;
	}
}
