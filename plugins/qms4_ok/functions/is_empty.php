<?php

if ( ! function_exists( 'is_empty' ) ) {
	/**
	 * @param    mixed    $value
	 * @return    bool
	 */
	function is_empty( $value ): bool
	{
		if ( is_null( $value ) ) { return true; }

		if ( is_string( $value ) || is_array( $value ) ) {
			return empty( $value );
		}

		if ( function_exists( 'is_countable' ) && is_countable( $value ) ) {
			return count( $value ) === 0;
		}

		if ( $value instanceof \Countable ) {
			return count( $value ) === 0;
		}

		if ( method_exists( $value, 'count') ) {
			return $value->count() === 0;
		}

		if ( method_exists( $value, 'empty') ) {
			return (bool) $value->empty();
		}

		if ( method_exists( $value, 'isEmpty') ) {
			return (bool) $value->isEmpty();
		}

		if ( method_exists( $value, 'is_empty') ) {
			return (bool) $value->is_empty();
		}

		return ! $value;
	}
}
