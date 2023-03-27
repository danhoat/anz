<?php
namespace Fabric\Action\Script;


class EnqueueScriptsIn404
{
	public function __invoke(): void
	{
		if ( ! is_404() ) { return; }

		$filename = 'template-parts/404/404.js';
		$filepath = __DIR__ . "/../../../{$filename}";

		if ( ! file_exists( $filepath ) ) { return; }

		wp_enqueue_script(
			"fabric-top",
			get_theme_file_uri( $filename ),
			array( 'jquery', 'slick', 'matchHeight', 'fabric-common' ),
			filemtime( $filepath )
		);
	}
}
