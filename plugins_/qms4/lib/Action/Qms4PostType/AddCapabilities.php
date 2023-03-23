<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\RoleCapability\Administrator;


class AddCapabilities
{
	public function __invoke()
	{
		$administrator = new Administrator();
		$administrator->add_caps( 'qms4' );
	}
}
