import { useSelect } from '@wordpress/data';
import { useCallback } from '@wordpress/element';

export interface WpTaxonomy {
	name: string;
	slug: string;
	description: string;
	hierarchical: boolean;
	types: string[];
}

export interface WpTerm {
	taxonomy: string;
	id: number;
	name: string;
	description: string;
	slug: string;
	count: number;
	parent: number;
}

export interface TaxonomyTerm {
	taxonomy: WpTaxonomy;
	terms: WpTerm[];
}

export function useTaxonomyTerms(
	postType: string
): readonly [ WpTaxonomy[], ( taxonomy: string | null ) => WpTerm[] ] {
	const taxonomyTerms: TaxonomyTerm[] = useSelect(
		( select ) => {
			const taxonomies =
				select( 'core' ).getTaxonomies( { per_page: -1 } ) ?? [];

			return taxonomies
				.filter( ( taxonomy ) => taxonomy.types.includes( postType ) )
				.map( ( taxonomy ) => {
					const terms =
						select( 'core' ).getEntityRecords(
							'taxonomy',
							taxonomy.slug,
							{ per_page: -1 }
						) ?? [];
					return { taxonomy, terms };
				} );
		},
		[ postType ]
	);

	const taxonomies = taxonomyTerms.map(
		( taxonomyTerm ) => taxonomyTerm.taxonomy
	);

	const getTerms = useCallback(
		( taxonomy: string | null ) => {
			if ( taxonomy == null ) {
				return [];
			}

			return (
				taxonomyTerms.find(
					( taxonomyTerm ) => taxonomyTerm.taxonomy.slug === taxonomy
				)?.terms ?? []
			);
		},
		[ taxonomyTerms ]
	);

	return [ taxonomies, getTerms ] as const;
}
