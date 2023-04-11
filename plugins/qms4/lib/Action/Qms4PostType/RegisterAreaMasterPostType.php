<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\PostType\AreaMasterPostType;


class RegisterAreaMasterPostType
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
	 * @return    void
	 */
	public function __invoke(): void
	{
		foreach ( $this->post_type_metas as $post_type_meta ) {
			if ( in_array( 'area', $post_type_meta->components(), true ) ) {
				( new AreaMasterPostType() )->register();
				return;
			}
		}
	}
}
