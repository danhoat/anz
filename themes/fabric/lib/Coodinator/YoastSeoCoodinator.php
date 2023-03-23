<?php
namespace Fabric\Coodinator;

use Fabric\Action\YoastSeo\RemoveContactMethods;
use Fabric\Action\YoastSeo\SetPriotity;


class YoastSeoCoodinator
{
	public function __construct()
	{
		add_filter( 'wpseo_metabox_prio', new SetPriotity( 'low' ) );
		add_filter( 'user_contactmethods', new RemoveContactMethods(), 20, 2 );
	}
}
