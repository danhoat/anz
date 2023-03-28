export interface TaxonomyFilterCond {
	taxonomy: string | null;
	terms: string[];
	operator: 'IN' | 'NOT IN' | 'AND';
}

export interface Attributes {
	numColumnsPc: number;
	numColumnsSp: number;
	numPostsPc: number;
	numPostsSp: number;

	postType: string;
	orderby: 'menu_order' | 'post_date' | 'post_modified' | 'post_meta';
	order: 'ASC' | 'DESC';

	layout: 'card' | 'list' | 'text';

	taxonomyFilters: TaxonomyFilterCond[];
	excludePostIds: string;
	includePostIds: string;

	linkTarget: '_self' | '_blank' | '_parent' | '__custom';
	linkTargetCustom: string;
}
