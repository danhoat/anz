<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\RoleCapability\Administrator;


class RemoveCapabilities
{
	public function __invoke()
	{
		$administrator = new Administrator();
		$administrator->remove_caps( 'qms4' );
	}
}
