import React from 'react';
import type { FC } from 'react';
import type { Timetable } from '../types';

interface Props {
	className?: string;
	timetable: Timetable;
	buttonText: string;
}

export const ParentTimetable: FC< Props > = ( {
	className,
	timetable,
	buttonText,
} ) => {
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
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{ timetable.map( ( row, index ) => (
						<tr key={ index }>
							<td>{ row.label }</td>
							<td className="qms4__event-overwrite__parent-timetable__field-capacity">
								{ row.capacity }
							</td>
							<td>
								<p className="qms4__event-overwrite__parent-timetable__comment">
									{ row.comment }
								</p>
								<div className="qms4__event-overwrite__parent-timetable__button">
									{ buttonText } ooo
								</div>
							</td>
							<td className="qms4__event-overwrite__parent-timetable__field-remove">
								&nbsp;
							</td>
						</tr>
					) ) }
				</tbody>
			</table>
		</div>
	);
};
