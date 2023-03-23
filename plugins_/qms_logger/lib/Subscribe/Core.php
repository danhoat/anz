<?php
namespace QMS\Logger\Subscribe;

use QMS\Logger\Action;


class Core
{
	public function __construct()
	{
		add_action( 'wp_login', new Action\UserLogin(), 10, 2 );
		add_action( 'wp_logout', new Action\UserLogout() );

		add_action( 'user_register', new Action\UserRegister(), 10, 2 );
		add_action( 'delete_user', new Action\UserDelete(), 10, 3 );
		add_action( 'profile_update', new Action\UserProfileUpdate(), 10, 3 );
		add_action( 'lostpassword_post', new Action\UserLostPassword(), 10, 2 );
		add_action( 'password_reset', new Action\UserResetPassword(), 10, 2 );

		add_action( 'delete_post', new Action\PostDalete(), 10, 2 );
		add_action( 'transition_post_status', new Action\PostUpdate, 10, 3 );

		add_action( 'saved_term', new Action\TermUpdate(), 10, 4 );
		add_action( 'delete_term', new Action\TermDelete(), 10, 5 );
	}
}
