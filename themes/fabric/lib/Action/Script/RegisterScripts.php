<?php
namespace Fabric\Action\Script;


class RegisterScripts
{
	public function __invoke(): void
	{
		wp_register_script(
			'slick',
			get_theme_file_uri( 'template-parts/common/slick.min.js' ),
			array( 'jquery' ),
			filemtime( __DIR__ . '/../../../template-parts/common/slick.min.js' )
		);

		wp_register_script(
			'matchHeight',
			get_theme_file_uri( 'template-parts/common/jquery.matchHeight-min.js' ),
			array( 'jquery' ),
			filemtime( __DIR__ . '/../../../template-parts/common/jquery.matchHeight-min.js' )
		);
	}
}
