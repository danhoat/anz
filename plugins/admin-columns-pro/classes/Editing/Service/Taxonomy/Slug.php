<?php

namespace ACP\Editing\Service\Taxonomy;

use ACP\Editing\View;

class Slug extends Field {

	public function __construct( $taxonomy ) {
		parent::__construct( $taxonomy, 'slug' );
	}

	public function get_view( string $context ): ?View {
		return new View\Text();
	}

}