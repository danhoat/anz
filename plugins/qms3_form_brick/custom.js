
//import apiFetch from '@wordpress/api-fetch';


const a = window.wp.apiFetch;
const e = window.jQuery;
const l = window.wp.date;
var json = [];
const dateSelect = window.jQuery("#brick-form__options-fairDate");

var url = "http://dev.roseun-charme.local/wp-json/wp/v2/fair/2655";
const eSelectDate =  e.find("#brick-form__options-fairDate");
console.log("eSelectDate: ", eSelectDate);
window.wp.apiFetch( { path: 'wp/v2/fair/2794' } ).then(
    ( result ) => {
    	window.json = result;
      	var qms4__schedules = result.qms4__schedules;
      	console.log("qms4__schedules: ", qms4__schedules);
      	var html = '';
      	qms4__schedules.forEach(function(item, index){
      			//console.log('item: ', item);
      		html = html + `<option pos="${index}" value="${item.date}" >${(0,l.dateI18n)("n月j日（D）",item.date)}</option>`;

      	});

      	dateSelect.html(html);
      	var timehtml = '';
      	qms4__schedules[0].timetable.forEach(function (item, index){
      		if(item.capacity !== '×'){
	 			timehtml = timehtml + `<option value = "${item.label}"> ${item.label}</option>`;
	 		}
      	})

      	window.jQuery("#brick-form__options-fairTime").html(timehtml);
    },
    ( error ) => {
        console.log('error: '. error);
    }
);
 dateSelect.on("change", (function(t) {
 	console.log('t: ',t);
 	var check = window.jQuery(this);
 	var opt = check.find();

 	 var element = window.jQuery(this).find('option:selected');
        var pos = element.attr("pos");

        console.log('pos: ', pos);

 	console.log('value: ', check.val()); // 2023-05-01
 	var timetable = window.json.qms4__schedules[pos].timetable;
 	var html = '';
 	timetable.forEach(function(item) {
 		console.log('item: ', item);
 		if(item.capacity !== '×'){
 			html = html + `<option value = "${item.label}"> ${item.label}</option>`;
 		}
 		window.jQuery("#brick-form__options-fairTime").html(html);

 	});
  }));

