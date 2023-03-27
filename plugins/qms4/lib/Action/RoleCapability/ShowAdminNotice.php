<?php

namespace QMS4\Action\RoleCapability;


class ShowAdminNotice
{
	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		$screen = get_current_screen();

		if ( $screen->id !== 'edit-qms4' || empty( $_GET[ 'reset_all_caps' ] ) ) {
			return;
		}

		$num_posts = $_GET[ 'reset_all_caps' ];

?>
		<div class="notice notice-success is-dismissible">
			<p><?= $num_posts ?>件のカスタム投稿タイプの権限をリセットしました。</p>
		</div>
<?php
	}
}
