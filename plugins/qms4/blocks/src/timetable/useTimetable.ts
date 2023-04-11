import { useEntityProp } from '@wordpress/core-data';

type CapacityOption = '○' | '△' | '×';

interface TimetableRow {
	label: string;
	capacity: CapacityOption;
	available: boolean;
	comment: string;
}

const defaultTimetable: readonly TimetableRow[] = [
	{
		label: '',
		capacity: '○',
		available: true,
		comment: '',
	},
	{
		label: '',
		capacity: '○',
		available: true,
		comment: '',
	},
];

function fetchTimetable(
	postType: string | null
): readonly [ readonly TimetableRow[], ( timetable: TimetableRow[] ) => void ] {
	const [ timetable, setTimetable ]: [
		TimetableRow[] | undefined,
		( timetable: TimetableRow[] ) => void
	] = useEntityProp( 'postType', postType, 'qms4__timetable' );

	return timetable == null
		? [ defaultTimetable, setTimetable ]
		: [ timetable, setTimetable ];
}

export function useTimetable( postType: string | null ) {
	const [ timetable, setTimetable ] = fetchTimetable( postType );

	const change = {
		label: ( index: number, label: string ) => {
			const newTimetable = timetable.map( ( row, _index ) => {
				return index === _index ? { ...row, label } : row;
			} );
			setTimetable( newTimetable );
		},

		capacity: ( index: number, capacity: CapacityOption ) => {
			const newTimetable = timetable.map( ( row, _index ) => {
				return index === _index ? { ...row, capacity } : row;
			} );
			setTimetable( newTimetable );
		},

		comment: ( index: number, comment: string ) => {
			const newTimetable = timetable.map( ( row, _index ) => {
				return index === _index ? { ...row, comment } : row;
			} );
			setTimetable( newTimetable );
		},
	};

	const addRow = () => {
		const newTimetable: TimetableRow[] = [
			...timetable,
			{
				label: '',
				capacity: '○',
				available: true,
				comment: '',
			},
		];
		setTimetable( newTimetable );
	};

	const removeRow = ( index: number ) => {
		return () => setTimetable( timetable.filter( ( _, i ) => index != i ) );
	};

	return [ timetable, change, addRow, removeRow ] as const;
}
