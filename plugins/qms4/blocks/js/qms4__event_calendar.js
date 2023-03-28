// イベントカレンダー
jQuery( function ( $ ) {
	/**
	 * @param {string} endpoint
	 * @param {string} query
	 * @param {Date} current
	 * @returns {Promise<{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]>}
	 */

	async  function fetch_calendar_month( endpoint, param, current ) {
		console.log('call fetch_calendar_month:');
		console.log('current: ', current);

		let api_url = endpoint
		.replace( '%year%', current.getFullYear() )
		.replace( '%month%', current.getMonth() + 1 ) + `?${ param }`;
		console.log('api_url: ', api_url);
		var result = await fetch(
			api_url
		).then(  ( response ) => {
        	var result =    response.json();
			return result;
		} );
		console.log('result:', result);
		return result;
	}

	/**
	 *
	 * @param {{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]} calendar_month
	 * @returns {string[]}
	 */
	function calendar_content( calendar_month ) {

		return calendar_month.data.map(
			( { date: date_str, date_class, schedules, enable } ) => {
				const date = new Date( date_str );

				return `<div
				class="qms4__block__event-calendar__body-cell ${ date_class.join( ' ' ) }"
				data-date="${ date_str }"
			>
					${
						! enable || schedules.length === 0
							? `<span class="qms4__block__event-calendar__day-title js__qms4__block__event-calendar__display-header">
									${ date.getDate() }
								</span>`
							: `<button class="qms4__block__event-calendar__day-title" >
									${ date.getDate() }
								</button>`
					}
				</div>`;
			}
		);
	}

	function list_content( date_str, schedules ) {
		const ymd = date_str.replace( /\-/g, '' );

		return schedules.map(
			( { id, permalink, title, img, area, terms } ) => {
				console.log( { area, terms } );

				return `<div class="qms4__block__event-calendar__display-list-item">
				<a href="${ permalink }?ymd=${ ymd }">
					<div class="qms4__block__event-calendar__display-list-item__thumbnail">
						${ img }
					</div>
					<div class="qms4__block__event-calendar__display-list-item__inner">
						<div class="qms4__block__event-calendar__display-list-item-title">
							${ title }
						</div>

						${
							area
								? `
								<ul class="qms4__block__event-calendar__display-list-item__icons">
									<li class="qms4__block__event-calendar__display-list-item__icon">
										${ area }
									</li>
								</ul>
							`
								: ''
						}

						${
							terms
								? Object.entries( terms )
										.map(
											( [ taxonomy, _terms ] ) =>
												`<ul class="qms4__block__event-calendar__display-list-item__icons">
									${ _terms
										.map(
											( { name, color } ) =>
												`<li
													class="qms4__block__event-calendar__display-list-item__icon"
													${ color ? `style="background-color:${ color };border-color:${ color }"` : '' }
												>
											${ name }
										</li>`
										)
										.join( '' ) }
								</ul>`
										)
										.join( '' )
								: ''
						}
					</div>
				</a>
			</div>`;
			}
		);
	}

	const month_names = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	];
	console.log('set left & right');

	var left = new Date().getMonth() +1  ; // 0 -> 11


	console.log('left: ', left);
	var right = left +1;
	console.log('right: ', right);


	const dows = [ '日', '月', '火', '水', '木', '金', '土' ];

	$( '.js__qms4__block__event-calendar' ).each( function () {
		/** @type {{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]} */
		let calendar_month = [];

		const $unit = $( this );
		const $prev = $unit.find(
			'.js__qms4__block__event-calendar__button-prev'
		);
		const $next = $unit.find(
			'.js__qms4__block__event-calendar__button-next'
		);
		const $year = $unit.find(
			'.js__qms4__block__event-calendar__month-title__year'
		);
		const $month = $unit.find(
			'.js__qms4__block__event-calendar__month-title__month'
		);
		const $month_name = $unit.find(
			'.js__qms4__block__event-calendar__month-title__month-name'
		);
		const $calendar_body = $unit.find(
			'.js__qms4__block__event-calendar__calendar-body'
		);

		const $calendar_body_next = $unit.find(
			'.js__qms4__block__event-calendar__calendar-body-next'
		);
		const $next_month = $unit.find(
			'.js-next-month-title'
		);



		const $display_header = $unit.find(
			'.js__qms4__block__event-calendar__display-header'
		);
		const $display_list = $unit.find(
			'.js__qms4__block__event-calendar__display-list'
		);

		const param = new URLSearchParams( $unit.data( 'query-string' ) );

		const showArea = !! $unit.data( 'show-area' );
		const showTerms = !! $unit.data( 'show-terms' );
		const taxonomies = showTerms
			? $unit.data( 'taxonomies' ).split( ',' ).filter( Boolean )
			: [];

		param.set( 'fields[area]', showArea ? 1 : 0 );
		param.set( 'fields[taxonomies]', taxonomies.join( ',' ) );
		console.log('param: ', param);
		const endpoint = $unit.data( 'endpoint' );
		console.log('endpoint: ', endpoint);

		/**
		 * 月初日を返す
		 *
		 * @param stringDate 文字列の日付
		 * @return {Date} 月初日にしたDateオブジェクト
		 */
		const getFirstDay = function (stringDate) {
			const date = new Date(stringDate);
			date.setDate(1);
			return date;
		}

		// カレントの日付を生成
		const current = getFirstDay( $unit.data( 'current' ) );
		console.log('current: ', current);
		$prev.on( 'click.prevMonth', async function ( event ) {

			console.log('click Prev');
			console.log('update left & right');
			event.preventDefault();
			right = right -2;
			var month = left -1;
			left = left - 2;



			current.setMonth( current.getMonth() - 1 );
				endpoint,
				calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);
			console.log('calendar_month: ', calendar_month);
			$year.text( current.getFullYear() );
			$month.text( left );
			$month_name.text( month_names[ current.getMonth() ] );
			console.log('calendar_content: ', calendar_content);

			console.log('next month body:');
			$calendar_body.html( calendar_content( calendar_month[0]) );
			console.log('$calendar_body_next: ', $calendar_body_next);


			$next_month.text( right );

			var  html = calendar_content( calendar_month[1] );
			console.log('next html: ', html);
			$calendar_body_next.html( html );
		} );

		$next.on( 'click.nextMonth', async function ( event ) {
			console.log('update left & right');
			param.set('event', 'next');
			console.log('click Next');
			event.preventDefault();



			right = right+ 2;
			var month = left + 1;
			left = left + 2;

			console.log(' Left', left);
			console.log('right:', right);
			current.setMonth( month );
			console.log(' currentbeforecallfetch:', current);

			calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);
			$year.text( current.getFullYear() );

			$month.text( left );

			$month_name.text( month_names[ current.getMonth() ] );
			console.log('calendar_content 0: ', calendar_month[0]);
			console.log('calendar_content 1: ', calendar_month[1]);

			$calendar_body.html( calendar_content( calendar_month[0]) );

			console.log('set body next month');
			$year.text( current.getFullYear() );


			$next_month.text( right  );

			$month_name.text( month_names[ current.getMonth() ] );


			$calendar_body_next.html( calendar_content( calendar_month[1] ) );



		} );

		$calendar_body.on(
			'click',
			'button.qms4__block__event-calendar__day-title',
			function ( event ) {
				event.preventDefault();
				const $button = $( this );
				const $cell = $button.parents(
					'.qms4__block__event-calendar__body-cell'
				);

				const date_str = $cell.data( 'date' );
				const date = new Date( date_str );

				$display_header.text(
					`${ date.getMonth() + 1 }月${ date.getDate() }日（${
						dows[ date.getDay() ]
					}）のイベント`
				);

				const schedules = calendar_month.find(
					( { date } ) => date === date_str
				).schedules;
				$display_list.html( list_content( date_str, schedules ) );
			}
		);

		fetch_calendar_month( endpoint, param, current ).then(
			( _calendar_month ) => {
				calendar_month = _calendar_month;
			}
		);
	} );
} );
