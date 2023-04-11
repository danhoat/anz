import { useSelect } from '@wordpress/data';

export interface WpPostType {
	name: string;
	labels: { [ key: string ]: string };
	slug: string;
	description: string;
	hierarchical: boolean;
	taxonomies: string[];
	viewable: boolean;
	visibility: { [ key: string ]: boolean };
	supports: { [ key: string ]: boolean };
	capabilities: { [ key: string ]: string };
}

export function usePostTypes(): WpPostType[] {
	return useSelect(
		( select ) =>
			select( 'core' ).getPostTypes< WpPostType[] >( { per_page: -1 } ) ||
			[],
		[]
	);
}
