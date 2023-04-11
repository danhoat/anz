<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\PostType\BlockTemplatePostType;
use QMS4\PostType\Qms4PostType;
use QMS4\PostType\SitePartPostType;


class RegisterPostType
{
	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		( new Qms4PostType() )->register();
		( new BlockTemplatePostType() )->register();
		( new SitePartPostType() )->register();
	}
}
