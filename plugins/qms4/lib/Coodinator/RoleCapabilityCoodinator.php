<?php

namespace QMS4\Coodinator;

use QMS4\Action\RoleCapability\AddBulkAction;
use QMS4\Action\RoleCapability\AddCapabilities;
use QMS4\Action\RoleCapability\AddRowAction;
use QMS4\Action\RoleCapability\CloneCapabilities;
use QMS4\Action\RoleCapability\RemoveCapabilities;
use QMS4\Action\RoleCapability\ReplaceCapabilities;
use QMS4\Action\RoleCapability\ResetAllCapabilities;
use QMS4\Action\RoleCapability\ResetCapabilities;
use QMS4\Action\RoleCapability\ShowAdminNotice;


class RoleCapabilityCoodinator
{
	public function __construct()
	{
		// 権限 付与/変更/複製/削除
		add_action( 'save_post', new AddCapabilities(), 20, 2 );
		add_action( 'acf/save_post', new ReplaceCapabilities(), 8 );
		add_action( 'dp_duplicate_post', new CloneCapabilities(), 20, 3 );
		add_action( 'before_delete_post', new RemoveCapabilities(), 10, 2 );


		// アクション： 権限リセット
		$action = 'reset_caps';
		add_filter( 'post_row_actions', new AddRowAction(), 10, 2 );
		add_action( "admin_action_{$action}", new ResetCapabilities() );


		// アクション： 権限一括リセット
		$screen = 'edit-qms4';
		add_filter( "bulk_actions-{$screen}", new AddBulkAction() );
		add_filter( "handle_bulk_actions-{$screen}", new ResetAllCapabilities(), 10, 3 );
		add_action( 'admin_notices', new ShowAdminNotice() );
	}
}
