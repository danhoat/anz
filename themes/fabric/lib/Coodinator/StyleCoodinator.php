<?php
namespace Fabric\Coodinator;

use Fabric\Action\Style\EnqueueEditorStyle;
use Fabric\Action\Style\EnqueueStyle;


class StyleCoodinator
{
	public function __construct()
	{
		add_action( 'after_setup_theme', new EnqueueEditorStyle() );
		add_action( 'wp_enqueue_scripts', new EnqueueStyle() );
	}
}
