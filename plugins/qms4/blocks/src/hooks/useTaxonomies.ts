import { useSelect } from '@wordpress/data';

export interface WpTaxonomy {
	name: string;
	slug: string;
	description: string;
	hierarchical: boolean;
	types: string[];
}

export function useTaxonomies( postType?: string ): WpTaxonomy[] {
	const taxonomies: WpTaxonomy[] = useSelect(
		( select ) =>
			select( 'core' ).getTaxonomies< WpTaxonomy[] >( {
				per_page: -1,
			} ) || [],
		[]
	);

	return postType
		? taxonomies.filter( ( taxonomy ) =>
				taxonomy.types.includes( postType )
		  )
		: taxonomies;
}
