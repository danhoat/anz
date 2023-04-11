<?php

namespace QMS4\Block;


class PanelMenu
{
	/** @var    string */
	private $name = 'panel-menu';

	public function register()
	{
		register_block_type( QMS4_DIR . "/blocks/build/{$this->name}" );
		register_block_type( QMS4_DIR . '/blocks/build/panel-menu-item' );
		register_block_type( QMS4_DIR . '/blocks/build/panel-menu-subitem' );
	}
}
