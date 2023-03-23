<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserResetPassword
{
	public function __invoke( \WP_User $user, string $new_pass ): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'reset_password', array(
				'ID' => $user->ID,
				'user_login' => $user->user_login,
				'new_pass' => str_repeat( '*', mb_strlen( $new_pass ) ),
			) );
	}
}
