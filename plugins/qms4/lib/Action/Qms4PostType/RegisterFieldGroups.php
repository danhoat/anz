<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\PostMeta\Memo;
use QMS4\PostMeta\OutputSetting;
use QMS4\PostMeta\PostTypeConfig;
use QMS4\PostMeta\RelPostType;


class RegisterFieldGroups
{
	public function __invoke()
	{
		( new Memo( 'qms4' ) )->register_meta();
		( new Memo( 'qms4__block_template' ) )->register_meta();
		( new Memo( 'qms4__site_part' ) )->register_meta();
		( new Memo( 'qms4__area_master', 'normal' ) )->register_meta();

		( new OutputSetting() )->register_meta();
		( new PostTypeConfig() )->register_meta();
		( new RelPostType() )->register_meta();
	}
}
