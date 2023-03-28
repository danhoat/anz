<?php

namespace QMS4\Action\Columns;


class DisplayColumnSitePartPostId
{
	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__site_part__post_id' ) { return; }

		echo trim( '
			<div>' . $post_id . '</div>
			<button
				type="button"
				class="qms4__site_part__post_id__shortcode"
				title="コードをコピー"
				data-post-id="' . $post_id . '"
			>&lt;/&gt;</button>
		' );
	}
}
