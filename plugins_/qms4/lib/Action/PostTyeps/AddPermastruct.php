<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


/**
 * パーマリンクの形式を設定する
 */
class AddPermastruct
{
	/** @var    string[] */
	private $post_types;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$post_types = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->permalink_type() === 'post_id' ) {
				$post_types[] = $post_type_meta->name();
			}
		}

		$this->post_types = $post_types;
	}

	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		foreach ( $this->post_types as $post_type ) {
			add_rewrite_rule(
				"{$post_type}/([^/]+)/?$",
				"index.php?post_type={$post_type}&p=\$matches[1]",
				'top'
			);
		}
	}
}
