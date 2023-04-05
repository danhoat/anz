import { useEntityProp } from '@wordpress/core-data';

function fetchButtonText(
	postType: string | null
): readonly [ string, ( buttonText: string ) => void ] {
	const [ buttonText, setButtonText ]: [
		string | undefined,
		( buttonText: string ) => void
	] = useEntityProp( 'postType', postType, 'qms4__timetable__button_text' );

	return buttonText
		? [ buttonText, setButtonText ]
		: [ 'この時間で予約するtsx .t', setButtonText ];
}

export function useButtonText(
	postType: string | null
): readonly [ string, ( buttonText: string ) => void ] {
	return fetchButtonText( postType );
}
