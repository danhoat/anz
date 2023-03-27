<?php

namespace QMS4\Action\RestApi;


class RegisterAdminUrlRoute
{
	public function __invoke()
	{
		register_rest_route(
			'qms4/v1',
			'/admin-url/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get' ),
				'permission_callback' => array( $this, 'permission' ),
			)
		);
	}

	/**
	 * @param    \WP_REST_Request    $request
	 * @return    \WP_REST_Response|\WP_Error
	 */
	public function get( \WP_REST_Request $request )
	{
		return admin_url( '/' );
	}

	/**
	 * @return    bool|\WP_Error
	 */
	public function permission()
	{
		// TODO: なんとかする
		return true;  // current_user_can( 'edit_post' );
	}
}
