<?php
namespace Fabric\Action\Style;


class EnqueueStyle
{
	public function __invoke()
	{
		wp_enqueue_style(
			'fabric-theme-style',
			get_stylesheet_uri(),
			array(),
			filemtime( get_theme_file_path( 'style.css' ) )
		);
		wp_enqueue_style(
			'fabric-theme-style02',
			get_stylesheet_directory_uri() . '/slick.css',
			array(),
			filemtime( get_theme_file_path( 'slick.css' ) )
		);
		wp_enqueue_style(
			'fabric-theme-style03',
			get_stylesheet_directory_uri() . '/style02.css',
			array(),
			filemtime( get_theme_file_path( 'style02.css' ) )
		);
	}
}
