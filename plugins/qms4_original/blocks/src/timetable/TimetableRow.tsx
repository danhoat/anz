import React from 'react';
import type { FC } from 'react';
import { RichText } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import { useRef, useEffect } from '@wordpress/element';

type CapacityOption = '○' | '△' | '×';

interface Props {
	label: string;
	capacity: CapacityOption;
	comment: string;

	onLabelChange: ( label: string ) => void;
	onCapacityChange: ( capacity: CapacityOption ) => void;
	onCommentChange: ( comment: string ) => void;

	removeRow: () => void;

	buttonText: string;
	onButtonTextChange: ( buttonText: string ) => void;
}

export const TimetableRow: FC< Props > = ( {
	label,
	capacity,
	comment,

	onLabelChange,
	onCapacityChange,
	onCommentChange,

	removeRow,

	buttonText,
	onButtonTextChange,
} ) => {
	// 自動でフォーカスをあてる
	// const labelTd = useRef< HTMLTableCellElement >( null );
	// useEffect( () => {
	// 	labelTd.current && labelTd.current.focus();
	// }, [ labelTd.current ] );

	const options: { value: CapacityOption; label: string }[] = [
		{ value: '○', label: '○' },
		{ value: '△', label: '△' },
		{ value: '×', label: '×' },
	];

	return (
		<tr>
			<RichText
				tagName="td"
				value={ label }
				onChange={ onLabelChange }
				placeholder="開催時間を入力 line 566"
			/>
			<td>
				<SelectControl
					value={ capacity }
					options={ options }
					onChange={ onCapacityChange }
				/>
			</td>
			<td>
				<RichText
					tagName="p"
					className="comment"
					value={ comment }
					placeholder="備考を入力 xline 70"
					onChange={ onCommentChange }
				/>
				<RichText
					tagName="p"
					className="reserve_time--dummy"
					value={ buttonText }
					placeholder="この時間で予約する line 77"
					onChange={ onButtonTextChange }
				/>
			</td>
			<td>
				<button
					className="qms4__timetable__button-remove-row"
					title="行を削除 line 844"
					onClick={ removeRow }
				>
					× 削除 line 87
				</button>
			</td>
			<td>
				<button
					className="qms4__timetable__button-remove-row"
					title="行を削除 line 844"
					onClick={ removeRow }
				>
					× 削除 line 97
				</button>
			</td>
		</tr>
	);
};
