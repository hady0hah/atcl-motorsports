(self.webpackChunk=self.webpackChunk||[]).push([[615],{6248:(t,e,s)=>{"use strict";t.exports=s.p+"images/logo-ATCL.492e0694.png"},4167:(t,e,s)=>{"use strict";t.exports=s.p+"images/pdf-download.f74116fe.png"},2148:(t,e,s)=>{"use strict";t.exports=s.p+"images/race-rally-racer-track-wallpaper.6775a1f4.jpg"},4752:(t,e,s)=>{"use strict";s.d(e,{Z:()=>l});s(9070);var a=s(9669),n=s.n(a),o=s(5382),i=s.n(o);function r(t,e){for(var s=0;s<e.length;s++){var a=e[s];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}s(538).default.use(i(),n());const l=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,s,a;return e=t,(s=[{key:"getRequest",value:function(t,e){return n().get(t,e)}},{key:"postRequest",value:function(t,e,s){return n().post(t,e,s).catch((function(t){console.log(t)}))}}])&&r(e.prototype,s),a&&r(e,a),t}())},6032:(t,e,s)=>{"use strict";s.d(e,{Z:()=>i});var a=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("header",[a("b-container",[a("b-row",[a("div",{staticClass:"logo-wrapper"},[a("img",{attrs:{src:s(6248)}})]),t._v(" "),a("b-col",[a("ul",{class:["navbar-list",{active:t.active}]},[a("router-link",{attrs:{tag:"li",to:"/",exact:""}},[t._v("Home")]),t._v(" "),a("router-link",{attrs:{tag:"li",to:"regulations"}},[t._v("Regulations")])],1)]),t._v(" "),a("div",{class:["hamburger",{active:t.active}],on:{click:function(e){t.active=!t.active}}},[a("span",{staticClass:"bar"}),t._v(" "),a("span",{staticClass:"bar"}),t._v(" "),a("span",{staticClass:"bar"})]),t._v(" "),a("div",{staticClass:"navbar-list"},[a("a",{attrs:{href:"https://www.rallylebanon.com/",target:"_blank"}},[t._v("Rally of Lebanon")])])],1)],1)],1)};a._withStripped=!0;const n={name:"Header",data:function(){return{active:!1}}};var o=(0,s(1900).Z)(n,a,[],!1,null,"83feee70",null);o.options.__file="assets/components/Header.vue";const i=o.exports},1594:(t,e,s)=>{"use strict";s.d(e,{Z:()=>i});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("b-container",{staticClass:"pages-footer",attrs:{fluid:""}},[s("b-row",{attrs:{"no-gutters":"","align-v":"stretch"}},[s("b-col",{staticClass:"rights",attrs:{md:"6"}},[t._v("\n      © 2021 ATCL Motorsports, ALL RIGHTS RESERVED\n    ")]),t._v(" "),s("b-col",{staticClass:"obsoft",attrs:{md:"6"}},[s("div",{staticStyle:{"line-height":"3.2em"}},[t._v("\n        Engineered by\n      ")]),t._v(" "),s("div",[s("a",{attrs:{href:"https://www.potech.global",target:"_blank"}})])])],1)],1)};a._withStripped=!0;const n={name:"PagesFooter"};var o=(0,s(1900).Z)(n,a,[],!1,null,"1816e554",null);o.options.__file="assets/components/PagesFooter.vue";const i=o.exports},9639:(t,e,s)=>{"use strict";s.r(e),s.d(e,{default:()=>f});var a=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("Header"),t._v(" "),a("ScrollToTop"),t._v(" "),a("TopTitle",{attrs:{"above-title":"Sporting Regulations","main-title":"Regulations"}}),t._v(" "),a("section",{staticClass:"regulations-pills-section mt-5"},[a("b-container",[a("b-row",[a("b-col",{attrs:{cols:"12"}},[a("ul",{ref:"pillsWrapper",staticClass:"pills-wrapper nav nav-pills",on:{mousedown:t.pillsMouseDown,mousemove:t.pillsMouseMove,mouseup:function(e){t.pillsScrolling=!1},mouseleave:function(e){t.pillsScrolling=!1}}},t._l(t.categories,(function(e){return a("li",[a("a",{attrs:{href:"#"+e.name}},[t._v(t._s(e.label))])])})),0)])],1)],1)],1),t._v(" "),t.categoriesLoading?a("div",{staticClass:"loader"}):a("div",t._l(t.categories,(function(e){return a("section",{staticClass:"regulations-section",attrs:{id:e.name}},[a("b-container",[a("b-row",[a("b-col",{staticClass:"regulations-title",attrs:{cols:"12"}},[a("h2",[t._v("\n              "+t._s(e.label)+"\n            ")])]),t._v(" "),a("b-col",{attrs:{cols:"12"}},[a("ul",{staticClass:"documents-list"},t._l(e.documents,(function(e){return a("li",[a("a",{attrs:{href:e.documentPath+"/"+e.document,target:"_blank"}},[a("img",{attrs:{src:s(4167)}}),t._v("\n                  "+t._s(e.label))])])})),0)])],1)],1)],1)})),0),t._v(" "),a("footer",{staticStyle:{"margin-top":"2em"}},[a("PagesFooter")],1)],1)};a._withStripped=!0;s(1539),s(8674),s(5666);var n=s(7324),o=s(8830),i=s(6032),r=s(3687),l=s(1594),c=s(4752);function u(t,e,s,a,n,o,i){try{var r=t[o](i),l=r.value}catch(t){return void s(t)}r.done?e(l):Promise.resolve(l).then(a,n)}const p={name:"Regulations",components:{PagesFooter:l.Z,ScrollToTop:r.Z,Header:i.Z,TopTitle:o.Z,Title:n.Z},data:function(){return{pillsScrolling:!1,pillsStartX:0,pillsScrollLeft:0,categoriesLoading:!1,categories:[],sections:["General","Speed Test","Rotax Max","Spring Rally","Rock Crawling"]}},methods:{pillsMouseDown:function(t){var e=this.$refs.pillsWrapper;this.pillsScrolling=!0,this.pillsStartX=t.pageX-e.offsetLeft,this.pillsScrollLeft=e.scrollLeft},pillsMouseMove:function(t){if(this.pillsScrolling){var e=this.$refs.pillsWrapper,s=t.pageX-e.offsetLeft-this.pillsStartX;e.scrollLeft=this.pillsScrollLeft-s}},fetchDocumentCategoriesList:function(){var t,e=this;return(t=regeneratorRuntime.mark((function t(){var s;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return(s=e).categoriesLoading=!0,t.next=4,c.Z.getRequest("/api/document/category/list",{}).then((function(t){return t.data})).catch((function(t){console.log(t)})).then((function(t){s.categoriesLoading=!1,s.categories=t.data}));case 4:case"end":return t.stop()}}),t)})),function(){var e=this,s=arguments;return new Promise((function(a,n){var o=t.apply(e,s);function i(t){u(o,a,n,i,r,"next",t)}function r(t){u(o,a,n,i,r,"throw",t)}i(void 0)}))})()}},mounted:function(){this.fetchDocumentCategoriesList().then((function(){}))}};var v=(0,s(1900).Z)(p,a,[],!1,null,"06522844",null);v.options.__file="assets/components/Regulations.vue";const f=v.exports},3687:(t,e,s)=>{"use strict";s.d(e,{Z:()=>i});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"show",rawName:"v-show",value:t.scY>100,expression:"scY > 100"}],staticClass:"scroll-top-top",on:{click:t.toTop}},[s("i",{staticClass:"fas fa-arrow-up"})])};a._withStripped=!0;s(2564);const n={name:"ScrollToTop",data:function(){return{scTimer:0,scY:0}},methods:{handleScroll:function(){var t=this;this.scTimer||(this.scTimer=setTimeout((function(){t.scY=window.scrollY,clearTimeout(t.scTimer),t.scTimer=0}),100))},toTop:function(){window.scrollTo({top:0,behavior:"smooth"})}},mounted:function(){window.addEventListener("scroll",this.handleScroll)}};var o=(0,s(1900).Z)(n,a,[],!1,null,"779185e2",null);o.options.__file="assets/components/ScrollToTop.vue";const i=o.exports},7324:(t,e,s)=>{"use strict";s.d(e,{Z:()=>i});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("b-row",{staticClass:"section-title-wrapper"},[s("b-col",{staticClass:"section-title",attrs:{sm:"6","align-self":"stretch"}},[s("h2",[t._v("\n      "+t._s(t.title)+"\n    ")])]),t._v(" "),s("b-col",{staticClass:"section-title-info",attrs:{sm:"6","align-self":"stretch"}},[s("h6",[t._v("\n      "+t._s(t.info)+"\n    ")])])],1)};a._withStripped=!0;const n={name:"Title",props:["title","info"]};var o=(0,s(1900).Z)(n,a,[],!1,null,"8676d326",null);o.options.__file="assets/components/Title.vue";const i=o.exports},8830:(t,e,s)=>{"use strict";s.d(e,{Z:()=>i});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("section",{staticClass:"top-title-section",style:"background-image: linear-gradient(white, white),url('"+t.backgroundImage+"');"},[s("b-container",{staticClass:"w-100"},[s("b-row",{staticClass:"w-100"},[s("b-col",{staticClass:"back-title",attrs:{cols:"12"}},[s("h1",[t._v(t._s(t.backTitle))])]),t._v(" "),s("b-col",{staticClass:"top-title-col",attrs:{lg:"6"}},[s("div",{staticClass:"top-title-wrapper"},[s("h5",[t._v(t._s(t.aboveTitle))]),t._v(" "),s("h1",[t._v(t._s(t.mainTitle))])])])],1)],1)],1)};a._withStripped=!0;const n={name:"TopTitle",props:["backTitle","mainTitle","aboveTitle","backImage"],data:function(){return{backgroundImage:s(2148)}},mounted:function(){this.backImage&&(this.backgroundImage=this.backImage)}};var o=(0,s(1900).Z)(n,a,[],!1,null,"294e0704",null);o.options.__file="assets/components/TopTitle.vue";const i=o.exports}}]);