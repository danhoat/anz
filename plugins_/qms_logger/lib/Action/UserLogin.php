<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserLogin
{
	public function __invoke( string $user_login, \WP_User $user )
	{
		UserActionLogger::channel( 'core' )
			->info( 'login', array(
				'ID' => $user->ID,
				'user_login' => $user_login,
			) );
	}
}
