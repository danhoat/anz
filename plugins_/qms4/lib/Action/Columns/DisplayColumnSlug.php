<?php

namespace QMS4\Action\Columns;


class DisplayColumnSlug
{
	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__slug' ) { return; }

		$slug = get_post_field( 'post_name', $post_id );
		$slug = urldecode( $slug );

		echo '<p>' . esc_html( $slug ) . '</p>';
	}
}
