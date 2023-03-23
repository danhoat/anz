
// import { DateTimePicker } from '@wordpress/components';
// import { useState } from '@wordpress/element';

(()=>{"use strict";var e,t={ 518:()=>{


	const e=window.wp.blocks,
	t=window.wp.element,n=(window.React,window.wp.blockEditor),
	l=window.wp.data,a=window.wp.coreData;



	const c=[
		{label:"",capacity:"○",available:!0,comment:"", phone:""},
		{label:"",capacity:"○",available:!0,comment:"", phone:""}];

	let time= [];
	for (let i = 0; i < 24; i++) {
	  time[i] = {value: i, label:i+'h'};
	}

	const o=window.wp.components,r=e=>{
		let{ label:l,capacity:a, exptime: et, comment:c, picker,pi, phone:ph, onLabelChange:r,onCapacityChange:m,onExptimeChange: ec, onCommentChange:i,onPhoneChange:phc, removeRow:u,buttonText:s,onButtonTextChange:p}=e;
		return(0,t.createElement)("tr",null,

			(0,t.createElement)(n.RichText,{tagName:"td",value:l,onChange:r,placeholder:"開催時間を入力 "}),

			(0,t.createElement)("td",null,(0,t.createElement)(o.SelectControl,{value:a,options:[{value:"○",label:"○"},{value:"△",label:"△"},{value:"×",label:"×"}],onChange:m})),
			(0,t.createElement)("td",null,(0,t.createElement)(n.RichText,{tagName:"p",className:"comment",value:c,placeholder:"備考を入力",onChange:i}),(0,t.createElement)(n.RichText,{tagName:"p",className:"reserve_time--dummy",value:s,placeholder:"この時間で予約する",onChange:p})),


			//coppy comment field
			(0,t.createElement)("td",null,(0,t.createElement)(o.SelectControl,{value:et,options:
				time, onChange:ec})),

			// (0,t.createElement)("td",null,(0,t.createElement)(n.RichText,{tagName:"p",className:"phone",value:ph,placeholder:"Select time",

			// 	onClick:function(Event){
			// 		// 9999


			// 		//(0,t.createElement)(n.__experimentalPublishDateTimePicker);


			// }})),

			// Add new phone number fi
			//coppy comment field
			(0,t.createElement)("td",null,(0,t.createElement)(n.RichText,{tagName:"p",className:"phone",value:ph,placeholder:"Phone Numer",onChange:phc})),


			// (0,t.createElement)(n.RichText,{tagName:"td",value:ph, onChange:phc,placeholder:"Phone number"}),


			(0,t.createElement)("td",null,(0,t.createElement)("button",{className:"qms4__timetable__button-remove-row",title:" 行を削除 ",onClick:u},"× 削除") )
			)// end tag tr
	};
(0,e.registerBlockType)("qms4/timetable",{edit:function(e){

	let{isSelected:o}=e;const m=(0,l.useSelect)((e=>e("core/editor").getCurrentPostType()),[]),

			[i,u]=function(e){ console.log('init row');
			return function(e){const[t,n]=(0,a.useEntityProp)("postType",e,"qms4__timetable__button_text");return t?[t,n]:["この時間で予約する 555",n]}(e)
		}(m),[s,p,d,b]=function(e){

			const[t,n]=function(e){
				const[t,n]=(0,a.useEntityProp)("postType",e,"qms4__timetable");
				return null==t?[c,n]:[t,n]}(e),l={
						label:(e,l)=>{
						const a=t.map(((t,n)=>e===n?{...t,label:l}:t));n(a)
					},
					capacity:(e,l) =>{const a=t.map(((t,n)=>e===n?{...t,capacity:l}:t));n(a)},
					exptime:(e,l) =>{const a=t.map(((t,n)=>e===n?{...t,exptime:l}:t));n(a)},
					phone:(e,l)    =>{const a=t.map(((t,n)=>e===n?{...t,phone:l}:t));n(a)},
					comment:(e,l)  =>{const a=t.map(((t,n)=>e===n?{...t,comment:l}:t));n(a)}

				};

				return[t,l,()=>{const e=[...t,{ label:"",capacity:"○",available:!0,comment:"",phone:"", exptime:""}];
					n(e)},e=>()=>n(t.filter(((t,n)=>e!=n)))]}(m);
					return(0,t.createElement)("div",(0,n.useBlockProps)(),(0,t.createElement)("table",null,(0,t.createElement)("thead",null,(0,t.createElement)("th",null,"開催",(0,t.createElement)("br",{className:"sp"}),"時間"),(0,t.createElement)("th",null,"空席",(0,t.createElement)("br",{className:"sp"}),"状況"),(0,t.createElement)("th",null," "),(0,t.createElement)("th",null," ")),
						(0,t.createElement)("tbody",null,s.map(((e,n)=>(0,t.createElement)(r,{key:n,label:e.label,capacity:e.capacity,exptime: e.exptime,comment:e.comment, phone: e.phone,
					onLabelChange:e=>p.label(n,e),
					onCapacityChange:e=>p.capacity(n,e),
					onExptimeChange:e=>p.exptime(n,e),
					onPhoneChange:e=>p.phone(n,e),
					onCommentChange:e=>p.comment(n,e),removeRow:b(n),buttonText:i,onButtonTextChange:u
				}
				))))),o&&(0,t.createElement)("button",{className:"qms4__timetable__button-add-row",title:"行を追加",onClick:d},"Add Row"),(0,t.createElement)("dl",{className:"ex clearfix"},(0,t.createElement)("dt",null,"○"),(0,t.createElement)("dd",null,"予約可"),(0,t.createElement)("dt",null,"△"),(0,t.createElement)("dd",null,"残りわずか"),(0,t.createElement)("dt",null,"×"),(0,t.createElement)("dd",null,"満席")))},
		save:function(){
			return null}})
}},n={};function l(e){var a=n[e];if(void 0!==a)return a.exports;var c=n[e]={exports:{}};return t[e](c,c.exports,l),c.exports}l.m=t,e=[],l.O=(t,n,a,c,ph)=>{if(!n){var o=1/0;for(u=0;u<e.length;u++){n=e[u][0],a=e[u][1],c=e[u][2];for(var r=!0,m=0;m<n.length;m++)(!1&c||o>=c)&&Object.keys(l.O).every((e=>l.O[e](n[m])))?n.splice(m--,1):(r=!1,c<o&&(o=c));if(r){e.splice(u--,1);var i=a();void 0!==i&&(t=i)}}return t}c=c||0;for(var u=e.length;u>0&&e[u-1][2]>c;u--)e[u]=e[u-1];e[u]=[n,a,c,ph]},l.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={4427:0,5132:0};l.O.j=t=>0===e[t];var t=(t,n)=>{var a,c,o,p=n[0],r=n[1],m=n[2],i=0;if(o.some((t=>0!==e[t]))){for(a in r)l.o(r,a)&&(l.m[a]=r[a]);if(m)var u=m(l)}for(t&&t(n);i<o.length;i++)c=o[i],l.o(e,c)&&e[c]&&e[c][0](),e[c]=0;return l.O(u)},n=self.webpackChunkqms4=self.webpackChunkqms4||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var a=l.O(void 0,[5132],(()=>l(518)));a=l.O(a)})();

