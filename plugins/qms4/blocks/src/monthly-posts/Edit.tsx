import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, PanelBody } from '@wordpress/components';
import { WPElement } from '@wordpress/element';
import { useMonthlyPosts } from '../hooks/useMonthlyPosts';
import { usePostTypes } from '../hooks/usePostTypes';

import './editor.scss';

export function Edit( { attributes, setAttributes } ): WPElement {
	const { postType } = attributes;

	const postTypes = usePostTypes();
	const options = [
		{
			disabled: true,
			label: '投稿タイプを選択',
			value: '',
		},
		...postTypes
			.filter( ( postType ) => postType.viewable )
			.map( ( postType ) => ( {
				label: postType.name,
				value: postType.slug,
			} ) ),
	];

	const thisYear = new Date().getFullYear();
	const [ year, posts, prevYear, nextYear ] = useMonthlyPosts(
		postType,
		thisYear
	);

	const blockProps = useBlockProps( {
		className: 'widget qms4__monthly-posts',
	} );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody>
					<SelectControl
						label="投稿タイプ"
						value={ postType }
						options={ options }
						onChange={ ( postType ) =>
							setAttributes( { postType } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="widget__main">
				<div className="widget__main-nav">
					<div className="widget__main-prev">
						<button type="button" onClick={ prevYear }>
							prev
						</button>
					</div>
					<div className="widget__main-year">{ year }</div>
					<div className="widget__main-next">
						{ year < thisYear && (
							<button type="button" onClick={ nextYear }>
								next
							</button>
						) }
					</div>
				</div>
				<ul className="widget__main-month">
					{ posts.map( ( { month, count } ) => (
						<li>
							<span>
								{ month }月（{ count }）
							</span>
						</li>
					) ) }
				</ul>
			</div>
		</div>
	);
}
