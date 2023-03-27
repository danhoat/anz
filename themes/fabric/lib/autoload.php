<?php

spl_autoload_register( function( $classname ) {
	if ( strpos( $classname, 'Fabric' ) === false ) { return; }

	$classname = str_replace( '\\', '/', $classname );
	$classname = str_replace( 'Fabric/', '', $classname );

	$filepath = __DIR__ . "/{$classname}.php";

	if ( file_exists( $filepath ) ) { require( $filepath ); }
} );
