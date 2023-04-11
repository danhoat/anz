<?php

namespace QMS4\Action\CustomBlock;

use QMS4\Block\BlockUtil;


class EnqueueScript
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		wp_enqueue_script(
			'qms4__block_script',
			plugins_url( '../../../blocks/js/qms4__block_script.js', __FILE__ ),
			array(),
			false,
			/* $in_footer = */ true
		);
	}
}
