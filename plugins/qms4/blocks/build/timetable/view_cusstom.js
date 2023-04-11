(() => {
    "use strict";
    var t = {
        n: e => {
            var n = e && e.__esModule ? () => e.default : () => e;
            return t.d(n, {
                a: n
            }), n
        },
        d: (e, n) => {
            for (var a in n) t.o(n, a) && !t.o(e, a) && Object.defineProperty(e, a, {
                enumerable: !0,
                get: n[a]
            })
        },
        o: (t, e) => Object.prototype.hasOwnProperty.call(t, e)
    };
    const e = window.jQuery;
    var n = t.n(e);
    console.log('n line 20', n);
    console.log('t line 20', t);
    console.log('e line 20', e);
    const a = window.wp.apiFetch;
    var i = t.n(a);
    console.log('set i: ', i);
    const l = window.wp.date;

    function _(t, e, n) {
    	console.log('pos of n:', n);
        let {
            $dates: a,
            $timetable: i,
            $prev: _,
            $next: c,
            buttonLabel: o,
            reserveUrl: s
        } = t;
        ! function(t, e, n) {
        	console.log('ttt:',t); // == e line 160
        	var pos = t.attr('pos');
        	console.log('pos 39: ', pos);
        	console.log('eee: ',e);
            const a = e[n];
            console.log('a: ', a);

            t.val(a.date); // kkk set date for current option
        }(a, e, n),
        function(t, e, n, a, i) {
        	//var eSelect = document.getElementById("ddlViewBy");
        	// var eSelect = document.getElementsByClassName("html.select")[0];
        	// console.log('eSelect:', eSelect);
        	// var opt= eSelect.find

        	var collection = document.querySelector('.html_select').options;

        	 for (let i = 0; i < collection.length; i++) {
        		var html = collection[i].toString();
        		console.log('html: ', html);
        		var selected = html.indexOf("selected");
        		console.log('selected: ', selected);

    		}
    		//var dateOption  = document.getElementById('qms4__block__timetable__dates').value;

			var e = document.getElementById("qms4__block__timetable__dates");
			var value = e.options[e.selectedIndex].value;
			var text = e.options[e.selectedIndex].text;
			console.log('value:', value);


        	i = 2;
            const _ = a[i],
                c = _.title,
                o = (0, l.dateI18n)("Y年n月j日（D）", _.date);
            console.log('check date: ', _.date);
            console.log('a: ', a);
            console.log('_', _); // == item[0]
            var url = window.location.href;
            console.log('url: ', url);


            const homeUrl = window.arkheVars.homeUrl || '';

            var now = new Date().getTime();
            let tl = new Date().toLocaleString();

            const s = _.timetable.map((t => {
                const a = (() => {
                    switch (t.capacity) {
                        case "○":
                        default:
                            return '<img src="' + homeUrl + 'wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
                        case "△":
                            return '<img src="' + homeUrl + 'wp-content/themes/fabric/images/icon_few.png" alt="△" />';
                        case "×":
                            return '<img src="' + homeUrl + 'wp-content/themes/fabric/images/icon_vacant.png" alt="×" />'
                    }
                })();

                var today = new Date().setHours(0, 0, 0, 0);
                var setDate = new Date(_.date).setHours(0, 0, 0, 0);



                var btn_html = "×" !== t.capacity ? `<a href="${n}?event_name=${c}&event_time=${o}%20${t.label}">${e}</a>` : "";
                if (now > setDate) {
                    var phone = t.phone.replace(/\D/g, '');
                    phone = phone.replace(/(\d{4})(\d{2})(\d{4})/, "$1-$2-$3");

                    btn_html = `<a href="tel:${t.phone}">${phone}</a>`;
                }

                return `<div class="qms4__block__timetable__timetable-body-row js_row">\n\t\t\t<div class="qms4__block__timetable__timetable-body-time">${t.label}</div>\n\t\t\t<div class="qms4__block__timetable__timetable-body-capacity">${a}</div>\n\t\t\t<div class="qms4__block__timetable__timetable-body-entry">\n\t\t\t\t<p class="qms4__block__timetable__timetable-body-comment">\n\t\t\t\t\t${t.comment}\n\t\t\t\t</p>\n\t\t\t\t<div class="qms4__block__timetable__timetable-body-button">\n\t\t\t\t\t ${btn_html} \n\t\t\t\t</div>\n\t\t\t</div>\n\t\t</div>`
            }));
            t.html(s)
        }(i, o, s, e, n),
        function(t, e, n) {
            if (0 === n) t.hide();
            else {
                const a = e[n - 1];
                t.html((0, l.dateI18n)("n月j日", a.date)).show()
            }
        }(_, e, n),
        function(t, e, n) {
        	console.log('check n: ', n);
            if (n === e.length - 1) t.hide();
            else {
                const a = e[n + 1];
                t.html((0, l.dateI18n)("n月j日", a.date)).show()
            }
        }(c, e, n)
    } {
        const c = Object.create(null);

        function o(t) {
            return c[t] ? c[t] : c[t] = i()({
                path: t
            }).then((t => t.qms4__schedules))
        }
        n()(".js__qms4__block__timetable").each((async function() {
        	console.log('line 104');
            const t = n()(this),
                e = t.find(".js__qms4__block__timetable__dates select"),
                a = t.find(".js__qms4__block__timetable__timetable-body"),
                i = t.find(".js__qms4__block__timetable__button-prev"),
                c = t.find(".js__qms4__block__timetable__button-next"),
                s = t.data("current"),
                d = t.data("endpoint"),
                b = t.data("button-label"),
                r = t.data("reserve-url"),
                m = {
                    $dates: e,
                    $timetable: a,
                    $prev: i,
                    $next: c,
                    buttonLabel: b,
                    reserveUrl: r
                },
                u = await o(`${d}?enable_schedule_only=1`).then((t => t.sort(((t, e) => t.date < e.date ? -1 : 1))));
                console.log('e line 160: ', e);
                var curOpt = e.selectedIndex;
                console.log('curOption: ', curOpt);
            if (console.info({
                    schedules: u
                }), 0 === u.length) return;
            let f = 0;
            s && (f = u.findIndex((t => t.date === s)), f = f < 0 ? 0 : f), e.html(function(t) {
            	console.log('render 3 options.');
            	var html = []
            	var select = '';
            	t.forEach(function(item, index){
            		select = (item.date == '2023-04-30') ? ' selected' : '';
            		html.push (`<option pos="${index}" value="${item.date}" ${select} >${(0,l.dateI18n)("n月j日（D）",item.date)}</option>`);
            	});


                return html;

            }(u).join("")), _(m, u, f), e.on("change", (function(t) {
                const n = e.val(),
                    a = u.findIndex((t => t.date === n));
                    console.log('check n:', n);
                a >= 0 && (f = a, _(m, u, f))
            })), i.on("click", (function(t) {
                t.preventDefault(), f -= 1, _(m, u, f)
            })), c.on("click", (function(t) {
                t.preventDefault(), f += 1, _(m, u, f)
            }))
        }))
    }
})();