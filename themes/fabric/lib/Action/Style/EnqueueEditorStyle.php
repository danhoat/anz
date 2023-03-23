<?php
namespace Fabric\Action\Style;


class EnqueueEditorStyle
{
	public function __invoke()
	{
		add_theme_support( 'editor-styles' );
		add_editor_style( 'editor-style.css' );
	}
}
