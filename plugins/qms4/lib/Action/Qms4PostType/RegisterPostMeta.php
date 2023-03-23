<?php

namespace QMS4\Action\Qms4PostType;


class RegisterPostMeta
{
	public function __invoke()
	{
		register_post_meta( 'qms4', 'capability_configured', array(
			'type' => 'boolean',
			'default' => false,
			'single' => true,
		) );
	}
}
