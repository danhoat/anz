<?php

namespace QMS4\Action\CustomBlock;

use QMS4\Block\BlockUtil;
use QMS4\Block\Timetable;


class EnqueueFrontScript
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		if ( BlockUtil::used( 'qms4/monthly-posts' ) ) {
			wp_enqueue_script(
				'qms4__monthly-posts',
				plugins_url( '../../../blocks/js/qms4__monthly-posts.js', __FILE__ ),
				array( 'jquery' ),
				filemtime( QMS4_DIR . '/blocks/js/qms4__monthly-posts.js' ),
				true
			);
		}

		if ( BlockUtil::used( 'qms4/event-calendar' ) ) {
			if( qms4_calendar_style()  !== 'custom' ){
				wp_enqueue_script(
					'qms4__event_calendar',
					plugins_url( '../../../blocks/js/qms4__event_calendar.js', __FILE__ ),
					array( 'jquery' ),
					filemtime( QMS4_DIR . '/blocks/js/qms4__event_calendar.js' ),
					true
				);
			} else{
				wp_enqueue_script(
					'qms4__event_calendar',
					plugins_url( '../../../blocks/js/qms4__event_calendar_custom_style.js', __FILE__ ),
					array( 'jquery' ),
					filemtime( QMS4_DIR . '/blocks/js/qms4__event_calendar_custom_style.js' ),
					true
				);
				wp_localize_script( 'qms4__event_calendar', 'qms4__event_calendar',
					array(
						'event_link' => get_post_type_archive_link('fair')
					)
				);
			}
		}

		if ( BlockUtil::used( 'qms4/panel-menu' ) ) {
			wp_enqueue_script(
				'qms4__panel_menu',
				plugins_url( '../../../blocks/js/qms4__panel_menu.js', __FILE__ ),
				array( 'jquery' ),
				filemtime( QMS4_DIR . '/blocks/js/qms4__panel_menu.js' ),
				true
			);
		}

		if ( BlockUtil::used( 'qms4/timetable' ) ) {
			( new Timetable() )->enqueue_script();
		}
	}
}
