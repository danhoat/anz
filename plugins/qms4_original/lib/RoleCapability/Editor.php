<?php

namespace QMS4\RoleCapability;

use QMS4\RoleCapability\RoleCapability;


/**
 * 編集者
 */
class Editor extends RoleCapability
{
	/**
	 * @return    string
	 */
	protected function role_name(): string
	{
		return 'editor';
	}

	/**
	 * @param    string    $capability_type
	 * @return    array<string,bool>
	 */
	protected function initial_grants( string $capability_type ): array
	{
		return array(
			"{$capability_type}__create_posts" => true,
			"{$capability_type}__edit_post" => true,
			"{$capability_type}__read_post" => true,
			"{$capability_type}__delete_post" => true,
			"{$capability_type}__edit_posts" => true,
			"{$capability_type}__edit_others_posts" => true,
			"{$capability_type}__delete_posts" => true,
			"{$capability_type}__publish_posts" => true,
			"{$capability_type}__read_private_posts" => true,

			"{$capability_type}__delete_private_posts" => true,
			"{$capability_type}__delete_published_posts" => true,
			"{$capability_type}__delete_others_posts" => true,
			"{$capability_type}__edit_private_posts" => true,
			"{$capability_type}__edit_published_posts" => true,

			"{$capability_type}__manage_terms" => true,
			"{$capability_type}__edit_terms" => true,
			"{$capability_type}__delete_terms" => true,
			"{$capability_type}__assign_terms" => true,
		);
	}
}
