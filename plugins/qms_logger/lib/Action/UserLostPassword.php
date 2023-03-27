<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserLostPassword
{
	/**
	 * @param    \WP_Error    $errors
	 * @param    \WP_User|false    $user
	 */
	public function __invoke( \WP_Error $errors, $user ): void
	{
		if ( ! $user_data ) {
			UserActionLogger::channel( 'core' )
				->notice( 'lost_password_error', array(
					'errors' => $errors->get_error_messages(),
				) );
		} else {
			UserActionLogger::channel( 'core' )
				->info( 'lost_password', array(
					'ID' => $user->ID,
					'user_login' => $user->user_login,
				) );
		}
	}
}
