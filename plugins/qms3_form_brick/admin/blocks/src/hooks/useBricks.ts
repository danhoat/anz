import { useSelect } from '@wordpress/data';
import { StructureRow } from '../types/StructureRow';

export type WpPostStatus = 'publish' | 'future' | 'draft' | 'pending'
    | 'private' | 'trash' | 'auto-draft' | 'inherit';

export interface WpPost {
    type: string;
    id: number;
    slug: string;
    generated_slug: string;
    status: WpPostStatus,
    title: { raw: string, rendered: string };
    date: string;
    date_gmt: string;
    modified: string;
    modified_gmt: string;
    password: string;
    link: string
    description: string;
    count: number;
    parent: number;

    structure: StructureRow[][][];
}

export function useBricks(): WpPost[]  {
	return useSelect<WpPost[]>(select => (
		select('core')
			.getEntityRecords<WpPost[]>('postType', 'brick', { per_page: -1 })
		|| []
	), []);
}
