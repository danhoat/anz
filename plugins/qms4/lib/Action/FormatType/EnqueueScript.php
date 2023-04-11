<?php

namespace QMS4\Action\FormatType;


class EnqueueScript
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		wp_enqueue_script(
			'qms4__wp-post',
			plugins_url( '../../../blocks/build/wp-post/index.js', __FILE__ ),
			array(),
			false,
			/* $in_footer = */ true
		);

		wp_enqueue_script(
			'qms4__restricted-br',
			plugins_url( '../../../blocks/build/restricted-br/index.js', __FILE__ ),
			array(),
			false,
			/* $in_footer = */ true
		);
	}
}
