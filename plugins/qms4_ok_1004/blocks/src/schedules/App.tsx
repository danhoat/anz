import React from 'react';
import { useSelect } from '@wordpress/data';
import { WPElement } from '@wordpress/element';

import { Calendar, NavOverwrite } from './components';
import { useYearMonth } from '../hooks/useYearMonth';
import { useSchedules } from './useSchedules';

import './editor.scss';

export function App(): WPElement {
	const postType = useSelect< string >(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);
	const postId = useSelect< number >(
		( select ) => select( 'core/editor' ).getCurrentPostId(),
		[]
	);

	const [ current, prevMonth, nextMonth ] = useYearMonth();
	const [ schedules, isSelected, toggleSelect ] = useSchedules(
		postType,
		postId
	);

	return (
		<>
			<Calendar
				current={ current }
				prevMonth={ prevMonth }
				nextMonth={ nextMonth }
				isSelected={ isSelected }
				toggleSelect={ toggleSelect }
			/>

			<NavOverwrite schedules={ schedules } />

			<input
				type="hidden"
				name="qms4__event__schedules"
				value={ JSON.stringify( schedules ) }
			/>
		</>
	);
}
