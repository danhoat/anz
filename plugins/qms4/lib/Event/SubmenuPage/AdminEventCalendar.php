<?php

namespace QMS4\Event\SubmenuPage;

use QMS4\PostTypeMeta\PostTypeMeta;


class AdminEventCalendar
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	/**
	 * @param    PostTypeMeta    $post_type_meta
	 */
	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;
	}

	/**
	 * @return    void
	 */
	public function register(): void
	{
		add_submenu_page(
			"edit.php?post_type={$this->post_type_meta->name()}",
			"{$this->post_type_meta->label()} 日程カレンダー",
			'日程カレンダー',
			'edit_posts',
			"{$this->post_type_meta->name()}__calendar",
			array( $this, 'render' )
		);
	}

	public function render()
	{
		$asset_file = require( QMS4_DIR . '/blocks/build/admin-event-calendar/index.asset.php' );

		$handle = 'qms4__admin-event-calendar';

		wp_register_script(
			/* $handle = */ $handle,
			/* $src = */ plugins_url( '../../blocks/build/admin-event-calendar/index.js', __FILE__ ),
			/* $deps = */ $asset_file[ 'dependencies' ],
			/* $ver = */ $asset_file[ 'version' ],
			/* $in_footer = */ true
		);

		wp_enqueue_script( $handle );

		echo '<div id="qms4__admin-event-calendar__container" class="qms4__admin-event-calendar__container"></div>';
	}
}
