<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class TermDelete
{
	public function __invoke(
		int $term_id,
		int $tt_id,
		string $taxonomy,
		\WP_Term $deleted_term,
		array $object_ids
	): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'delete_term', array(
				'taxonomy' => $taxonomy,
				'term_id' => $term_id,
				'object_ids' => $object_ids,
			) );
	}
}
