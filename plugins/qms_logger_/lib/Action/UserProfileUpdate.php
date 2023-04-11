<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserProfileUpdate
{
	public function __invoke(
		int $user_id,
		\WP_User $old_user_data,
		array $userdata
	): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'profile_update', array(
				'ID' => $user_id,
				'user_login' => $old_user_data->user_login,
			) );
	}
}
