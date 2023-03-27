<?php

namespace QMS4\Coodinator;

use QMS4\Action\CustomBlock\RegisterBlock;
use QMS4\Action\CustomBlock\EnqueueFrontScript;
use QMS4\Action\CustomBlock\EnqueueScript;
use QMS4\Block\BlockUtil;


class CustomBlockCoodinator
{
	public function __construct()
	{
		add_action( 'init', new RegisterBlock() );

		add_action( 'wp_footer', new EnqueueFrontScript() );
		add_action( 'enqueue_block_editor_assets', new EnqueueScript() );

		$block_name = 'qms4/monthly-posts';
		add_filter( "render_block_{$block_name}", array( $this, 'use__monthly_posts' ) );

		$block_name = 'qms4/term-list';
		add_filter( "render_block_{$block_name}", array( $this, 'use__term_list' ) );

		$block_name = 'qms4/event-calendar';
		add_filter( "render_block_{$block_name}", array( $this, 'use__event_calendar' ) );

		$block_name = 'qms4/panel-menu';
		add_filter( "render_block_{$block_name}", array( $this, 'use__panel_menu' ) );

		$block_name = 'qms4/timetable';
		add_filter( "render_block_{$block_name}", array( $this, 'use__timetable' ) );
	}

	/**
	 * @param    string    $block_content
	 * @return    string
	 */
	public function use__monthly_posts( string $block_content ): string
	{
		$block_name = 'qms4/monthly-posts';
		remove_filter( "render_block_{$block_name}", array( $this, 'use__monthly_posts' ) );

		BlockUtil::use( $block_name );

		return $block_content;
	}

	/**
	 * @param    string    $block_content
	 * @return    string
	 */
	public function use__term_list( string $block_content ): string
	{
		$block_name = 'qms4/term-list';
		remove_filter( "render_block_{$block_name}", array( $this, 'use__term_list' ) );

		BlockUtil::use( $block_name );

		return $block_content;
	}

	/**
	 * @param    string    $block_content
	 * @return    string
	 */
	public function use__event_calendar( string $block_content ): string
	{
		$block_name = 'qms4/event-calendar';
		remove_filter( "render_block_{$block_name}", array( $this, 'use__event_calendar' ) );

		BlockUtil::use( $block_name );

		return $block_content;
	}

	/**
	 * @param    string    $block_content
	 * @return    string
	 */
	public function use__panel_menu( string $block_content ): string
	{
		$block_name = 'qms4/panel-menu';
		remove_filter( "render_block_{$block_name}", array( $this, 'use__event_calendar' ) );

		BlockUtil::use( $block_name );

		return $block_content;
	}

	/**
	 * @param    string    $block_content
	 * @return    string
	 */
	public function use__timetable( string $block_content ): string
	{
		$block_name = 'qms4/timetable';
		remove_filter( "render_block_{$block_name}", array( $this, 'use__event_calendar' ) );

		BlockUtil::use( $block_name );

		return $block_content;
	}
}
