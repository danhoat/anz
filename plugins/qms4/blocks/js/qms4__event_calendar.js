// イベントカレンダー
jQuery( function ( $ ) {
	/**
	 * @param {string} endpoint
	 * @param {string} query
	 * @param {Date} current
	 * @returns {Promise<{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]>}
	 */
	function fetch_calendar_month( endpoint, param, current ) {
		var t = endpoint
				.replace( '%year%', current.getFullYear() )
				.replace( '%month%', current.getMonth() + 1 ) + `?${ param }`;
				console.log('t: ',t);
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
	function calendar_content( calendar_month, archive_link = '' ) {

		return calendar_month.map(
			( { date: date_str, date_class, schedules, enable } ) => {
				const date = new Date( date_str );

				var  set_color = '';
				if( schedules.length && schedules[0].terms.fair__special[0] ){
					 set_color = ` style = " background:  ${schedules[0].terms.fair__special[0].color}" `;
				}
				var date_info =  ` ${ archive_link !== ''
							? `<a href= "${archive_link}?ymd=${ date.getFullYear() }-${ ("0" + (date.getMonth() + 1)).slice(-2) }-${  ("0" + date.getDate() ).slice(-2)  }"  ${set_color}> ${  date.getDate()} </a>`
							: `<button class="qms4__block__event-calendar__day-title" ${set_color} >
									${ date.getDate() }
								</button>`
					}`;
				return `<div
				class="qms4__block__event-calendar__body-cell ${ date_class.join( ' ' ) }"
				data-date="${ date_str }"
			>
					${
						! enable || schedules.length === 0
							? `<span class="qms4__block__event-calendar__day-title js__qms4__block__event-calendar__display-header">
									${ date.getDate() }
								</span>`
							: `
									${ date_info }
								`
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
	var left = new Date().getMonth() +1  ; // 0 -> 11
	var right = left +1;
	function valiate_pre_month(month){
		if( month > 2){
			month = month - 2;
		} else if(month == 2 ){
			month = 12;
		} else if(month == 1) {
			month = 11;

		}
		return month;
	}
	function valiate_next_month(month){
		if( month < 11 ){
			month = month + 2;
		} else if(month == 11 ){
			month = 1;
		} else if(month == 12) {
			month = 2;

		}
		return month;
	}


	$( '.js__qms4__block__event-calendar' ).each( function () {
		/** @type {{ date: string, date_class: string[], schedules: { id: number, title: string }[] }[]} */
		let calendar_month = [];

		const $unit = $( this );
		const showArea = !! $unit.data( 'show-area' );
		const showPosts = !! $unit.data( 'data-show-posts' );
		const showTerms = !! $unit.data( 'show-terms' );
		const style 	=  $unit.data('data-set-style' );
		const archive_link = $unit.attr('data-set-archive-link');
		const taxonomies = showTerms
			? $unit.data( 'taxonomies' ).split( ',' ).filter( Boolean )
			: [];

		const param = new URLSearchParams( $unit.data( 'query-string' ) );
		console.log('unit: ', $unit);
		console.log('data-set-archive-link: ', archive_link );
		param.set( 'fields[area]', showArea ? 1 : 0 );
		param.set( 'fields[taxonomies]', taxonomies.join( ',' ) );

		param.set('style',$unit.attr('data-set-style'));


		const $prev = $unit.find(
			'.js__qms4__block__event-calendar__button-prev'
		);
		var $next = $unit.find(
			'.js__qms4__block__event-calendar__button-next'
		);
		var $year = $unit.find(
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
		// right block
		const $right_calendar_body = $unit.find(
			'.js__qms4__block__event-calendar__calendar-body-right'
		);

		//end right

		const $calendar_body_next = $unit.find(
			'.js__qms4__block__event-calendar__calendar-body-right'
		);

		const $new_style_next =  $unit.find(
			'.js__qms4__block__event-calendar__button-next-2month'
		);
		const $next_year = $unit.find(
			'.js__qms4__block__event-calendar__month-title__next-year'
		);
		const $next_month = $unit.find(
			'.js-next-month-title'
		);

		const $new_prev = $unit.find(
			'.js__qms4__block__event-calendar__button-prev-new'
		);



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

			$calendar_body.html( calendar_content( calendar_month[0] ) );
		} );


		$new_prev.on( 'click.prevMonth', async function ( event ) {

			event.preventDefault();
			left 	= valiate_pre_month(left);
			right = valiate_pre_month(right);

			month = left-1;
			current.setMonth(month);

			if(left == 11){
				current.setFullYear(current.getFullYear() - 1);
			}

			var	calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);
			console.log("calendar_month: ", calendar_month);
			$year.text( current.getFullYear() );
			$month.text( left );
			$month_name.text( month_names[ current.getMonth() ] );


			$calendar_body.html( calendar_content( calendar_month[0].data) , archive_link);



			$next_month.text( right );
			$next_year.text( current.getFullYear() );

			if(right == 12){
				$next_year.text( current.getFullYear() +1 );
			}

			var  html = calendar_content( calendar_month[1].data ,archive_link);

			$calendar_body_next.html( html );
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

		$new_style_next.on( 'click.nextMonth', async function ( event ) {
			param.set('event', 'next');

			event.preventDefault();

			left 	= valiate_next_month(left);
			right 	= valiate_next_month(right);
			current.setMonth( left -1 );

			if(right <= 2){
				current.setFullYear(current.getFullYear() + 1);

			}
			$next_year.text( current.getFullYear() );

			calendar_month = await fetch_calendar_month(
				endpoint,
				param,
				current
			);
			console.log("fetch_calendar_month: ", fetch_calendar_month);

			$year.text( current.getFullYear() );
			if(left == 12 ) $year.text( current.getFullYear() -1 );


			$month.text( left );

			$month_name.text( month_names[ current.getMonth() ] );
			$calendar_body.html( calendar_content( calendar_month[0].data, archive_link) );
			$year.text( current.getFullYear() );
			$next_month.text( right  );
			$next_year.text( current.getFullYear() );
			$month_name.text( month_names[ current.getMonth() ] );
			$calendar_body_next.html( calendar_content( calendar_month[1].data,archive_link ) );


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
