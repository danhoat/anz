import { useSelect } from '@wordpress/data';

export interface WpTerm {
	taxonomy: string;
	id: number;
	name: string;
	description: string;
	slug: string;
	count: number;
	parent: number;
}

export function useTerms( taxonomy: string ): WpTerm[] {
	return useSelect< WpTerm[] >(
		( select ) =>
			select( 'core' ).getEntityRecords< WpTerm[] >(
				'taxonomy',
				taxonomy,
				{ per_page: -1 }
			) || [],
		[ taxonomy ]
	);
}
