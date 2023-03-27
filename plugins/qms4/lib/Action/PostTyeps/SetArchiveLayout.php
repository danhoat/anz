<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class SetArchiveLayout
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
	 * @param    string    $list_type
	 * @param    string    $archive_type
	 * @return    bool
	 */
	public function __invoke( string $list_type, string $archive_type ): string
	{
		$post_type = get_query_var( 'post_type' );
		$post_type_meta = $this->find( $post_type );

		$archive_layout = $post_type_meta
			? $post_type_meta->archive_layout()
			: 'default';

		switch ( $archive_layout ) {
			case 'card': return 'card';
			case 'list': return 'list';
			case 'text': return 'simple';
			default: return $list_type;
		}
	}

	// ====================================================================== //

	/**
	 * @param    string    $post_type
	 * @return    PostTypeMeta|null
	 */
	private function find( string $post_type ): ?PostTypeMeta
	{
		foreach ( $this->post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->name() === $post_type ) {
				return $post_type_meta;
			}
		}

		return null;
	}
}
