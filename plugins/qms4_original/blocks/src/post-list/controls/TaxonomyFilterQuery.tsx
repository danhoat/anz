import React from 'react';
import type { FC } from 'react';
import { Button } from '@wordpress/components';

import { useTaxonomyTerms } from '../useTaxonomyTerms';
import type { Attributes, TaxonomyFilterCond } from '../Attributes';
import { TaxonomyFilterQueryCond } from './TaxonomyFilterQueryCond';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const TaxonomyFilterQuery: FC< Props > = ( {
	attributes,
	setAttributes,
} ) => {
	const { postType, taxonomyFilters } = attributes;

	const updateCond = (
		index: number,
		filter: Partial< TaxonomyFilterCond >
	) => {
		const newTaxonomyFilters = taxonomyFilters.map( ( _filter, _index ) => {
			return index === _index ? { ..._filter, ...filter } : _filter;
		} );

		setAttributes( { taxonomyFilters: newTaxonomyFilters } );
	};

	const addCond = () => {
		setAttributes( {
			taxonomyFilters: [
				...taxonomyFilters,
				{ taxonomy: null, terms: [], operator: 'IN' },
			],
		} );
	};

	const removeCond = ( index: number ) => {
		const newTaxonomyFilters = taxonomyFilters.filter(
			( _, _index ) => index !== _index
		);

		setAttributes( { taxonomyFilters: newTaxonomyFilters } );
	};

	const [ taxonomies, getTerms ] = useTaxonomyTerms( postType );

	const styleUnit = {
		marginBottom: '8px',
	} as const;

	const styleNote = {
		display: 'flex',
		justifyContent: 'center',
		alignItems: 'center',
		height: '40px',
		marginBottom: '8px',
		color: 'var(--wp--preset--color--cyan-bluish-gray)',
		border: '1px dashed var(--wp--preset--color--cyan-bluish-gray)',
	} as const;

	return (
		<div style={ styleUnit }>
			{ taxonomyFilters.length > 0 ? (
				taxonomyFilters.map( ( cond, index ) => (
					<TaxonomyFilterQueryCond
						key={ `${ cond.taxonomy }-${ index }` }
						taxonomyFilterCond={ cond }
						updateCond={ ( cond ) => updateCond( index, cond ) }
						removeCond={ () => removeCond( index ) }
						taxonomies={ taxonomies }
						getTerms={ getTerms }
					/>
				) )
			) : (
				<div style={ styleNote }>絞り込み条件はありません</div>
			) }

			<Button variant="primary" onClick={ addCond }>
				条件を追加
			</Button>
		</div>
	);
};
