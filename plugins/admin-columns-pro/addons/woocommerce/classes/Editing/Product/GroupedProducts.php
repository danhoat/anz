<?php

namespace ACA\WC\Editing\Product;

use ACA\WC\Editing\PostTrait;
use ACA\WC\Editing\Storage;
use ACA\WC\Helper\Select;
use ACP;
use ACP\Editing\View;

class GroupedProducts implements ACP\Editing\Service, ACP\Editing\Service\Editability, ACP\Editing\PaginatedOptions {

	use ProductNotSupportedReasonTrait;
	use PostTrait;

	public function get_view( string $context ): ?View {
		return ( new ACP\Editing\View\AjaxSelect() )->set_multiple( true )->set_clear_button( true );
	}

	public function get_paginated_options( $s, $paged, $id = null ) {
		return new Select\Paginated\Products( (string) $s, (int) $paged );
	}

	public function is_editable( int $id ): bool {
		return 'grouped' === wc_get_product( $id )->get_type();
	}

	public function get_value( $id ) {
		$product = wc_get_product( $id );

		if ( 'grouped' !== $product->get_type() ) {
			return null;
		}

		return $this->get_editable_posts_values( $product->get_children() );
	}

	public function update( int $id, $data ): void {
		update_post_meta( $id, '_children', array_map( 'intval', (array) $data ) );
	}

}