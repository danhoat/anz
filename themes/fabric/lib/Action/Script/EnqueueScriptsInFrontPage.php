<?php
namespace Fabric\Action\Script;


class EnqueueScriptsInFrontPage
{
	public function __invoke(): void
	{
		if ( ! is_front_page() ) { return; }

		$filename = 'template-parts/front_page/front_page.js';
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
