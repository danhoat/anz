import React from 'react';
import type { FC } from 'react';
import { SelectControl } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

import type { TaxonomyFilterCond } from '../Attributes';
import type { WpTaxonomy, WpTerm } from '../useTaxonomyTerms';

interface Props {
	taxonomyFilterCond: TaxonomyFilterCond;
	updateCond: ( cond: Partial< TaxonomyFilterCond > ) => void;
	removeCond: () => void;
	taxonomies: WpTaxonomy[];
	getTerms: ( taxonomy: string | null ) => WpTerm[];
}

export const TaxonomyFilterQueryCond: FC< Props > = ( {
	taxonomyFilterCond,
	updateCond,
	removeCond,
	taxonomies,
	getTerms,
} ) => {
	const cond = taxonomyFilterCond;
	const terms = getTerms( cond.taxonomy );

	const styleUnit = {
		position: 'relative',
		border: '1px dashed var(--wp--preset--color--cyan-bluish-gray)',
		padding: '8px',
		marginBottom: '8px',
	} as const;

	const styleButton = {
		position: 'absolute',
		top: 0,
		right: 0,
		zIndex: 1,
		border: 'none',
		background: 'none',
		cursor: 'pointer',
	} as const;

	return (
		<div style={ styleUnit }>
			<SelectControl
				label="タクソノミー"
				options={ [
					{ label: '-', value: '', disabled: true },
					...taxonomies.map( ( taxonomy ) => ( {
						label: taxonomy.name,
						value: taxonomy.slug,
					} ) ),
				] }
				value={ cond.taxonomy ?? '' }
				onChange={ ( taxonomy ) => updateCond( { taxonomy } ) }
			/>

			<SelectControl
				label="ターム"
				multiple
				options={ terms.map( ( term ) => ( {
					label: term.name,
					value: term.slug,
				} ) ) }
				value={ cond.terms }
				onChange={ ( terms ) => updateCond( { terms } ) }
			/>

			<ToggleGroupControl
				label="絞り込み条件"
				value={ cond.operator }
				onChange={ ( operator ) => updateCond( { operator } ) }
				isBlock
			>
				<ToggleGroupControlOption value="IN" label="IN" />
				<ToggleGroupControlOption value="AND" label="AND" />
				<ToggleGroupControlOption value="NOT IN" label="NOT IN" />
			</ToggleGroupControl>

			<button
				type="button"
				onClick={ removeCond }
				aria-label="条件を削除"
				style={ styleButton }
			>
				×
			</button>
		</div>
	);
};
