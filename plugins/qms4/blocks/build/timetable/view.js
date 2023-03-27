(()=>{"use strict";var t={n:e=>{
	var n=e&&e.__esModule?()=>e.default:()=>e;return t.d(n,{a:n}),n},d:(e,n)=>{for(var a in n)t.o(n,a)&&!t.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:n[a]})},o:(t,e)=>Object.prototype.hasOwnProperty.call(t,e)};const e=window.jQuery;var n=t.n(e);

	const a=window.wp.apiFetch;
	var i=t.n(a);
	const l=window.wp.date;
	function _(t,e,n){
		let{$dates:a,$timetable:i,$prev:_,$next:c,buttonLabel:o,reserveUrl:s}=t;
		!function(t,e,n){
			const a=e[n];t.val(a.date)}(a,e,n),
		function(t,e,n,a,i){
			const _=a[i],c=_.title,
			o=(0,l.dateI18n)("Y年n月j日（D）",_.date);
			console.log('check date: ', _.date);

			var now = new Date().getTime();
			let tl= new Date().toLocaleString();

			const s=_.timetable.map((t=>{const a=(()=>{
				switch(t.capacity){
				case"○":
				default: return'<img src="/wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
				case"△":
					return'<img src="/wp-content/themes/fabric/images/icon_few.png" alt="△" />';
				case"×":return'<img src="/wp-content/themes/fabric/images/icon_vacant.png" alt="×" />'}
				})();

				var today = new Date().setHours(0, 0, 0, 0);
				var setDate = new Date(_.date).setHours(0, 0, 0, 0);



				var btn_html = "×"!==t.capacity?`<a href="${n}?event_name=${c}&event_time=${o}%20${t.label}">${e}</a>`:"";
				if( now > setDate ){
					var phone = t.phone.replace(/\D/g, '');
					phone =  phone.replace(/(\d{4})(\d{3})(\d{3})/, "$1-$2-$3");

					btn_html = `<a href="tel:=${t.phone}">${phone}</a>`;
				}

					return`<div class="qms4__block__timetable__timetable-body-row js_row">\n\t\t\t<div class="qms4__block__timetable__timetable-body-time">${t.label}</div>\n\t\t\t<div class="qms4__block__timetable__timetable-body-capacity">${a}</div>\n\t\t\t<div class="qms4__block__timetable__timetable-body-entry">\n\t\t\t\t<p class="qms4__block__timetable__timetable-body-comment">\n\t\t\t\t\t${t.comment}\n\t\t\t\t</p>\n\t\t\t\t<div class="qms4__block__timetable__timetable-body-button">\n\t\t\t\t\t ${btn_html} \n\t\t\t\t</div>\n\t\t\t</div>\n\t\t</div>`}));t.html(s)}(i,o,s,e,n),function(t,e,n){if(0===n)t.hide();else{const a=e[n-1];t.html((0,l.dateI18n)("n月j日",a.date)).show()}}(_,e,n),function(t,e,n){if(n===e.length-1)t.hide();else{const a=e[n+1];t.html((0,l.dateI18n)("n月j日",a.date)).show()}}(c,e,n)}{const c=Object.create(null);function o(t){return c[t]?c[t]:c[t]=i()({path:t}).then((t=>t.qms4__schedules))}n()(".js__qms4__block__timetable").each((async function(){const t=n()(this),e=t.find(".js__qms4__block__timetable__dates select"),a=t.find(".js__qms4__block__timetable__timetable-body"),i=t.find(".js__qms4__block__timetable__button-prev"),c=t.find(".js__qms4__block__timetable__button-next"),s=t.data("current"),d=t.data("endpoint"),b=t.data("button-label"),r=t.data("reserve-url"),m={$dates:e,$timetable:a,$prev:i,$next:c,buttonLabel:b,reserveUrl:r},u=await o(`${d}?enable_schedule_only=1`).then((t=>t.sort(((t,e)=>t.date<e.date?-1:1))));if(console.info({schedules:u}),0===u.length)return;let f=0;s&&(f=u.findIndex((t=>t.date===s)),f=f<0?0:f),e.html(function(t){return t.map((t=>`<option value="${t.date}">${(0,l.dateI18n)("n月j日（D）",t.date)}</option>`))}(u).join("")),_(m,u,f),e.on("change",(function(t){const n=e.val(),a=u.findIndex((t=>t.date===n));a>=0&&(f=a,_(m,u,f))})),i.on("click",(function(t){t.preventDefault(),f-=1,_(m,u,f)})),c.on("click",(function(t){t.preventDefault(),f+=1,_(m,u,f)}))}))}})();