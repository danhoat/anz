<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class TermUpdate
{
	public function __invoke(
		int $term_id,
		int $tt_id,
		string $taxonomy,
		bool $update
	): void
	{
		if ( $update ) {
			UserActionLogger::channel( 'core' )
				->info( 'update_term', array(
					'taxonomy' => $taxonomy,
					'term_id' => $term_id,
				) );
		} else {
			UserActionLogger::channel( 'core' )
				->info( 'insert_term', array(
					'taxonomy' => $taxonomy,
					'term_id' => $term_id,
				) );
		}
	}
}
