import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

import { useButtonText } from './useButtonText';
import { useTimetable } from './useTimetable';
import { TimetableRow } from './TimetableRow';

import './editor.scss';

type CapacityOption = '○' | '△' | '×';

interface TimetableRow {
	label: string;
	capacity: CapacityOption;
	comment: string;
}

export function Edit( { isSelected } ) {
	const postType = useSelect< string >(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);

	const [ buttonText, setButtonText ] = useButtonText( postType );
	const [ timetable, change, addRow, removeRow ] = useTimetable( postType );

	return (
		<div { ...useBlockProps() }>
			<table>
				<thead>
					<th>
						開催
						<br className="sp" />
						時間
					</th>
					<th>
						空席
						<br className="sp" />
						状況
					</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</thead>
				<tbody>
					{ timetable.map( ( row, index ) => (
						<TimetableRow
							key={ index }
							label={ row.label }
							capacity={ row.capacity }
							comment={ row.comment }
							onLabelChange={ ( label ) =>
								change.label( index, label )
							}
							onCapacityChange={ ( capacity ) =>
								change.capacity( index, capacity )
							}
							onCommentChange={ ( comment ) =>
								change.comment( index, comment )
							}
							removeRow={ removeRow( index ) }
							buttonText={ buttonText }
							onButtonTextChange={ setButtonText }
						/>
					) ) }
				</tbody>
			</table>
			{ isSelected && (
				<button
					className="qms4__timetable__button-add-row"
					title="行を追加"
					onClick={ addRow }
				>
					Add Row
				</button>
			) }
			<dl className="ex clearfix">
				<dt>○</dt>
				<dd>予約可</dd>
				<dt>△</dt>
				<dd>残りわずか</dd>
				<dt>×</dt>
				<dd>満席</dd>
			</dl>
		</div>
	);
}
