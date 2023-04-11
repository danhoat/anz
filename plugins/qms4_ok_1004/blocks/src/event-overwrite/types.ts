export interface Attributes {
	overwriteEnable: boolean;
	title: string;
	timetable: Timetable;
	buttonText: string;
	parentTitle: string;
	parentTimetable: Timetable;
	parentButtonText: string;
}

export interface TimetableRow {
	label: string;
	capacity: '○' | '△' | '×';
	available: boolean;
	comment: string;
}

export type Timetable = TimetableRow[] | null;
