<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserLogout
{
	public function __invoke( int $user_id )
	{
		$user = get_user_by( 'id', $user_id );

		UserActionLogger::channel( 'core' )
			->info( 'logout', array(
				'ID' => $user_id,
				'user_login' => $user->user_login,
			) );
	}
}
