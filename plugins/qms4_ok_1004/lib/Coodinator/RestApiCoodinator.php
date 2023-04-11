<?php

namespace QMS4\Coodinator;

use QMS4\Action\RestApi\RegisterAdminUrlRoute;
use QMS4\Action\RestApi\RegisterEventCalendarRoute;
use QMS4\Action\RestApi\RegisterMonthlyPosts;
use QMS4\Action\RestApi\RegisterQms4Route;


class RestApiCoodinator
{
	public function __construct()
	{
		add_action( 'rest_api_init', new RegisterAdminUrlRoute() );
		add_action( 'rest_api_init', new RegisterEventCalendarRoute() );
		add_action( 'rest_api_init', new RegisterMonthlyPosts() );
		add_action( 'rest_api_init', new RegisterQms4Route() );
	}
}
