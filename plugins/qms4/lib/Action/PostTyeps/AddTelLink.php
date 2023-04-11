<?php

namespace QMS4\Action\PostTyeps;


class AddTelLink
{
	/**
	 * @param    string    $content
	 * @return    string
	 */
	public function __invoke( string $content ): string
	{
		// return preg_replace_callback(
		// 	'/0\d{1,4}-\d{1,4}-\d{4}/',
		// 	function( $matches ) {
		// 		$tel = $matches[ 0 ];
		// 		$tel_ = str_replace( array( '-', '(', ')' ), '', $tel );
		// 		return '<a href="tel:' . $tel_ . '">' . $tel . '</a>';
		// 	},
		// 	$content
		// );
		return $content;
	}
}
