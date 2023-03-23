// イベントカレンダー
jQuery( function ( $ ) {
	/**
	 * @param {string} endpoint
	 * @param {string} query
	 * @param {Date} current
	 * @returns {Promise<{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]>}
	 */
	function fetch_calendar_month( endpoint, param, current ) {
		console.log('call fetch_calendar_month:');
		return fetch(
			endpoint
				.replace( '%year%', current.getFullYear() )
				.replace( '%month%', current.getMonth() + 1 ) + `?${ param }`
		).then( ( response ) => response.json() );
	}

	/**
	 *
	 * @param {{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]} calendar_month
	 * @returns {string[]}
	 */
	function calendar_content( calendar_month ) {
		return calendar_month.map(
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

		const endpoint = $unit.data( 'endpoint' );

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

		$prev.on( 'click.prevMonth', async function ( event ) {
			event.preventDefault();
			current.setMonth( current.getMonth() - 1 );

			calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);

			$year.text( current.getFullYear() );
			$month.text( current.getMonth() + 1 );
			$month_name.text( month_names[ current.getMonth() ] );

			$calendar_body.html( calendar_content( calendar_month ) );
		} );

		$next.on( 'click.nextMonth', async function ( event ) {
			event.preventDefault();
			current.setMonth( current.getMonth() + 1 );

			calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);

			$year.text( current.getFullYear() );
			$month.text( current.getMonth() + 1 );
			$month_name.text( month_names[ current.getMonth() ] );

			$calendar_body.html( calendar_content( calendar_month ) );
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
