<?php
namespace Fabric\Coodinator;

use Fabric\Action\Shortcode\SettingCallback;


class ShortcodeCoodinator
{
	public function __construct()
	{
		add_shortcode( 'setting', new SettingCallback() );
	}
}
