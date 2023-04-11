<?php
namespace QMS\Logger\Logger;

use Monolog\Processor\ProcessorInterface;


class CurrentUserProcessor implements ProcessorInterface
{
	/** @var    \WP_User */
	private $current_user;

	public function __construct()
	{
		$this->current_user = wp_get_current_user();
	}

	public function __invoke( array $record ): array
	{
		$record[ 'extra' ][ 'current_user_id' ] = $this->current_user->ID;
		$record[ 'extra' ][ 'current_user_login' ] = $this->current_user->user_login;

		return $record;
	}
}
