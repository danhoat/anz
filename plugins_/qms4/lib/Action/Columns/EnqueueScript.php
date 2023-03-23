<?php

namespace QMS4\Action\Columns;


class EnqueueScript
{
	/**
	 * @param    string    $hook_suffix
	 * @return    void
	 */
	public function __invoke( string $hook_suffix ): void
	{
		global $post_type;

		if ( $hook_suffix === 'edit.php' && $post_type === 'qms4__site_part' ) {
			wp_enqueue_script(
				'qms4__site_part',
				plugins_url( '../../../assets/js/qms4__site_part.js', __FILE__ ),
				array(),
				false,
				true
			);
		}
	}
}
