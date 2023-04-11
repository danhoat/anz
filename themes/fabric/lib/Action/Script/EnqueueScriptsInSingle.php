<?php
namespace Fabric\Action\Script;


class EnqueueScriptsInSingle
{
	public function __invoke(): void
	{
		if ( ! is_single() ) { return; }

		global $post;
		$post_type = $post->post_type;
		$filename = "template-parts/{$post_type}/single.js";
		$filepath = __DIR__ . "/../../../{$filename}";

		//if ( ! file_exists( $filepath ) ) { return; }

		// wp_enqueue_script(
		// 	"fabric-single-{$post_type}",
		// 	get_theme_file_uri( $filename ),
		// 	array( 'jquery', 'slick', 'matchHeight', 'fabric-common' ),
		// 	filemtime( $filepath )
		// );
	}
}
