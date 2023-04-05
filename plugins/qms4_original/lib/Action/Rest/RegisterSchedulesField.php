<?php

namespace QMS4\Action\Rest;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Rest\Schedules;


class RegisterSchedulesField
{
	/** @var    PostTypeMeta[] */
	private $post_type_metas;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$this->post_type_metas = $post_type_metas;
	}

	/**
	 * @param    \WP_REST_Server    $wp_rest_server
	 * @return    void
	 */
	public function __invoke( \WP_REST_Server $wp_rest_server )
	{
		foreach ( $this->post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->func_type() === 'event' ) {
				( new Schedules( $post_type_meta ) )->register_field();
			}
		}
	}
}
