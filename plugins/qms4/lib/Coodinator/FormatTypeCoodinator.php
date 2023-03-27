<?php

namespace QMS4\Coodinator;

use QMS4\Action\FormatType\EnqueueScript;
use QMS4\Util\IsMobile;


class FormatTypeCoodinator
{
	public function __construct()
	{
		add_action( 'enqueue_block_editor_assets', new EnqueueScript() );

		add_shortcode( 'post_title', array( $this, 'post_title' ) );
		add_shortcode( 'post_date', array( $this, 'post_date' ) );
		add_shortcode( 'post_modified', array( $this, 'post_modified' ) );

		add_shortcode( 'br_pc', array( $this, 'br_pc' ) );
		add_shortcode( 'br_sp', array( $this, 'br_sp' ) );
	}

	/**
	 * @return    string
	 */
	public function post_title(): string
	{
		return get_the_title();
	}

	/**
	 * @return    string
	 */
	public function post_date(): string
	{
		return get_the_date();
	}

	/**
	 * @return    string
	 */
	public function post_modified(): string
	{
		return get_the_modified_date();
	}

	/**
	 * @return    string
	 */
	public function br_pc(): string
	{
		return IsMobile::detect() ? '' : '<br>';
	}

	/**
	 * @return    string
	 */
	public function br_sp(): string
	{
		return IsMobile::detect() ? '<br>' : '';
	}
}
