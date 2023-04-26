const a = window.wp.apiFetch;
const e = window.jQuery;
const l = window.wp.date;
var json = [];
const dateSelect = window.jQuery("#brick-form__options-fairDate");

var url = "http://dev.roseun-charme.local/wp-json/wp/v2/fair/2655";

const eSelectDate =  e.find("#brick-form__options-fairDate");

const queryString 	= window.location.search;
const urlParams 	= new URLSearchParams(queryString);

const id 	= urlParams.get('id');
const ymd 	= urlParams.get('ymd');
var   pos 	= 0;

window.wp.apiFetch( { path: 'wp/v2/fair/'+id+'?enable_schedule_only=1' } ).then(
	( result ) => {
		window.json = result;
		var qms4__schedules = result.qms4__schedules;
		var html = selected = '';
		qms4__schedules.sort( ((t, e) => t.date < e.date ? -1 : 1) );

		qms4__schedules.forEach(function(item, index){
			selected = '';

			if(item.date == ymd){
				window.pos = index;
				selected = 'selected';
			}
			html = html + `<option pos="${index}" value="${item.date}" ${selected}>${(0,l.dateI18n)("n月j日（D）",item.date)}</option>`;
		});

		dateSelect.html(html);
		var timehtml = '';
		qms4__schedules[window.pos].timetable.forEach(function (item, index){

			timehtml = timehtml + `<option value = "${item.label}"> ${item.label}</option>`;
		})

		window.jQuery("#brick-form__options-fairTime").html(timehtml);
	},
	( error ) => {
		console.log('error: '. error);
	}
);

dateSelect.on("change", (function(t) {

	var element = window.jQuery(this).find('option:selected');
	var pos 	= element.attr("pos");
	var timetable = window.json.qms4__schedules[pos].timetable;
	var html = '';
	timetable.forEach(function(item) {

	if(item.capacity !== '×'){
		html = html + `<option value = "${item.label}"> ${item.label}</option>`;
	}
	window.jQuery("#brick-form__options-fairTime").html(html);

	});
}));


