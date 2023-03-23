<?php

namespace ACA\BbPress\Service;

use AC;
use AC\ListScreenTypes;
use AC\Registerable;

class Editing implements Registerable {

	public function register() {
		add_filter( 'ac/editing/role_group', [ $this, 'editing_role_group' ], 10, 2 );
	}

	/**
	 * @param $group
	 * @param $role
	 *
	 * @return string
	 */
	public function editing_role_group( $group, $role ) {
		if ( strpos( $role, "bbp_" ) === 0 ) {
			$group = __( 'bbPress', 'codepress-admin-columns' );
		}

		return $group;
	}

}