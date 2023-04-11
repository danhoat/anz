import { dateI18n } from '@wordpress/date';
import type { Schedule } from './Schedule';

export function update(
	{ $dates, $timetable, $prev, $next, buttonLabel, reserveUrl },
	schedules: Schedule[],
	current: number
) {
	updateDates( $dates, schedules, current );
	updateTimetable( $timetable, buttonLabel, reserveUrl, schedules, current );
	updatePrev( $prev, schedules, current );
	updateNext( $next, schedules, current );
}

function updateDates( $dates, schedules: Schedule[], current: number ) {
	const schedule = schedules[ current ];
	$dates.val( schedule.date );
}

function updateTimetable(
	$timetable,
	buttonLabel,
	reserveUrl,
	schedules: Schedule[],
	current: number
) {
	const schedule = schedules[ current ];

	const eventName = schedule.title;
	const eventDate = dateI18n( 'Y年n月j日（D）', schedule.date );

	const htmls = schedule.timetable.map( ( row ) => {
		const capacityMark = ( () => {
			switch ( row.capacity ) {
				case '○':
					return '<img src="/wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
				case '△':
					return '<img src="/wp-content/themes/fabric/images/icon_few.png" alt="△" />';
				case '×':
					return '<img src="/wp-content/themes/fabric/images/icon_vacant.png" alt="×" />';
				default:
					return '<img src="/wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
			}
		} )();

		return `<div class="qms4__block__timetable__timetable-body-row">
			<div class="qms4__block__timetable__timetable-body-time">${ row.label }</div>
			<div class="qms4__block__timetable__timetable-body-capacity">${ capacityMark }</div>
			<div class="qms4__block__timetable__timetable-body-entry">
				<p class="qms4__block__timetable__timetable-body-comment">
					${ row.comment }
				</p>
				<div class="qms4__block__timetable__timetable-body-button">
					${
						row.capacity !== '×'
							? `<a href="${ decodeURI(
								reserveUrl
								+ '?event_name=' + htmlEscapeText( eventName )
								+ '&event_time=' + htmlEscapeText( eventDate ) + '%20' + htmlEscapeText( row.label )
							) }">${ buttonLabel }</a>`
						: ''
					}
				</div>
			</div>
		</div>`;
	} );
	$timetable.html( htmls );
}

function updatePrev( $prev, schedules: Schedule[], current: number ) {
	if ( current === 0 ) {
		$prev.hide();
	} else {
		const schedule = schedules[ current - 1 ];
		$prev.html( dateI18n( 'n月j日', schedule.date ) ).show();
	}
}

function updateNext( $next, schedules: Schedule[], current: number ) {
	if ( current === schedules.length - 1 ) {
		$next.hide();
	} else {
		const schedule = schedules[ current + 1 ];
		$next.html( dateI18n( 'n月j日', schedule.date ) ).show();
	}
}

function htmlEscapeText( escapeText: string ) {

	// エスケープした方が良い HTML 要素の
	const htmlEscapeTags = [
		'br',
		'p',
		'b',
		'em',
		'div',
		'pre',
		'span',
		'small',
		'strong',
	];

	// HTML 要素をエスケープする為の正規表現オブジェクトを生成
	// 生成される正規表現： /<\/?(Tag01|Tag2|Tag3|Tag04|Tag05|Tag06|Tag07|Tag08|Tag09)[^>]*>/g
	const regHtmlTag = new RegExp( '<\/?(' + htmlEscapeTags.join( '|' ) + ')[^>]*>', 'g' );

	return escapeText
		.replace( regHtmlTag, '' )  // HTML 要素のエスケープ
		.replace( /&/g, '＆' )  // URL の GET クエリの特殊文字と被るので全角変換
		.replace( /#/g, '＃' )  // URL の GET クエリの特殊文字と被るので全角変換
		.replace( /'/g, '&#096;' )
		.replace( /"/g, '&quot;' )  // URL が途中で終了してしまうのでエスケープ
		.replace( /'/g, '&#39;' )
		.replace( /</g, '&lt;' )
		.replace( />/g, '&gt;' )  // a タグが途中で終了してしまうのでエスケープ
	;
}
