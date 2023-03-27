<?php
namespace Fabric\Coodinator;

use Fabric\Action\Script\EnqueueCommonScripts;
use Fabric\Action\Script\EnqueueScriptsIn404;
use Fabric\Action\Script\EnqueueScriptsInArchive;
use Fabric\Action\Script\EnqueueScriptsInFrontPage;
use Fabric\Action\Script\EnqueueScriptsInPage;
use Fabric\Action\Script\EnqueueScriptsInSingle;
use Fabric\Action\Script\RegisterScripts;


class ScriptCoodinator
{
	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', new RegisterScripts() );
		add_action( 'wp_enqueue_scripts', new EnqueueCommonScripts() );
		add_action( 'wp_enqueue_scripts', new EnqueueScriptsInFrontPage() );
		add_action( 'wp_enqueue_scripts', new EnqueueScriptsInPage() );
		add_action( 'wp_enqueue_scripts', new EnqueueScriptsIn404() );
		add_action( 'wp_enqueue_scripts', new EnqueueScriptsInArchive() );
		add_action( 'wp_enqueue_scripts', new EnqueueScriptsInSingle() );
	}
}
