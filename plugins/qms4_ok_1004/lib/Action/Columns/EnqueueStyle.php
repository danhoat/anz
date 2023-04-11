<?php

namespace QMS4\Action\Columns;


class EnqueueStyle
{
	/**
	 * @param    string    $hook_suffix
	 * @return    void
	 */
	public function __invoke( string $hook_suffix ): void
	{
		global $post_type;

		if ( $hook_suffix === 'edit.php' && $post_type === 'qms4__site_part' ) {
			wp_enqueue_style(
				'qms4__site_part',
				plugins_url( '../../../assets/css/qms4__site_part.css', __FILE__ )
			);
		}
	}
}
