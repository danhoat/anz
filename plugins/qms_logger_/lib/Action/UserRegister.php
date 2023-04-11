<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserRegister
{
	public function __invoke( int $user_id, array $userdata ): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'register_user', array(
				'ID' => $user_id,
				'user_login' => $userdata[ 'user_login' ],
			) );
	}
}
