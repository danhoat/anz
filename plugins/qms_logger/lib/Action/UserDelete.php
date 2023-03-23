<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserDelete
{
	public function __invoke( int $user_id, ?int $reassign, \WP_User $user ): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'delete_user', array(
				'ID' => $user_id,
				'user_login' => $user->user_login,
				'reassign' => $reassign,
			) );
	}
}
