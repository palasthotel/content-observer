!function(){"use strict";var e={n:function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,{a:n}),n},d:function(t,n){for(var a in n)e.o(n,a)&&!e.o(t,a)&&Object.defineProperty(t,a,{enumerable:!0,get:n[a]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,n=window.wp.apiFetch,a=e.n(n);const r=ContentObserver,i=()=>{const{customRequestHeaders:e}=r;return e},o=e=>{let t;try{t=new URL(e)}catch(e){return!1}return!(t.hostname.length<3||t.hostname.indexOf(".")<=0||"http:"!==t.protocol&&"https:"!==t.protocol)},s=(e,n)=>{const[s,u]=(0,t.useState)(!1),[l,c]=(0,t.useState)(!0),[d,h]=(0,t.useState)(1);return(0,t.useEffect)((()=>{if(s)return;if(!o(e))return void c(!1);if(n.length<=0)return void c(!1);u(!0);const{promise:t,cancel:l}=((e,t)=>{const{apiNamespace:n,pingUrlApiKeyParam:o,apiKey:s}=r,u=`${n}/ping?${o}=${s}&site_url=${encodeURIComponent(e)}&site_api_key=${t}`,l=new AbortController;return{promise:a()({path:u,signal:l.signal,headers:i()}).then((e=>"pong"===e.response)).catch((e=>(console.error(e),e))),cancel:()=>{l.abort()}}})(e,n);return t.then((e=>{u(!1),c(!0===e)})),()=>l()}),[e,n,d]),{isSuccess:l,isTesting:s,testAgain:()=>h((e=>e+1))}},u=(e,t)=>e.filter((e=>{let{relation_type:n}=e;return n===t||"both"===n})),l=e=>u(e,"observer"),c=e=>u(e,"observable");function d(e,t){if(t.length<e)throw new TypeError(e+" argument"+(e>1?"s":"")+" required, but only "+t.length+" present")}function h(e){return d(1,arguments),e instanceof Date||"object"==typeof e&&"[object Date]"===Object.prototype.toString.call(e)}function m(e){d(1,arguments);var t=Object.prototype.toString.call(e);return e instanceof Date||"object"==typeof e&&"[object Date]"===t?new Date(e.getTime()):"number"==typeof e||"[object Number]"===t?new Date(e):("string"!=typeof e&&"[object String]"!==t||"undefined"==typeof console||(console.warn("Starting with v2.0.0-beta.1 date-fns doesn't accept strings as date arguments. Please use `parseISO` to parse strings. See: https://git.io/fjule"),console.warn((new Error).stack)),new Date(NaN))}function g(e){if(d(1,arguments),!h(e)&&"number"!=typeof e)return!1;var t=m(e);return!isNaN(Number(t))}var f={lessThanXSeconds:{one:"less than a second",other:"less than {{count}} seconds"},xSeconds:{one:"1 second",other:"{{count}} seconds"},halfAMinute:"half a minute",lessThanXMinutes:{one:"less than a minute",other:"less than {{count}} minutes"},xMinutes:{one:"1 minute",other:"{{count}} minutes"},aboutXHours:{one:"about 1 hour",other:"about {{count}} hours"},xHours:{one:"1 hour",other:"{{count}} hours"},xDays:{one:"1 day",other:"{{count}} days"},aboutXWeeks:{one:"about 1 week",other:"about {{count}} weeks"},xWeeks:{one:"1 week",other:"{{count}} weeks"},aboutXMonths:{one:"about 1 month",other:"about {{count}} months"},xMonths:{one:"1 month",other:"{{count}} months"},aboutXYears:{one:"about 1 year",other:"about {{count}} years"},xYears:{one:"1 year",other:"{{count}} years"},overXYears:{one:"over 1 year",other:"over {{count}} years"},almostXYears:{one:"almost 1 year",other:"almost {{count}} years"}};function w(e){return function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},n=t.width?String(t.width):e.defaultWidth,a=e.formats[n]||e.formats[e.defaultWidth];return a}}var v={date:w({formats:{full:"EEEE, MMMM do, y",long:"MMMM do, y",medium:"MMM d, y",short:"MM/dd/yyyy"},defaultWidth:"full"}),time:w({formats:{full:"h:mm:ss a zzzz",long:"h:mm:ss a z",medium:"h:mm:ss a",short:"h:mm a"},defaultWidth:"full"}),dateTime:w({formats:{full:"{{date}} 'at' {{time}}",long:"{{date}} 'at' {{time}}",medium:"{{date}}, {{time}}",short:"{{date}}, {{time}}"},defaultWidth:"full"})},b={lastWeek:"'last' eeee 'at' p",yesterday:"'yesterday at' p",today:"'today at' p",tomorrow:"'tomorrow at' p",nextWeek:"eeee 'at' p",other:"P"};function p(e){return function(t,n){var a,r=n||{};if("formatting"===(r.context?String(r.context):"standalone")&&e.formattingValues){var i=e.defaultFormattingWidth||e.defaultWidth,o=r.width?String(r.width):i;a=e.formattingValues[o]||e.formattingValues[i]}else{var s=e.defaultWidth,u=r.width?String(r.width):e.defaultWidth;a=e.values[u]||e.values[s]}return a[e.argumentCallback?e.argumentCallback(t):t]}}function y(e){return function(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=n.width,r=a&&e.matchPatterns[a]||e.matchPatterns[e.defaultMatchWidth],i=t.match(r);if(!i)return null;var o,s=i[0],u=a&&e.parsePatterns[a]||e.parsePatterns[e.defaultParseWidth],l=Array.isArray(u)?C(u,(function(e){return e.test(s)})):M(u,(function(e){return e.test(s)}));o=e.valueCallback?e.valueCallback(l):l,o=n.valueCallback?n.valueCallback(o):o;var c=t.slice(s.length);return{value:o,rest:c}}}function M(e,t){for(var n in e)if(e.hasOwnProperty(n)&&t(e[n]))return n}function C(e,t){for(var n=0;n<e.length;n++)if(t(e[n]))return n}function E(e){return function(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=t.match(e.matchPattern);if(!a)return null;var r=a[0],i=t.match(e.parsePattern);if(!i)return null;var o=e.valueCallback?e.valueCallback(i[0]):i[0];o=n.valueCallback?n.valueCallback(o):o;var s=t.slice(r.length);return{value:o,rest:s}}}var S={code:"en-US",formatDistance:function(e,t,n){var a,r=f[e];return a="string"==typeof r?r:1===t?r.one:r.other.replace("{{count}}",t.toString()),null!=n&&n.addSuffix?n.comparison&&n.comparison>0?"in "+a:a+" ago":a},formatLong:v,formatRelative:function(e,t,n,a){return b[e]},localize:{ordinalNumber:function(e,t){var n=Number(e),a=n%100;if(a>20||a<10)switch(a%10){case 1:return n+"st";case 2:return n+"nd";case 3:return n+"rd"}return n+"th"},era:p({values:{narrow:["B","A"],abbreviated:["BC","AD"],wide:["Before Christ","Anno Domini"]},defaultWidth:"wide"}),quarter:p({values:{narrow:["1","2","3","4"],abbreviated:["Q1","Q2","Q3","Q4"],wide:["1st quarter","2nd quarter","3rd quarter","4th quarter"]},defaultWidth:"wide",argumentCallback:function(e){return e-1}}),month:p({values:{narrow:["J","F","M","A","M","J","J","A","S","O","N","D"],abbreviated:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],wide:["January","February","March","April","May","June","July","August","September","October","November","December"]},defaultWidth:"wide"}),day:p({values:{narrow:["S","M","T","W","T","F","S"],short:["Su","Mo","Tu","We","Th","Fr","Sa"],abbreviated:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],wide:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},defaultWidth:"wide"}),dayPeriod:p({values:{narrow:{am:"a",pm:"p",midnight:"mi",noon:"n",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"},abbreviated:{am:"AM",pm:"PM",midnight:"midnight",noon:"noon",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"},wide:{am:"a.m.",pm:"p.m.",midnight:"midnight",noon:"noon",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"}},defaultWidth:"wide",formattingValues:{narrow:{am:"a",pm:"p",midnight:"mi",noon:"n",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"},abbreviated:{am:"AM",pm:"PM",midnight:"midnight",noon:"noon",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"},wide:{am:"a.m.",pm:"p.m.",midnight:"midnight",noon:"noon",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"}},defaultFormattingWidth:"wide"})},match:{ordinalNumber:E({matchPattern:/^(\d+)(th|st|nd|rd)?/i,parsePattern:/\d+/i,valueCallback:function(e){return parseInt(e,10)}}),era:y({matchPatterns:{narrow:/^(b|a)/i,abbreviated:/^(b\.?\s?c\.?|b\.?\s?c\.?\s?e\.?|a\.?\s?d\.?|c\.?\s?e\.?)/i,wide:/^(before christ|before common era|anno domini|common era)/i},defaultMatchWidth:"wide",parsePatterns:{any:[/^b/i,/^(a|c)/i]},defaultParseWidth:"any"}),quarter:y({matchPatterns:{narrow:/^[1234]/i,abbreviated:/^q[1234]/i,wide:/^[1234](th|st|nd|rd)? quarter/i},defaultMatchWidth:"wide",parsePatterns:{any:[/1/i,/2/i,/3/i,/4/i]},defaultParseWidth:"any",valueCallback:function(e){return e+1}}),month:y({matchPatterns:{narrow:/^[jfmasond]/i,abbreviated:/^(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i,wide:/^(january|february|march|april|may|june|july|august|september|october|november|december)/i},defaultMatchWidth:"wide",parsePatterns:{narrow:[/^j/i,/^f/i,/^m/i,/^a/i,/^m/i,/^j/i,/^j/i,/^a/i,/^s/i,/^o/i,/^n/i,/^d/i],any:[/^ja/i,/^f/i,/^mar/i,/^ap/i,/^may/i,/^jun/i,/^jul/i,/^au/i,/^s/i,/^o/i,/^n/i,/^d/i]},defaultParseWidth:"any"}),day:y({matchPatterns:{narrow:/^[smtwf]/i,short:/^(su|mo|tu|we|th|fr|sa)/i,abbreviated:/^(sun|mon|tue|wed|thu|fri|sat)/i,wide:/^(sunday|monday|tuesday|wednesday|thursday|friday|saturday)/i},defaultMatchWidth:"wide",parsePatterns:{narrow:[/^s/i,/^m/i,/^t/i,/^w/i,/^t/i,/^f/i,/^s/i],any:[/^su/i,/^m/i,/^tu/i,/^w/i,/^th/i,/^f/i,/^sa/i]},defaultParseWidth:"any"}),dayPeriod:y({matchPatterns:{narrow:/^(a|p|mi|n|(in the|at) (morning|afternoon|evening|night))/i,any:/^([ap]\.?\s?m\.?|midnight|noon|(in the|at) (morning|afternoon|evening|night))/i},defaultMatchWidth:"any",parsePatterns:{any:{am:/^a/i,pm:/^p/i,midnight:/^mi/i,noon:/^no/i,morning:/morning/i,afternoon:/afternoon/i,evening:/evening/i,night:/night/i}},defaultParseWidth:"any"})},options:{weekStartsOn:0,firstWeekContainsDate:1}};function T(e){if(null===e||!0===e||!1===e)return NaN;var t=Number(e);return isNaN(t)?t:t<0?Math.ceil(t):Math.floor(t)}function P(e,t){d(2,arguments);var n=m(e).getTime(),a=T(t);return new Date(n+a)}function k(e,t){d(2,arguments);var n=T(t);return P(e,-n)}var D=864e5;function x(e){d(1,arguments);var t=1,n=m(e),a=n.getUTCDay(),r=(a<t?7:0)+a-t;return n.setUTCDate(n.getUTCDate()-r),n.setUTCHours(0,0,0,0),n}function W(e){d(1,arguments);var t=m(e),n=t.getUTCFullYear(),a=new Date(0);a.setUTCFullYear(n+1,0,4),a.setUTCHours(0,0,0,0);var r=x(a),i=new Date(0);i.setUTCFullYear(n,0,4),i.setUTCHours(0,0,0,0);var o=x(i);return t.getTime()>=r.getTime()?n+1:t.getTime()>=o.getTime()?n:n-1}function U(e){d(1,arguments);var t=W(e),n=new Date(0);n.setUTCFullYear(t,0,4),n.setUTCHours(0,0,0,0);var a=x(n);return a}var N=6048e5;function O(e,t){d(1,arguments);var n=t||{},a=n.locale,r=a&&a.options&&a.options.weekStartsOn,i=null==r?0:T(r),o=null==n.weekStartsOn?i:T(n.weekStartsOn);if(!(o>=0&&o<=6))throw new RangeError("weekStartsOn must be between 0 and 6 inclusively");var s=m(e),u=s.getUTCDay(),l=(u<o?7:0)+u-o;return s.setUTCDate(s.getUTCDate()-l),s.setUTCHours(0,0,0,0),s}function Y(e,t){d(1,arguments);var n=m(e),a=n.getUTCFullYear(),r=t||{},i=r.locale,o=i&&i.options&&i.options.firstWeekContainsDate,s=null==o?1:T(o),u=null==r.firstWeekContainsDate?s:T(r.firstWeekContainsDate);if(!(u>=1&&u<=7))throw new RangeError("firstWeekContainsDate must be between 1 and 7 inclusively");var l=new Date(0);l.setUTCFullYear(a+1,0,u),l.setUTCHours(0,0,0,0);var c=O(l,t),h=new Date(0);h.setUTCFullYear(a,0,u),h.setUTCHours(0,0,0,0);var g=O(h,t);return n.getTime()>=c.getTime()?a+1:n.getTime()>=g.getTime()?a:a-1}function j(e,t){d(1,arguments);var n=t||{},a=n.locale,r=a&&a.options&&a.options.firstWeekContainsDate,i=null==r?1:T(r),o=null==n.firstWeekContainsDate?i:T(n.firstWeekContainsDate),s=Y(e,t),u=new Date(0);u.setUTCFullYear(s,0,o),u.setUTCHours(0,0,0,0);var l=O(u,t);return l}var z=6048e5;function F(e,t){for(var n=e<0?"-":"",a=Math.abs(e).toString();a.length<t;)a="0"+a;return n+a}var A=function(e,t){var n=e.getUTCFullYear(),a=n>0?n:1-n;return F("yy"===t?a%100:a,t.length)},J=function(e,t){var n=e.getUTCMonth();return"M"===t?String(n+1):F(n+1,2)},q=function(e,t){return F(e.getUTCDate(),t.length)},H=function(e,t){return F(e.getUTCHours()%12||12,t.length)},_=function(e,t){return F(e.getUTCHours(),t.length)},L=function(e,t){return F(e.getUTCMinutes(),t.length)},Q=function(e,t){return F(e.getUTCSeconds(),t.length)},X=function(e,t){var n=t.length,a=e.getUTCMilliseconds();return F(Math.floor(a*Math.pow(10,n-3)),t.length)},R={G:function(e,t,n){var a=e.getUTCFullYear()>0?1:0;switch(t){case"G":case"GG":case"GGG":return n.era(a,{width:"abbreviated"});case"GGGGG":return n.era(a,{width:"narrow"});default:return n.era(a,{width:"wide"})}},y:function(e,t,n){if("yo"===t){var a=e.getUTCFullYear(),r=a>0?a:1-a;return n.ordinalNumber(r,{unit:"year"})}return A(e,t)},Y:function(e,t,n,a){var r=Y(e,a),i=r>0?r:1-r;return"YY"===t?F(i%100,2):"Yo"===t?n.ordinalNumber(i,{unit:"year"}):F(i,t.length)},R:function(e,t){return F(W(e),t.length)},u:function(e,t){return F(e.getUTCFullYear(),t.length)},Q:function(e,t,n){var a=Math.ceil((e.getUTCMonth()+1)/3);switch(t){case"Q":return String(a);case"QQ":return F(a,2);case"Qo":return n.ordinalNumber(a,{unit:"quarter"});case"QQQ":return n.quarter(a,{width:"abbreviated",context:"formatting"});case"QQQQQ":return n.quarter(a,{width:"narrow",context:"formatting"});default:return n.quarter(a,{width:"wide",context:"formatting"})}},q:function(e,t,n){var a=Math.ceil((e.getUTCMonth()+1)/3);switch(t){case"q":return String(a);case"qq":return F(a,2);case"qo":return n.ordinalNumber(a,{unit:"quarter"});case"qqq":return n.quarter(a,{width:"abbreviated",context:"standalone"});case"qqqqq":return n.quarter(a,{width:"narrow",context:"standalone"});default:return n.quarter(a,{width:"wide",context:"standalone"})}},M:function(e,t,n){var a=e.getUTCMonth();switch(t){case"M":case"MM":return J(e,t);case"Mo":return n.ordinalNumber(a+1,{unit:"month"});case"MMM":return n.month(a,{width:"abbreviated",context:"formatting"});case"MMMMM":return n.month(a,{width:"narrow",context:"formatting"});default:return n.month(a,{width:"wide",context:"formatting"})}},L:function(e,t,n){var a=e.getUTCMonth();switch(t){case"L":return String(a+1);case"LL":return F(a+1,2);case"Lo":return n.ordinalNumber(a+1,{unit:"month"});case"LLL":return n.month(a,{width:"abbreviated",context:"standalone"});case"LLLLL":return n.month(a,{width:"narrow",context:"standalone"});default:return n.month(a,{width:"wide",context:"standalone"})}},w:function(e,t,n,a){var r=function(e,t){d(1,arguments);var n=m(e),a=O(n,t).getTime()-j(n,t).getTime();return Math.round(a/z)+1}(e,a);return"wo"===t?n.ordinalNumber(r,{unit:"week"}):F(r,t.length)},I:function(e,t,n){var a=function(e){d(1,arguments);var t=m(e),n=x(t).getTime()-U(t).getTime();return Math.round(n/N)+1}(e);return"Io"===t?n.ordinalNumber(a,{unit:"week"}):F(a,t.length)},d:function(e,t,n){return"do"===t?n.ordinalNumber(e.getUTCDate(),{unit:"date"}):q(e,t)},D:function(e,t,n){var a=function(e){d(1,arguments);var t=m(e),n=t.getTime();t.setUTCMonth(0,1),t.setUTCHours(0,0,0,0);var a=t.getTime(),r=n-a;return Math.floor(r/D)+1}(e);return"Do"===t?n.ordinalNumber(a,{unit:"dayOfYear"}):F(a,t.length)},E:function(e,t,n){var a=e.getUTCDay();switch(t){case"E":case"EE":case"EEE":return n.day(a,{width:"abbreviated",context:"formatting"});case"EEEEE":return n.day(a,{width:"narrow",context:"formatting"});case"EEEEEE":return n.day(a,{width:"short",context:"formatting"});default:return n.day(a,{width:"wide",context:"formatting"})}},e:function(e,t,n,a){var r=e.getUTCDay(),i=(r-a.weekStartsOn+8)%7||7;switch(t){case"e":return String(i);case"ee":return F(i,2);case"eo":return n.ordinalNumber(i,{unit:"day"});case"eee":return n.day(r,{width:"abbreviated",context:"formatting"});case"eeeee":return n.day(r,{width:"narrow",context:"formatting"});case"eeeeee":return n.day(r,{width:"short",context:"formatting"});default:return n.day(r,{width:"wide",context:"formatting"})}},c:function(e,t,n,a){var r=e.getUTCDay(),i=(r-a.weekStartsOn+8)%7||7;switch(t){case"c":return String(i);case"cc":return F(i,t.length);case"co":return n.ordinalNumber(i,{unit:"day"});case"ccc":return n.day(r,{width:"abbreviated",context:"standalone"});case"ccccc":return n.day(r,{width:"narrow",context:"standalone"});case"cccccc":return n.day(r,{width:"short",context:"standalone"});default:return n.day(r,{width:"wide",context:"standalone"})}},i:function(e,t,n){var a=e.getUTCDay(),r=0===a?7:a;switch(t){case"i":return String(r);case"ii":return F(r,t.length);case"io":return n.ordinalNumber(r,{unit:"day"});case"iii":return n.day(a,{width:"abbreviated",context:"formatting"});case"iiiii":return n.day(a,{width:"narrow",context:"formatting"});case"iiiiii":return n.day(a,{width:"short",context:"formatting"});default:return n.day(a,{width:"wide",context:"formatting"})}},a:function(e,t,n){var a=e.getUTCHours()/12>=1?"pm":"am";switch(t){case"a":case"aa":return n.dayPeriod(a,{width:"abbreviated",context:"formatting"});case"aaa":return n.dayPeriod(a,{width:"abbreviated",context:"formatting"}).toLowerCase();case"aaaaa":return n.dayPeriod(a,{width:"narrow",context:"formatting"});default:return n.dayPeriod(a,{width:"wide",context:"formatting"})}},b:function(e,t,n){var a,r=e.getUTCHours();switch(a=12===r?"noon":0===r?"midnight":r/12>=1?"pm":"am",t){case"b":case"bb":return n.dayPeriod(a,{width:"abbreviated",context:"formatting"});case"bbb":return n.dayPeriod(a,{width:"abbreviated",context:"formatting"}).toLowerCase();case"bbbbb":return n.dayPeriod(a,{width:"narrow",context:"formatting"});default:return n.dayPeriod(a,{width:"wide",context:"formatting"})}},B:function(e,t,n){var a,r=e.getUTCHours();switch(a=r>=17?"evening":r>=12?"afternoon":r>=4?"morning":"night",t){case"B":case"BB":case"BBB":return n.dayPeriod(a,{width:"abbreviated",context:"formatting"});case"BBBBB":return n.dayPeriod(a,{width:"narrow",context:"formatting"});default:return n.dayPeriod(a,{width:"wide",context:"formatting"})}},h:function(e,t,n){if("ho"===t){var a=e.getUTCHours()%12;return 0===a&&(a=12),n.ordinalNumber(a,{unit:"hour"})}return H(e,t)},H:function(e,t,n){return"Ho"===t?n.ordinalNumber(e.getUTCHours(),{unit:"hour"}):_(e,t)},K:function(e,t,n){var a=e.getUTCHours()%12;return"Ko"===t?n.ordinalNumber(a,{unit:"hour"}):F(a,t.length)},k:function(e,t,n){var a=e.getUTCHours();return 0===a&&(a=24),"ko"===t?n.ordinalNumber(a,{unit:"hour"}):F(a,t.length)},m:function(e,t,n){return"mo"===t?n.ordinalNumber(e.getUTCMinutes(),{unit:"minute"}):L(e,t)},s:function(e,t,n){return"so"===t?n.ordinalNumber(e.getUTCSeconds(),{unit:"second"}):Q(e,t)},S:function(e,t){return X(e,t)},X:function(e,t,n,a){var r=(a._originalDate||e).getTimezoneOffset();if(0===r)return"Z";switch(t){case"X":return I(r);case"XXXX":case"XX":return B(r);default:return B(r,":")}},x:function(e,t,n,a){var r=(a._originalDate||e).getTimezoneOffset();switch(t){case"x":return I(r);case"xxxx":case"xx":return B(r);default:return B(r,":")}},O:function(e,t,n,a){var r=(a._originalDate||e).getTimezoneOffset();switch(t){case"O":case"OO":case"OOO":return"GMT"+G(r,":");default:return"GMT"+B(r,":")}},z:function(e,t,n,a){var r=(a._originalDate||e).getTimezoneOffset();switch(t){case"z":case"zz":case"zzz":return"GMT"+G(r,":");default:return"GMT"+B(r,":")}},t:function(e,t,n,a){var r=a._originalDate||e;return F(Math.floor(r.getTime()/1e3),t.length)},T:function(e,t,n,a){return F((a._originalDate||e).getTime(),t.length)}};function G(e,t){var n=e>0?"-":"+",a=Math.abs(e),r=Math.floor(a/60),i=a%60;if(0===i)return n+String(r);var o=t||"";return n+String(r)+o+F(i,2)}function I(e,t){return e%60==0?(e>0?"-":"+")+F(Math.abs(e)/60,2):B(e,t)}function B(e,t){var n=t||"",a=e>0?"-":"+",r=Math.abs(e);return a+F(Math.floor(r/60),2)+n+F(r%60,2)}var $=R;function K(e,t){switch(e){case"P":return t.date({width:"short"});case"PP":return t.date({width:"medium"});case"PPP":return t.date({width:"long"});default:return t.date({width:"full"})}}function V(e,t){switch(e){case"p":return t.time({width:"short"});case"pp":return t.time({width:"medium"});case"ppp":return t.time({width:"long"});default:return t.time({width:"full"})}}var Z={p:V,P:function(e,t){var n,a=e.match(/(P+)(p+)?/)||[],r=a[1],i=a[2];if(!i)return K(e,t);switch(r){case"P":n=t.dateTime({width:"short"});break;case"PP":n=t.dateTime({width:"medium"});break;case"PPP":n=t.dateTime({width:"long"});break;default:n=t.dateTime({width:"full"})}return n.replace("{{date}}",K(r,t)).replace("{{time}}",V(i,t))}},ee=Z;function te(e){var t=new Date(Date.UTC(e.getFullYear(),e.getMonth(),e.getDate(),e.getHours(),e.getMinutes(),e.getSeconds(),e.getMilliseconds()));return t.setUTCFullYear(e.getFullYear()),e.getTime()-t.getTime()}var ne=["D","DD"],ae=["YY","YYYY"];function re(e){return-1!==ne.indexOf(e)}function ie(e){return-1!==ae.indexOf(e)}function oe(e,t,n){if("YYYY"===e)throw new RangeError("Use `yyyy` instead of `YYYY` (in `".concat(t,"`) for formatting years to the input `").concat(n,"`; see: https://git.io/fxCyr"));if("YY"===e)throw new RangeError("Use `yy` instead of `YY` (in `".concat(t,"`) for formatting years to the input `").concat(n,"`; see: https://git.io/fxCyr"));if("D"===e)throw new RangeError("Use `d` instead of `D` (in `".concat(t,"`) for formatting days of the month to the input `").concat(n,"`; see: https://git.io/fxCyr"));if("DD"===e)throw new RangeError("Use `dd` instead of `DD` (in `".concat(t,"`) for formatting days of the month to the input `").concat(n,"`; see: https://git.io/fxCyr"))}var se=/[yYQqMLwIdDecihHKkms]o|(\w)\1*|''|'(''|[^'])+('|$)|./g,ue=/P+p+|P+|p+|''|'(''|[^'])+('|$)|./g,le=/^'([^]*?)'?$/,ce=/''/g,de=/[a-zA-Z]/;function he(e,t,n){d(2,arguments);var a=String(t),r=n||{},i=r.locale||S,o=i.options&&i.options.firstWeekContainsDate,s=null==o?1:T(o),u=null==r.firstWeekContainsDate?s:T(r.firstWeekContainsDate);if(!(u>=1&&u<=7))throw new RangeError("firstWeekContainsDate must be between 1 and 7 inclusively");var l=i.options&&i.options.weekStartsOn,c=null==l?0:T(l),h=null==r.weekStartsOn?c:T(r.weekStartsOn);if(!(h>=0&&h<=6))throw new RangeError("weekStartsOn must be between 0 and 6 inclusively");if(!i.localize)throw new RangeError("locale must contain localize property");if(!i.formatLong)throw new RangeError("locale must contain formatLong property");var f=m(e);if(!g(f))throw new RangeError("Invalid time value");var w=te(f),v=k(f,w),b={firstWeekContainsDate:u,weekStartsOn:h,locale:i,_originalDate:f},p=a.match(ue).map((function(e){var t=e[0];return"p"===t||"P"===t?(0,ee[t])(e,i.formatLong,b):e})).join("").match(se).map((function(n){if("''"===n)return"'";var a=n[0];if("'"===a)return me(n);var o=$[a];if(o)return!r.useAdditionalWeekYearTokens&&ie(n)&&oe(n,t,e),!r.useAdditionalDayOfYearTokens&&re(n)&&oe(n,t,e),o(v,n,i.localize,b);if(a.match(de))throw new RangeError("Format string contains an unescaped latin alphabet character `"+a+"`");return n})).join("");return p}function me(e){return e.match(le)[1].replace(ce,"'")}var ge={lessThanXSeconds:{standalone:{one:"weniger als 1 Sekunde",other:"weniger als {{count}} Sekunden"},withPreposition:{one:"weniger als 1 Sekunde",other:"weniger als {{count}} Sekunden"}},xSeconds:{standalone:{one:"1 Sekunde",other:"{{count}} Sekunden"},withPreposition:{one:"1 Sekunde",other:"{{count}} Sekunden"}},halfAMinute:{standalone:"halbe Minute",withPreposition:"halben Minute"},lessThanXMinutes:{standalone:{one:"weniger als 1 Minute",other:"weniger als {{count}} Minuten"},withPreposition:{one:"weniger als 1 Minute",other:"weniger als {{count}} Minuten"}},xMinutes:{standalone:{one:"1 Minute",other:"{{count}} Minuten"},withPreposition:{one:"1 Minute",other:"{{count}} Minuten"}},aboutXHours:{standalone:{one:"etwa 1 Stunde",other:"etwa {{count}} Stunden"},withPreposition:{one:"etwa 1 Stunde",other:"etwa {{count}} Stunden"}},xHours:{standalone:{one:"1 Stunde",other:"{{count}} Stunden"},withPreposition:{one:"1 Stunde",other:"{{count}} Stunden"}},xDays:{standalone:{one:"1 Tag",other:"{{count}} Tage"},withPreposition:{one:"1 Tag",other:"{{count}} Tagen"}},aboutXWeeks:{standalone:{one:"etwa 1 Woche",other:"etwa {{count}} Wochen"},withPreposition:{one:"etwa 1 Woche",other:"etwa {{count}} Wochen"}},xWeeks:{standalone:{one:"1 Woche",other:"{{count}} Wochen"},withPreposition:{one:"1 Woche",other:"{{count}} Wochen"}},aboutXMonths:{standalone:{one:"etwa 1 Monat",other:"etwa {{count}} Monate"},withPreposition:{one:"etwa 1 Monat",other:"etwa {{count}} Monaten"}},xMonths:{standalone:{one:"1 Monat",other:"{{count}} Monate"},withPreposition:{one:"1 Monat",other:"{{count}} Monaten"}},aboutXYears:{standalone:{one:"etwa 1 Jahr",other:"etwa {{count}} Jahre"},withPreposition:{one:"etwa 1 Jahr",other:"etwa {{count}} Jahren"}},xYears:{standalone:{one:"1 Jahr",other:"{{count}} Jahre"},withPreposition:{one:"1 Jahr",other:"{{count}} Jahren"}},overXYears:{standalone:{one:"mehr als 1 Jahr",other:"mehr als {{count}} Jahre"},withPreposition:{one:"mehr als 1 Jahr",other:"mehr als {{count}} Jahren"}},almostXYears:{standalone:{one:"fast 1 Jahr",other:"fast {{count}} Jahre"},withPreposition:{one:"fast 1 Jahr",other:"fast {{count}} Jahren"}}},fe={date:w({formats:{full:"EEEE, do MMMM y",long:"do MMMM y",medium:"do MMM y",short:"dd.MM.y"},defaultWidth:"full"}),time:w({formats:{full:"HH:mm:ss zzzz",long:"HH:mm:ss z",medium:"HH:mm:ss",short:"HH:mm"},defaultWidth:"full"}),dateTime:w({formats:{full:"{{date}} 'um' {{time}}",long:"{{date}} 'um' {{time}}",medium:"{{date}} {{time}}",short:"{{date}} {{time}}"},defaultWidth:"full"})},we={lastWeek:"'letzten' eeee 'um' p",yesterday:"'gestern um' p",today:"'heute um' p",tomorrow:"'morgen um' p",nextWeek:"eeee 'um' p",other:"P"},ve={narrow:["J","F","M","A","M","J","J","A","S","O","N","D"],abbreviated:["Jan","Feb","Mär","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"],wide:["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"]},be={narrow:ve.narrow,abbreviated:["Jan.","Feb.","März","Apr.","Mai","Juni","Juli","Aug.","Sep.","Okt.","Nov.","Dez."],wide:ve.wide},pe={code:"de",formatDistance:function(e,t,n){var a,r=null!=n&&n.addSuffix?ge[e].withPreposition:ge[e].standalone;return a="string"==typeof r?r:1===t?r.one:r.other.replace("{{count}}",String(t)),null!=n&&n.addSuffix?n.comparison&&n.comparison>0?"in "+a:"vor "+a:a},formatLong:fe,formatRelative:function(e,t,n,a){return we[e]},localize:{ordinalNumber:function(e){return Number(e)+"."},era:p({values:{narrow:["v.Chr.","n.Chr."],abbreviated:["v.Chr.","n.Chr."],wide:["vor Christus","nach Christus"]},defaultWidth:"wide"}),quarter:p({values:{narrow:["1","2","3","4"],abbreviated:["Q1","Q2","Q3","Q4"],wide:["1. Quartal","2. Quartal","3. Quartal","4. Quartal"]},defaultWidth:"wide",argumentCallback:function(e){return e-1}}),month:p({values:ve,formattingValues:be,defaultWidth:"wide"}),day:p({values:{narrow:["S","M","D","M","D","F","S"],short:["So","Mo","Di","Mi","Do","Fr","Sa"],abbreviated:["So.","Mo.","Di.","Mi.","Do.","Fr.","Sa."],wide:["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"]},defaultWidth:"wide"}),dayPeriod:p({values:{narrow:{am:"vm.",pm:"nm.",midnight:"Mitternacht",noon:"Mittag",morning:"Morgen",afternoon:"Nachm.",evening:"Abend",night:"Nacht"},abbreviated:{am:"vorm.",pm:"nachm.",midnight:"Mitternacht",noon:"Mittag",morning:"Morgen",afternoon:"Nachmittag",evening:"Abend",night:"Nacht"},wide:{am:"vormittags",pm:"nachmittags",midnight:"Mitternacht",noon:"Mittag",morning:"Morgen",afternoon:"Nachmittag",evening:"Abend",night:"Nacht"}},defaultWidth:"wide",formattingValues:{narrow:{am:"vm.",pm:"nm.",midnight:"Mitternacht",noon:"Mittag",morning:"morgens",afternoon:"nachm.",evening:"abends",night:"nachts"},abbreviated:{am:"vorm.",pm:"nachm.",midnight:"Mitternacht",noon:"Mittag",morning:"morgens",afternoon:"nachmittags",evening:"abends",night:"nachts"},wide:{am:"vormittags",pm:"nachmittags",midnight:"Mitternacht",noon:"Mittag",morning:"morgens",afternoon:"nachmittags",evening:"abends",night:"nachts"}},defaultFormattingWidth:"wide"})},match:{ordinalNumber:E({matchPattern:/^(\d+)(\.)?/i,parsePattern:/\d+/i,valueCallback:function(e){return parseInt(e)}}),era:y({matchPatterns:{narrow:/^(v\.? ?Chr\.?|n\.? ?Chr\.?)/i,abbreviated:/^(v\.? ?Chr\.?|n\.? ?Chr\.?)/i,wide:/^(vor Christus|vor unserer Zeitrechnung|nach Christus|unserer Zeitrechnung)/i},defaultMatchWidth:"wide",parsePatterns:{any:[/^v/i,/^n/i]},defaultParseWidth:"any"}),quarter:y({matchPatterns:{narrow:/^[1234]/i,abbreviated:/^q[1234]/i,wide:/^[1234](\.)? Quartal/i},defaultMatchWidth:"wide",parsePatterns:{any:[/1/i,/2/i,/3/i,/4/i]},defaultParseWidth:"any",valueCallback:function(e){return e+1}}),month:y({matchPatterns:{narrow:/^[jfmasond]/i,abbreviated:/^(j[aä]n|feb|mär[z]?|apr|mai|jun[i]?|jul[i]?|aug|sep|okt|nov|dez)\.?/i,wide:/^(januar|februar|märz|april|mai|juni|juli|august|september|oktober|november|dezember)/i},defaultMatchWidth:"wide",parsePatterns:{narrow:[/^j/i,/^f/i,/^m/i,/^a/i,/^m/i,/^j/i,/^j/i,/^a/i,/^s/i,/^o/i,/^n/i,/^d/i],any:[/^j[aä]/i,/^f/i,/^mär/i,/^ap/i,/^mai/i,/^jun/i,/^jul/i,/^au/i,/^s/i,/^o/i,/^n/i,/^d/i]},defaultParseWidth:"any"}),day:y({matchPatterns:{narrow:/^[smdmf]/i,short:/^(so|mo|di|mi|do|fr|sa)/i,abbreviated:/^(son?|mon?|die?|mit?|don?|fre?|sam?)\.?/i,wide:/^(sonntag|montag|dienstag|mittwoch|donnerstag|freitag|samstag)/i},defaultMatchWidth:"wide",parsePatterns:{any:[/^so/i,/^mo/i,/^di/i,/^mi/i,/^do/i,/^f/i,/^sa/i]},defaultParseWidth:"any"}),dayPeriod:y({matchPatterns:{narrow:/^(vm\.?|nm\.?|Mitternacht|Mittag|morgens|nachm\.?|abends|nachts)/i,abbreviated:/^(vorm\.?|nachm\.?|Mitternacht|Mittag|morgens|nachm\.?|abends|nachts)/i,wide:/^(vormittags|nachmittags|Mitternacht|Mittag|morgens|nachmittags|abends|nachts)/i},defaultMatchWidth:"wide",parsePatterns:{any:{am:/^v/i,pm:/^n/i,midnight:/^Mitte/i,noon:/^Mitta/i,morning:/morgens/i,afternoon:/nachmittags/i,evening:/abends/i,night:/nachts/i}},defaultParseWidth:"any"})},options:{weekStartsOn:1,firstWeekContainsDate:4}};const ye=e=>{let{site:n,onDelete:a,hasDeleteFlag:r,onUndelete:i}=e;const{isSuccess:o,isTesting:u,testAgain:l}=s(n.url,n.api_key);let c="---";n.last_notification_time&&(c=he(new Date(1e3*n.last_notification_time),"Pp",{locale:pe}));let d="---";n.registration_time&&(d=he(new Date(1e3*n.registration_time),"Pp",{locale:pe}));const h=r?{textDecoration:"line-through"}:{};return(0,t.createElement)("tr",null,(0,t.createElement)("td",{className:"title column-title has-row-actions column-primary page-title"},(0,t.createElement)("strong",{style:h},n.url),"API Key: ",(0,t.createElement)("code",null,n.api_key),(0,t.createElement)("br",null),"Slug: ",(0,t.createElement)("code",null,n.slug),(0,t.createElement)("div",{className:"row-actions"},r?(0,t.createElement)("span",{className:"edit"},(0,t.createElement)("a",{style:{cursor:"pointer"},onClick:i},"Do not delete")):(0,t.createElement)("span",{className:"trash"},(0,t.createElement)("a",{style:{cursor:"pointer"},className:"submitdelete",onClick:a},"Delete")))),(0,t.createElement)("td",null,c),(0,t.createElement)("td",null,d),(0,t.createElement)("td",null,(0,t.createElement)("div",null,(0,t.createElement)("a",{className:"button button-secondary",onClick:()=>l()},"Test connection ",u?(0,t.createElement)("span",{className:"spinner is-active",style:{float:"none",margin:0,width:15,height:15,backgroundSize:"contain"}}):(0,t.createElement)("span",null,o?"✅":"🚨"))),!o&&!u&&(0,t.createElement)("i",null,"Is this plugin installed and activated on the foreign site? Is the api key correct?")))};var Me=e=>{let{sites:n,isLoading:a,deletes:r,onDelete:i,onUndelete:o}=e;return(0,t.createElement)("table",{className:"wp-list-table widefat fixed striped"},(0,t.createElement)("thead",null,(0,t.createElement)("tr",null,(0,t.createElement)("td",null,"URL / API Key"),(0,t.createElement)("td",{style:{width:120}},"Last notification"),(0,t.createElement)("td",{style:{width:120}},"Registration"),(0,t.createElement)("td",{style:{width:140}}))),(0,t.createElement)("tbody",{style:{minHeight:20}},n.map((e=>(0,t.createElement)(ye,{key:e.url,site:e,hasDeleteFlag:r.includes(e.id),onDelete:()=>i(e),onUndelete:()=>o(e)}))),a&&(0,t.createElement)("tr",null,(0,t.createElement)("td",{colSpan:3},(0,t.createElement)("span",{className:"spinner is-active",style:{float:"left"}})))))},Ce=e=>{var n,a,r,i;let{label:u,onSubmit:l,site:c={}}=e;const[d,h]=(0,t.useState)(null!==(n=null==c?void 0:c.url)&&void 0!==n?n:""),[m,g]=(0,t.useState)(null!==(a=null==c?void 0:c.slug)&&void 0!==a?a:""),[f,w]=(0,t.useState)(null!==(r=null==c?void 0:c.api_key)&&void 0!==r?r:""),[v,b]=(0,t.useState)(null!==(i=null==c?void 0:c.relation_type)&&void 0!==i?i:"observer"),p=o(d)&&f.length>0,y={paddingTop:10},{isTesting:M,isSuccess:C,testAgain:E}=s(d,f),S=d.toLowerCase().replace(/[^a-z_]/g,"");return(0,t.createElement)("div",null,(0,t.createElement)("div",{style:y},(0,t.createElement)("label",null,"URL",(0,t.createElement)("br",null),(0,t.createElement)("input",{type:"text",value:d,onChange:e=>h(e.target.value),className:"regular-text",placeholder:"https://example.de/"}))),(0,t.createElement)("div",{style:y},(0,t.createElement)("label",null,"Unique slug",(0,t.createElement)("br",null),(0,t.createElement)("input",{type:"text",value:m,onChange:e=>g(e.target.value),className:"regular-text",placeholder:S}),(0,t.createElement)("br",null),(0,t.createElement)("p",{className:"description"},"Must begin with letter. Only lowercase letters and underscores allowed."))),(0,t.createElement)("div",{style:y},(0,t.createElement)("label",null,"API Key",(0,t.createElement)("br",null),(0,t.createElement)("input",{type:"text",value:f,onChange:e=>w(e.target.value),className:"regular-text",placeholder:"API key of foreign site"}))),(0,t.createElement)("div",{style:y},(0,t.createElement)("label",null,"Relation type",(0,t.createElement)("br",null),(0,t.createElement)("select",{onChange:e=>b(e.target.value),value:v},(0,t.createElement)("option",{value:"observer"},"Observer"),(0,t.createElement)("option",{value:"observable"},"Observable"),(0,t.createElement)("option",{value:"both"},"Observer and observable")))),(0,t.createElement)("a",{className:"button button-secondary",onClick:()=>E()},"Test connection ",M?(0,t.createElement)("span",{className:"spinner is-active",style:{float:"none",margin:0,width:15,height:15,backgroundSize:"contain"}}):(0,t.createElement)("span",null,C?"✅":"🚨")),!C&&!M&&(0,t.createElement)("i",null,"Is this plugin installed and activated on the foreign site? Is the api key correct?"),(0,t.createElement)("div",{style:y},(0,t.createElement)("a",{className:"button button-secondary "+(p?"":"button-disabled"),onClick:()=>{var e;p&&l({url:d,slug:m,api_key:f,relation_type:v,registration_time:null!==(e=null==c?void 0:c.registration_time)&&void 0!==e?e:Date.now()/1e3})}},u)))},Ee=()=>{const{sites:e,isFetching:n,isSaving:o,update:s,error:u}=(()=>{const[e,n]=(0,t.useState)(1),[o,s]=(0,t.useState)(!1),[u,l]=(0,t.useState)(!1),[c,d]=(0,t.useState)([]),[h,m]=(0,t.useState)("");return(0,t.useEffect)((()=>{s(!0),(()=>{const{apiNamespace:e}=r;return a()({path:`/${e}/sites`,method:"GET",headers:i()}).then((e=>e))})().then((e=>{s(!1),d(e)}))}),[e]),{sites:c,error:h,isFetching:o,isSaving:u,update:e=>{let{dirtySites:t,deletes:n}=e;l(!0),(e=>{let{dirtySites:t,deletes:n}=e;const{apiNamespace:o}=r;return a()({path:`/${o}/sites`,method:"POST",headers:i(),data:{dirty_sites:t.map((e=>{var t;return{id:null!==(t=e.id)&&void 0!==t?t:null,slug:e.slug,url:e.url,api_key:e.api_key,relation_type:e.relation_type}})),deletes:n}}).then((e=>e))})({dirtySites:t,deletes:n}).then((e=>{l(!1),d(e)})).catch((e=>{console.error(e),m(e.message)}))}}})(),[d,h]=(0,t.useState)([]),[m,g]=(0,t.useState)([]),f=e=>{h([...d,e.id])},w=e=>{h(d.filter((t=>t!==e.id)))},v=[...e,...m];return(0,t.useEffect)((()=>{o||0!==u.length||(h([]),g([]))}),[o]),(0,t.createElement)(t.Fragment,null,(0,t.createElement)("h2",null,"Observers"),(0,t.createElement)("p",null,"These sites are watching for changes on this site."),(0,t.createElement)(Me,{sites:l(v),isLoading:n,deletes:d,onDelete:f,onUndelete:w}),(0,t.createElement)("h2",null,"Observables"),(0,t.createElement)("p",null,"This site is watching for changes on these sites."),(0,t.createElement)(Me,{sites:c(v),isLoading:n,deletes:d,onDelete:f,onUndelete:w}),(0,t.createElement)("hr",null),o?(0,t.createElement)("p",null,"Saving sites. ",(0,t.createElement)("span",{className:"spinner is-active",style:{float:"left"}})):(0,t.createElement)(Ce,{label:"Add site",onSubmit:e=>{g((t=>[...t,e]))}}),(0,t.createElement)("hr",null),u.length?(0,t.createElement)("p",null,"Corrupt state: ",u,(0,t.createElement)("br",null),(0,t.createElement)("a",{style:{cursor:"pointer"},onClick:()=>window.location.reload()},"reload page")):(0,t.createElement)("button",{className:"button button-primary",onClick:()=>{s({dirtySites:v,deletes:d})}},"Save"))};jQuery((()=>{const e=document.getElementById("content-sync__sites");(0,t.render)((0,t.createElement)(Ee,null),e)}))}();