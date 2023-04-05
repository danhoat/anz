<?php

namespace QMS4\PostType;

use QMS4\PostType\EventPostType;
use QMS4\PostType\EventSchedulePostType;
use QMS4\PostType\GeneralPostType;
use QMS4\PostType\PostType;
use QMS4\PostTypeMeta\PostTypeMeta;


class PostTypeFactory
{
	/**
	 * @param    PostTypeMeta    $post_type_meta
	 * @return    PostType[]
	 */
	public function create( PostTypeMeta $post_type_meta )
	{
		switch ( $post_type_meta->func_type() ) {
			case 'general':
			case 'calendar':
				return array(
					new GeneralPostType( $post_type_meta ),
				);

			case 'event':
				return array(
					new GeneralPostType( $post_type_meta ),
					new EventSchedulePostType( $post_type_meta ),
				);

			default:
				throw new \RuntimeException( '不明な機能タイプです: $func_type: ' . $post_type_meta->func_type() );
		}
	}
}
