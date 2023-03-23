export interface Schedule {
	post_id: number;
	active: boolean;
	date: string;
	overwrite: boolean;
	title: string;
	timetable: {
		label: string;
		available: boolean;
		capacity: '○' | '△' | '×';
		comment: string;
	}[];
}
