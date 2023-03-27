import React from 'react';
import type { FC } from 'react';
import { RichText } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import type { Timetable } from '../types';

type CapacityOption = '○' | '△' | '×';

interface Props {
	className?: string;

	timetable: Timetable;
	buttonText: string;

	setTimetable: ( timetable: Timetable ) => void;
	setButtonText: ( buttonText: string ) => void;
}

export const OverwriteTimetable: FC< Props > = ( {
	className,
	timetable,
	buttonText,
	setTimetable,
	setButtonText,
} ) => {
	const onLabelChange = ( index ) => {
		return ( label: string ) => {
			if ( timetable == null ) {
				return;
			}

			setTimetable(
				timetable.map( ( row, _index ) =>
					index == _index ? { ...row, label } : row
				)
			);
		};
	};

	const onCapacityChange = ( index ) => {
		return ( capacity: '○' | '△' | '×' ) => {
			if ( timetable == null ) {
				return;
			}

			setTimetable(
				timetable.map( ( row, _index ) =>
					index == _index ? { ...row, capacity } : row
				)
			);
		};
	};

	const onCommentChange = ( index ) => {
		return ( comment: string ) => {
			if ( timetable == null ) {
				return;
			}

			setTimetable(
				timetable.map( ( row, _index ) =>
					index == _index ? { ...row, comment } : row
				)
			);
		};
	};

	const removeRow = ( index ) => {
		return ( event ) => {
			event.preventDefault();
			if ( timetable == null ) {
				return;
			}
			setTimetable(
				timetable.filter( ( _, _index ) => index !== _index )
			);
		};
	};

	const addRow = ( event ) => {
		event.preventDefault();

		if ( timetable == null ) {
			setTimetable( [
				{ label: '', capacity: '○', available: true, comment: '' },
			] );
		} else {
			setTimetable( [
				...timetable,
				{ label: '', capacity: '○', available: true, comment: '' },
			] );
		}
	};

	const options: { value: CapacityOption; label: string }[] = [
		{ value: '○', label: '○' },
		{ value: '△', label: '△' },
		{ value: '×', label: '×' },
	];

	return timetable == null ? (
		<div className={ className }>
			<p className="qms4__event-overwrite__timetable-empty">
				タイムテーブル未登録
			</p>
		</div>
	) : (
		<div className={ className }>
			<table>
				<thead>
					<tr>
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
						<th>削除</th>
					</tr>
				</thead>
				<tbody>
					{ timetable.map( ( row, index ) => (
						<tr key={ index }>
							<RichText
								tagName="td"
								value={ row.label }
								onChange={ onLabelChange( index ) }
								placeholder="開催時間を入力"
							/>
							<td className="qms4__event-overwrite__overwrite-timetable__field-capacity">
								<SelectControl
									value={ row.capacity }
									options={ options }
									onChange={ onCapacityChange( index ) }
								/>
							</td>
							<td>
								<RichText
									tagName="p"
									className="qms4__event-overwrite__overwrite-timetable__comment"
									value={ row.comment }
									placeholder="備考を入力"
									onChange={ onCommentChange( index ) }
								/>
								<RichText
									tagName="div"
									className="qms4__event-overwrite__overwrite-timetable__button"
									value={ buttonText }
									placeholder="この時間で予約する"
									onChange={ setButtonText }
								/>
							</td>
							<td className="qms4__event-overwrite__overwrite-timetable__field-remove">
								<button
									title="行を削除"
									onClick={ removeRow( index ) }
								>
									× 削除
								</button>
							</td>
						</tr>
					) ) }
				</tbody>
			</table>
			<button
				className="qms4__event-overwrite__overwrite-timetable__button-add-row"
				title="行を追加"
				onClick={ addRow }
			>
				+ 行を追加
			</button>
		</div>
	);
};
