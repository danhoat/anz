<?php

namespace QMS4\Action\RestApi;

use QMS4\Fetch\MonthlyPosts;


class RegisterEventSchedulesRoute
{
	public function __invoke()
	{
		register_rest_route(
			'qms4/v1',
			'/schedules/(?P<post_type>[a-z0-9_\\-]+)/(?P<start>\\d{4}\\-\\d{2}\\-\\d{2})/(?P<end>\\d{4}\\-\\d{2}\\-\\d{2})/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * @param    \WP_REST_Request    $request
	 * @return    \WP_REST_Response|\WP_Error
	 */
	public function get( \WP_REST_Request $request )
	{
		$param = $request->get_params();


		$validation_result = $this->validate( $param );
		if ( is_wp_error( $validation_result ) ) {
			return $validation_result;
		}


		$post_type = $param[ 'post_type' ];
		$start = $param[ 'start' ];
		$end = $param[ 'end' ];


		$data_monthly_posts = new MonthlyPosts( $post_type );
		$posts = $data_monthly_posts->fetch( $year );

		return new \WP_REST_Response( $posts, 200 );
	}

	/**
	 * @param    array<string,mixed>    $param
	 * @return    true|\WP_Error
	 */
	private function validate( array $param )
	{
		$post_type_object = get_post_type_object( $param[ 'post_type' ] );

		if ( is_null( $post_type_object ) || ! $post_type_object->public ) {
			return new \WP_Error(
				'unknown_post_type',
				"Unknown post_type: \$post_type: {$param['post_type']}",
				array( 'status' => 400 )
			);
		}

		return true;
	}
}
