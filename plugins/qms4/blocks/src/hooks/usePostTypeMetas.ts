import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export interface TaxonomyMeta {
	taxonomy: string;
	object_name: string;
	name: string;
	label: string;
	query: string;
}

export interface PostTypeMeta {
	id: number;
	name: string;
	label: string;
	is_public: boolean;
	permalink_type: string;
	func_type: 'general' | 'event' | 'calendar';
	editor: 'block_editor' | 'classic_editor';
	cal_base_date: number;
	components: string[];
	taxonomies: TaxonomyMeta[];
	orderby: string;
	order: 'ASC' | 'DESC';
	new_date: number;
	new_class: string;
	posts_per_page: number;
	term_html: string;
}

export function usePostTypeMetas() {
	const [ postTypeMetas, setPostTypeMetas ] = useState< PostTypeMeta[] >(
		[]
	);

	useEffect( () => {
		( async () => {
			const postTypeMetas = await apiFetch< PostTypeMeta[] >( {
				path: '/qms4/v1/qms4/',
				method: 'GET',
			} );

			setPostTypeMetas( postTypeMetas ?? [] );
		} )();
	}, [] );

	return postTypeMetas;
}
