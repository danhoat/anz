<?php
namespace Fabric\Action\Script;


class EnqueueCommonScripts
{
	public function __invoke(): void
	{
		wp_enqueue_script(
			'fabric-common',
			get_theme_file_uri( 'template-parts/common/common.js' ),
			array( 'jquery', 'slick', 'matchHeight' ),
			filemtime( __DIR__ . '/../../../template-parts/common/common.js' )
		);
	}
}
