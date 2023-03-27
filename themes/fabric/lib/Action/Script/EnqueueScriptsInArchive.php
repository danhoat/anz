<?php
namespace Fabric\Action\Script;


class EnqueueScriptsInArchive
{
	public function __invoke(): void
	{
		if ( ! is_archive() ) { return; }

		$post_type = get_query_var( 'post_type' );
		$filename = "template-parts/{$post_type}/archive.js";
		$filepath = __DIR__ . "/../../../{$filename}";

		if ( ! file_exists( $filepath ) ) { return; }

		wp_enqueue_script(
			"fabric-archive-{$post_type}",
			get_theme_file_uri( $filename ),
			array( 'jquery', 'slick', 'matchHeight', 'fabric-common' ),
			filemtime( $filepath )
		);
	}
}
