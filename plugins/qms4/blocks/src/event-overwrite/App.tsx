import React from 'react';
import type { FC } from 'react';
import { useAttributes } from './useAttributes';
import {
	OverwriteEnable,
	ParentTitle,
	ParentTimetable,
	OverwriteTitle,
	OverwriteTimetable,
} from './components';

import './editor.scss';

export const App: FC = () => {
	const [ attributes, setAttributes ] = useAttributes(
		/** @ts-ignore */
		window.QMS4__META_BOX__EVENT_OVERWRITE
	);

	const toggleOverwriteEnable = () => {
		setAttributes( {
			overwriteEnable: ! attributes.overwriteEnable,
			timetable:
				! attributes.overwriteEnable && attributes.timetable == null
					? [
							{
								label: '',
								capacity: '○',
								available: true,
								comment: '',
							},
					  ]
					: attributes.timetable,
		} );
	};

	const clone = ( event ) => {
		event.preventDefault();
		setAttributes( {
			overwriteEnable: true,
			timetable: attributes.parentTimetable && [
				...attributes.parentTimetable,
			],
		} );
	};

	return (
		<div className="qms4__event-overwrite">
			<OverwriteEnable
				className="qms4__event-overwrite__overwrite-enable"
				overwriteEnable={ attributes.overwriteEnable }
				toggleOverwriteEnable={ toggleOverwriteEnable }
			/>

			<div className="qms4__event-overwrite__main">
				<div
					className={ `qms4__event-overwrite__parent ${
						! attributes.overwriteEnable
							? 'qms4__event-overwrite__parent--active'
							: ''
					}` }
				>
					<h3>マスター設定</h3>
					<ParentTitle
						className="qms4__event-overwrite__parent-title"
						title={ attributes.parentTitle }
					/>
					<ParentTimetable
						className="qms4__event-overwrite__parent-timetable"
						timetable={ attributes.parentTimetable }
						buttonText={ attributes.parentButtonText }
					/>
				</div>

				<div className="qms4__event-overwrite__button-clone">
					<button onClick={ clone }>マスター設定を複製</button>
				</div>

				<div
					className={ `qms4__event-overwrite__overwrite ${
						attributes.overwriteEnable
							? 'qms4__event-overwrite__overwrite--active'
							: ''
					}` }
				>
					<h3>個別設定</h3>
					<OverwriteTitle
						className="qms4__event-overwrite__overwrite-title"
						parentTitle={ attributes.parentTitle }
						title={ attributes.title }
						setTitle={ ( title ) => setAttributes( { title } ) }
					/>
					<OverwriteTimetable
						className="qms4__event-overwrite__overwrite-timetable"
						timetable={ attributes.timetable }
						buttonText={ attributes.buttonText }
						setTimetable={ ( timetable ) =>
							setAttributes( { timetable } )
						}
						setButtonText={ ( buttonText ) =>
							setAttributes( { buttonText } )
						}
					/>
				</div>
			</div>

			<div className="qms4__event-overwrite__other-schedules">
				<h3>この設定を他の日程にも反映させる</h3>
			</div>

			<input
				type="hidden"
				name="qms4__event__event-overwrite"
				value={ JSON.stringify( {
					overwriteEnable: attributes.overwriteEnable,
					title: attributes.title,
					timetable: attributes.timetable,
					buttonText: attributes.buttonText,
				} ) }
			/>
		</div>
	);
};
