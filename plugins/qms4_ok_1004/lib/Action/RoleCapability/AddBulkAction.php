<?php

namespace QMS4\Action\RoleCapability;


class AddBulkAction
{
	/**
	 * @param    string[]    $actions
	 * @return    string[]
	 */
	public function __invoke( array $actions ): array
	{
		$actions[ 'reset_all_caps' ] = '権限リセット';

		return $actions;
	}
}
