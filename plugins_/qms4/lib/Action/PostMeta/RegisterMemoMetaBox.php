<?php

namespace QMS4\Action\PostMeta;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\PostMeta\Memo;


class RegisterMemoMetaBox
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	/**
	 * @param    PostTypeMeta    $post_type_meta
	 */
	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;
	}

	/**
	 * @return    void
	 */
	public function __invoke()
	{
		( new Memo( $this->post_type_meta->name() ) )->register_meta();
	}
}
