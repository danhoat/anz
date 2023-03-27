<?php

if ( ! function_exists( 'ok' ) ) {
	/**
	 * @param    mixed    $value
	 * @return    bool
	 */
	function ok( $value ): bool
	{
		if ( is_null( $value ) ) { return false; }

		if ( is_string( $value ) || is_array( $value ) ) {
			return ! empty( $value );
		}

		if ( function_exists( 'is_countable' ) && is_countable( $value ) ) {
			return count( $value ) !== 0;
		}

		if ( $value instanceof \Countable ) {
			return count( $value ) !== 0;
		}

		if ( method_exists( $value, 'count') ) {
			return $value->count() !== 0;
		}

		if ( method_exists( $value, 'empty') ) {
			return ! $value->empty();
		}

		if ( method_exists( $value, 'isEmpty') ) {
			return ! $value->isEmpty();
		}

		if ( method_exists( $value, 'is_empty') ) {
			return ! $value->is_empty();
		}

		return (bool) $value;
	}
}
