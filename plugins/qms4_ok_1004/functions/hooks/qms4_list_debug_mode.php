<?php

/**
 * @param    bool    $active
 */
function qms4_list_debug_mode( bool $active = true )
{
	add_filter( 'qms4_list_debug', function ( $debug_mode ) use ( $active ) {
		return $active;
	} );
}
