<?php

namespace QMS4\Action\PostMeta;


class ShowSlugMetaBox
{
	/** @var    string */
	private $post_type;

	/**
	 * @param    string    $post_type
	 */
	public function __construct( string $post_type )
	{
		$this->post_type = $post_type;
	}

	/**
	 * @param    string[]    $hidden
	 * @param    \WP_Screen    $screen
	 * @return    string[]
	 */
	public function __invoke( array $hidden, \WP_Screen $screen ): array
	{
		if ( $screen->post_type !== $this->post_type ) { return $hidden; }

		$hidden = array_filter( $hidden, function ( string $value ) {
			return 'slugdiv' !== $value;
		} );

		return $hidden;
	}
}
