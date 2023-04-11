import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, SelectControl, PanelBody } from '@wordpress/components';
import { WPElement } from '@wordpress/element';

import { useTaxonomies } from '../hooks/useTaxonomies';
import { useTerms } from '../hooks/useTerms';

import './editor.scss';

export function Edit( { attributes, setAttributes } ): WPElement {
	const { taxonomy, showCount } = attributes;

	const taxonomies = useTaxonomies();
	const terms = useTerms( taxonomy ).filter( ( term ) => term.count != 0 );

	const options = [
		{
			disabled: true,
			label: 'タクソノミーを選択',
			value: '',
		},
		...taxonomies.map( ( taxonomy ) => ( {
			label: taxonomy.name,
			value: taxonomy.slug,
		} ) ),
	];

	const blockProps = useBlockProps( {
		className: 'widget qms4__term-list',
	} );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody>
					<SelectControl
						label="タクソノミー"
						value={ taxonomy }
						options={ options }
						onChange={ ( taxonomy ) =>
							setAttributes( { taxonomy } )
						}
					/>

					<ToggleControl
						label="投稿数を表示する"
						checked={ showCount }
						onChange={ ( showCount ) =>
							setAttributes( { showCount } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="widget__main">
				{ terms.length == 0 ? (
					<p className="widget__notice">
						タームが一つも登録されていません
					</p>
				) : (
					<ul className="widget__main-list">
						{ terms.map( ( term ) => (
							<li>
								<a href="#">
									<span className="widget__main-list__term-name">
										{ term.name }
									</span>
									{ showCount && (
										<span className="widget__main-list__term-count">
											{ term.count }
										</span>
									) }
								</a>
							</li>
						) ) }
					</ul>
				) }
			</div>
		</div>
	);
}
