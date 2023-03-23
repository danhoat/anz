<?php

namespace QMS4\Action\PostTyeps;

use QMS4\Event\SubmenuPage\AdminEventCalendar;
use QMS4\PostTypeMeta\PostTypeMeta;


class AddAdminEventCalendarPage
{
	/** @var    PostTypeMeta[] */
	private $event_post_type_metas;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$event_post_type_metas = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->func_type() === 'event' ) {
				$event_post_type_metas[] = $post_type_meta;
			}
		}

		$this->event_post_type_metas = $event_post_type_metas;
	}

	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		foreach ( $this->event_post_type_metas as $post_type_meta ) {
			( new AdminEventCalendar( $post_type_meta ) )->register();
		}
	}
}
