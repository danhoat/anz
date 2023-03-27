<?php

namespace QMS4\Block;


class MegaMenu
{
	/** @var    string */
	private $name = 'mega-menu';

	public function register()
	{
		register_block_type( QMS4_DIR . "/blocks/build/{$this->name}" );
		register_block_type( QMS4_DIR . '/blocks/build/mega-menu-item' );
	}
}
