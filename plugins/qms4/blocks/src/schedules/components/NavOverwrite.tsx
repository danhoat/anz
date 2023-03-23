import React from 'react';
import type { FC } from 'react';
import { useSelect } from '@wordpress/data';
import { date, dateI18n } from '@wordpress/date';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import type { Schedule } from '../useSchedules';

interface Props {
	schedules: Schedule[];
}

export const NavOverwrite: FC< Props > = ( { schedules } ) => {
	const [ adminUrl, setAdminUrl ] = useState( '' );
	useEffect( () => {
		( async () => {
			const adminUrl = await apiFetch< string >( {
				path: '/qms4/v1/admin-url/',
				method: 'GET',
			} );
			setAdminUrl( adminUrl );
		} )();
	}, [] );

	const today = date( 'Y-m-d', new Date() );
	const filtered = schedules.filter(
		( schedule ) =>
			schedule.post_id != null &&
			schedule.active &&
			schedule.date >= today
	);

	const [ postId, setPostId ] = useState(
		filtered[ 0 ] ? filtered[ 0 ].post_id : 0
	);

	useEffect( () => {
		if ( postId === 0 && filtered.length > 0 ) {
			setPostId( filtered[ 0 ].post_id! );
		}
	}, [ filtered ] );

	return filtered.length > 0 ? (
		<nav className="qms4__event-calendar__nav-overwrite">
			<select
				value={ postId }
				onChange={ ( event ) => setPostId( +event.target.value ) }
			>
				{ filtered.map( ( schedule ) => (
					<option
						key={ schedule.post_id }
						value={ schedule.post_id || undefined }
					>
						{ dateI18n( 'Y年n月j日（D）', schedule.date ) }
					</option>
				) ) }
			</select>
			<a
				className="button"
				href={ `${ adminUrl }post.php?post=${ postId }&action=edit` }
				target="_blank"
			>
				個別設定
			</a>
		</nav>
	) : null;
};
