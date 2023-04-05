import { useSelect } from '@wordpress/data';

export function useDateTimeFormat(
	defaultDateFormat = 'Y/n/j',
	defaultTimeFormat = 'H:i'
): [ string, string ] {
	const { date_format, time_format } = useSelect(
		( select ) => select( 'core' ).getSite() ?? {}
	);

	return [
		`${ date_format }` ?? defaultDateFormat,
		`${ time_format }` ?? defaultTimeFormat,
	];
}
