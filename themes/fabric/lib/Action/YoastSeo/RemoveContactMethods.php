<?php
namespace Fabric\Action\YoastSeo;


class RemoveContactMethods
{
	/**
	 * @param    array<string,string>    $methods
	 * @param    \WP_User|\stdClass|null    $user
	 * @return    array<string,string>
	 */
	public function __invoke( array $methods, $user ): array
	{
		unset( $methods[ 'facebook' ] );
		unset( $methods[ 'instagram' ] );
		unset( $methods[ 'linkedin' ] );
		unset( $methods[ 'myspace' ] );
		unset( $methods[ 'pinterest' ] );
		unset( $methods[ 'soundcloud' ] );
		unset( $methods[ 'tumblr' ] );
		unset( $methods[ 'twitter' ] );
		unset( $methods[ 'youtube' ] );
		unset( $methods[ 'wikipedia' ] );

		return $methods;
	}
}
