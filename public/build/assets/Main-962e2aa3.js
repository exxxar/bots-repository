import{Y as L,a as B,A as P,S as T,b as j}from"./MapModal.vue_vue_type_style_index_0_lang-3d33acec.js";import{_ as m}from"./_plugin-vue_export-helper-c27b6911.js";import{q as r,t as c,v as e,y as d,F as g,A as u,L as C,H as _,P as k,B as h,D as p,C as w,J as b,M as N,R as v,O as y,K as M,G as S}from"./index.es-b188fb53.js";import{_ as E}from"./ShopLayout-ae538d8f.js";import"./app-70d33f57.js";import"./vue3-json-editor.esm.prod-22c6b7fc.js";import"./Pagination-0b2e35d4.js";import"./InlineInjectionsHelper.vue_vue_type_style_index_0_lang-f2e262e7.js";import"./v4-a960c1f4.js";import"./TelegramChannelHelper-a3ce9357.js";import"./vue3-perfect-scrollbar-1347635f.js";import"./index-0cbab2b5.js";const F={data(){return{title:null,text:null}},mounted(){window.addEventListener("notification",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#notification-1").toast("show")}),window.addEventListener("success",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#menu-success-1").showMenu()}),window.addEventListener("warning",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#menu-warning-1").showMenu()})}},R={id:"notification-1","data-dismiss":"notification-1","data-delay":"3000","data-autohide":"true",class:"notification notification-ios bg-dark1-dark"},O=e("span",{class:"notification-icon"},[e("i",{class:"fa fa-bell"}),e("em",null,"Оповещение"),e("i",{"data-dismiss":"notification-1",class:"fa fa-times-circle"})],-1),U={class:"font-15 color-white mb-n3"},V={class:"pt-2"},A={id:"menu-success-1",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"305","data-menu-effect":"menu-over",style:{display:"block",height:"305px",overflow:"auto"}},z=e("h1",{class:"text-center mt-4"},[e("i",{class:"fa fa-3x fa-check-circle color-green1-dark"})],-1),I={class:"text-center mt-3 text-uppercase font-700"},Y=["innerHTML"],D=e("a",{"data-dismiss":"menu-success-1",class:"close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-green1-light"},"Хорошо",-1),H={id:"menu-warning-1",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"305","data-menu-effect":"menu-over",style:{display:"block",height:"305px",overflow:"auto"}},G=e("h1",{class:"text-center mt-4"},[e("i",{class:"fa fa-3x fa-times color-red2-dark"})],-1),K={class:"text-center mt-3 text-uppercase font-700"},W=["innerHTML"],Q=e("a",{"data-dismiss":"menu-warning-1",class:"close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-red1-light"},"Хорошо",-1);function J(s,o,l,a,t,i){return r(),c(g,null,[e("div",R,[O,e("h1",U,d(t.title||"Системное"),1),e("p",V,d(t.text||"Ошибка отображения текста"),1)]),e("div",A,[z,e("h1",I,d(t.title||"Системное"),1),e("p",{class:"boxed-text-l",innerHTML:t.text},null,8,Y),D]),e("div",H,[G,e("h1",K,d(t.title||"Системное"),1),e("p",{class:"boxed-text-l",innerHTML:t.text},null,8,W),Q])],64)}const X=m(F,[["render",J]]),Z={data(){return{item:null}},mounted(){window.addEventListener("show-chashback-info",s=>{this.item=s.detail.item||null,this.$nextTick(()=>{$("#cashback-item-info").showMenu()})})},methods:{}},ee={id:"cashback-item-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"50vh",display:"block"},"data-menu-effect":"menu-over"},te={key:0,class:"w-100"},oe={class:"table table-borderless rounded-sm shadow-l m-0",style:{overflow:"hidden"}},se=e("thead",null,[e("tr",{class:"bg-gray1-dark"},[e("th",{scope:"col",class:"color-theme"},"Параметр"),e("th",{scope:"col",class:"color-theme"},"Значение")])],-1),ne=e("th",{scope:"row"},"Тип операции",-1),le={class:"font-weight-bold"},ie=e("th",{scope:"row"},"Сумма CashBack, руб",-1),re={class:"font-weight-bold"},ce={key:0},ae=e("th",{scope:"row"},"Уровень начисления",-1),de={class:"font-weight-bold"},he={key:1},ue=e("th",{scope:"row"},"Сумма в чеке, руб",-1),me={class:"font-weight-bold"},pe=e("th",{scope:"row"},"Дата операции",-1),_e={class:"font-weight-bold"},fe=e("th",{scope:"row"},"Описание операции",-1),be={class:"font-weight-bold"},ge=e("th",{scope:"row"},"TG id сотрудника",-1),we={class:"font-weight-bold"},ke=e("th",{scope:"row"},"Имя сотрудника",-1),ve={class:"font-weight-bold"},ye=e("th",{scope:"row"},"Телефон сотрудника",-1),xe={class:"font-weight-bold"};function $e(s,o,l,a,t,i){return r(),c("div",ee,[t.item?(r(),c("div",te,[e("table",oe,[se,e("tbody",null,[e("tr",null,[ne,e("td",le,d(t.item.operation_type?"Начисление":"Списание"),1)]),e("tr",null,[ie,e("td",re,d(t.item.amount||0),1)]),t.item.operation_type?(r(),c("tr",ce,[ae,e("td",de,d(t.item.level||0),1)])):u("",!0),t.item.operation_type?(r(),c("tr",he,[ue,e("td",me,d(t.item.money_in_check||0),1)])):u("",!0),e("tr",null,[pe,e("td",_e,d(s.$filters.current(t.item.created_at)),1)]),e("tr",null,[fe,e("td",be,d(t.item.description||"Нет описания"),1)]),e("tr",null,[ge,e("td",we,d(t.item.employee.telegram_chat_id||"Не указано"),1)]),e("tr",null,[ke,e("td",ve,d(t.item.employee.fio_from_telegram||"Не указано"),1)]),e("tr",null,[ye,e("td",xe,d(t.item.employee.phone||"Не указано"),1)])])])])):u("",!0)])}const Ce=m(Z,[["render",$e]]),Me={props:{code:{type:String,default:"001"}},computed:{self(){return window.self},script(){return window.currentScript},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){switch(this.code){case"001":return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa((this.code||"001")+this.self.telegram_chat_id);case"002":return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("002"+this.self.telegram_chat_id+"S"+this.script);default:return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa(this.code)}}},methods:{copy(){var s=$("<input>");$("body").append(s),s.val(this.link).select(),document.execCommand("copy"),s.remove()}}},qe={class:"w-100 p-3 object-fit-cover my-0",alt:""};function Le(s,o,l,a,t,i){const n=C("lazy");return r(),c(g,null,[_(e("img",qe,null,512),[[n,i.qr]]),e("a",{href:"javascript:void(0)",onClick:o[0]||(o[0]=k((...f)=>i.copy&&i.copy(...f),["prevent"])),class:"btn btn-link w-100 text-center"},"Скопировать ссылку")],64)}const q=m(Me,[["render",Le]]),Be={id:"qr-code",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"70vh",display:"block"},"data-menu-effect":"menu-over"},Pe={key:0,class:"w-100 p-3"},Te=e("p",{class:"mb-0 text-center"},"Дайте отсканировать этот QR-код",-1),je={data(){return{code:null}},mounted(){window.addEventListener("show-qr-code",s=>{this.code=null,this.$nextTick(()=>{this.code=s.detail.code||null,$("#qr-code").showMenu()})})},methods:{}},Ne=Object.assign(je,{__name:"QrCodeModal",setup(s){return(o,l)=>(r(),c("div",Be,[o.code?(r(),c("div",Pe,[Te,h(q,{code:o.code},null,8,["code"])])):u("",!0)]))}}),Se={data(){return{params:{win:"Номер выигрыша",name:"Ф.И.О.",phone:"Телефон",answered_by:"Кто проверил",answered_at:"Дата ответа"},item:null,eventCallbackForm:{info:null},loading:!1}},mounted(){window.addEventListener("show-event-info",s=>{this.item=s.detail.item||null,this.$nextTick(()=>{$("#event-item-info").showMenu()})})},methods:{sendApproveToUser(){this.loading=!0,this.$store.dispatch("sendApproveToUser",{dataObject:{user_telegram_chat_id:this.item.bot_user.telegram_chat_id,action_id:this.item.id,...this.eventCallbackForm}}).then(s=>{this.loading=!1,this.eventCallbackForm.info=null,$("#event-item-info").hideMenu(),this.$botNotification.success("Отлично!","Оповещение успешно отправлено")}).catch(()=>{this.loading=!1,$("#event-item-info").hideMenu(),this.$botNotification.warning("Упс!","Что-то пошло не так")})}}},Ee={id:"event-item-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"50vh",display:"block"},"data-menu-effect":"menu-over"},Fe={key:0,class:"w-100 p-3"},Re=e("h4",null,"Информирование пользователя",-1),Oe={key:0},Ue={key:1},Ve={class:"mb-0"},Ae={class:"mb-3"},ze=e("label",{for:"bill-info",class:"form-label"},"Сообщение для пользователя",-1),Ie={class:"mb-3"},Ye=["disabled"];function De(s,o,l,a,t,i){return r(),c("div",Ee,[t.item?(r(),c("div",Fe,[Re,e("ul",null,[e("li",null," № события: "+d(t.item.slug.id),1),e("li",null," Название события: "+d(t.item.slug.command),1),e("li",null," Использовано попыток: "+d(t.item.current_attempts),1),e("li",null,[p(" Дата прохождения: "),t.item.completed_at?(r(),c("span",Oe,d(s.$filters.current(t.item.completed_at)),1)):(r(),c("span",Ue,"Не установлена"))]),(r(!0),c(g,null,w(t.item.data,n=>(r(),c("li",null,[(r(!0),c(g,null,w(Object.keys(n),f=>(r(),c("p",Ve,d(t.params[f])+":"+d(n[f]||"Не установлено"),1))),256))]))),256))]),e("form",{onSubmit:o[1]||(o[1]=k((...n)=>i.sendApproveToUser&&i.sendApproveToUser(...n),["prevent"]))},[e("div",Ae,[ze,_(e("textarea",{class:"form-control",placeholder:"Информация","onUpdate:modelValue":o[0]||(o[0]=n=>t.eventCallbackForm.info=n),id:"bill-info",rows:"3",required:""},null,512),[[b,t.eventCallbackForm.info]])]),e("div",Ie,[e("button",{disabled:t.loading,type:"submit",class:"btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100"}," Отправить ",8,Ye)])],32)])):u("",!0)])}const He=m(Se,[["render",De]]),Ge={id:"menu-share",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"420px"}},Ke=e("h1",{class:"text-center font-700 font-26 mt-3 pt-2"},"Поделиться контактами",-1),We=e("p",{class:"boxed-text-xl under-heading m-0 p-0"}," Делитесь ссылкой с друзьями ",-1),Qe=e("div",{class:"divider divider-margins"},null,-1),Je={class:"row text-center mr-4 ml-4 mb-0"},Xe={class:"col-3 mb-n2"},Ze=e("i",{class:"fab fa-facebook-f font-22"},null,-1),et=e("br",null,null,-1),tt=[Ze,et],ot=e("p",{class:"font-11 opacity-70"},"Facebook",-1),st={class:"col-3 mb-n2"},nt=e("i",{class:"fab fa-twitter font-22"},null,-1),lt=e("br",null,null,-1),it=[nt,lt],rt=e("p",{class:"font-11 opacity-70"},"Twitter",-1),ct={class:"col-3 mb-n2"},at=e("i",{class:"fa-brands fa-vk font-22"},null,-1),dt=e("br",null,null,-1),ht=[at,dt],ut=e("p",{class:"font-11 opacity-70"},"VK",-1),mt={class:"col-3 mb-n2"},pt=e("i",{class:"fa fa-envelope font-22"},null,-1),_t=e("br",null,null,-1),ft=[pt,_t],bt=e("p",{class:"font-11 opacity-70"},"Email",-1),gt={class:"col-3 mb-n2"},wt=e("i",{class:"fab fa-whatsapp font-22"},null,-1),kt=e("br",null,null,-1),vt=[wt,kt],yt=e("p",{class:"font-11 opacity-70"},"WhatsApp",-1),xt={class:"col-3 mb-n2"},$t=e("i",{class:"fa fa-link font-22"},null,-1),Ct=e("br",null,null,-1),Mt=[$t,Ct],qt=e("p",{class:"font-11 opacity-70"},"Копировать",-1),Lt={class:"col-3 mb-n2"},Bt=e("i",{class:"fab fa-pinterest-p font-22"},null,-1),Pt=e("br",null,null,-1),Tt=[Bt,Pt],jt=e("p",{class:"font-11 opacity-70"},"Pinterest",-1),Nt=e("div",{class:"col-3 mb-n2"},[e("a",{href:"#",class:"close-menu icon icon-l bg-red2-dark rounded-s shadow-l text-white"},[e("i",{class:"fa fa-times font-22"}),e("br")]),e("p",{class:"font-11 opacity-70"},"Закрыть")],-1),St=e("div",{class:"divider divider-margins mt-n1 mb-3"},null,-1),Et=e("div",{class:"divider divider-margins mt-n1 mb-3"},null,-1),Ft=e("p",{class:"footer-copyright font-10 text-center pb-3 mb-1"},[p("© CashMan "),e("span",{id:"copyright-year"},"2023"),p(". Все Права защищены.")],-1),Rt={computed:{self(){return window.self},tg(){return window.Telegram.WebApp},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id)}},methods:{open(s){this.tg.openLink(s)},copy(){var s=$("<input>");$("body").append(s),s.val(this.link).select(),document.execCommand("copy"),s.remove()}}},Ot=Object.assign(Rt,{__name:"ShareMenuBar",setup(s){return(o,l)=>(r(),c("div",Ge,[Ke,We,Qe,e("div",Je,[e("div",Xe,[e("a",{href:"#",onClick:l[0]||(l[0]=a=>o.open("https://www.facebook.com/sharer/sharer.php?u="+o.link)),class:"icon icon-l bg-facebook rounded-s shadow-l text-white"},tt),ot]),e("div",st,[e("a",{href:"#",onClick:l[1]||(l[1]=a=>o.open("https://twitter.com/home?status="+o.link)),class:"icon icon-l bg-twitter rounded-s shadow-l text-white"},it),rt]),e("div",ct,[e("a",{href:"#",onClick:l[2]||(l[2]=a=>o.open("https://vk.com/share.php?url="+o.link)),class:"icon icon-l bg-linkedin rounded-s shadow-l text-white"},ht),ut]),e("div",mt,[e("a",{href:"#",onClick:l[3]||(l[3]=a=>o.open("mailto:?body="+o.link)),class:"icon icon-l bg-mail rounded-s shadow-l text-white"},ft),bt]),e("div",gt,[e("a",{href:"#",onClick:l[4]||(l[4]=a=>o.open("whatsapp://send?text="+o.link)),class:"icon icon-l bg-whatsapp rounded-s shadow-l text-white"},vt),yt]),e("div",xt,[e("a",{href:"#",onClick:l[5]||(l[5]=(...a)=>o.copy&&o.copy(...a)),class:"shareToCopyLink icon icon-l bg-blue2-dark rounded-s shadow-l text-white"},Mt),qt]),e("div",Lt,[e("a",{href:"#",onClick:l[6]||(l[6]=a=>o.open("https://pinterest.com/pin/create/button/?url="+o.link)),class:"icon icon-l bg-pinterest rounded-s shadow-l text-white"},Tt),jt]),Nt]),St,h(q,{code:"001"}),Et,Ft]))}}),Ut={},Vt={id:"menu-highlights",class:"menu menu-box-bottom menu-box-detached rounded-m",style:{display:"block",height:"510px"}},At=N('<div class="card header-card shape-rounded h-40"><div class="card-overlay bg-highlight opacity-95"></div><div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div></div><h1 class="text-center mt-4 font-35 font-800">CashMan</h1><p class="text-center font-12 mt-n1 pb-2 mb-3">Добавь красок в свою жизнь</p><div class="card card-style"><div class="highlight-changer pt-3 pb-2"><p class="text-center px-3"> Доступные цветовые схемы для использования в приложении. Нажми на кружочек и цветовая схема автоматически будет применена. </p><a href="#" data-change-highlight="blue2"><i class="fa fa-circle color-blue2-dark"></i><span class="color-blue2-light">Default</span></a><a href="#" data-change-highlight="red2"><i class="fa fa-circle color-red1-dark"></i><span class="color-red2-light">Red</span></a><a href="#" data-change-highlight="orange"><i class="fa fa-circle color-orange-dark"></i><span class="color-orange-light">Orange</span></a><a href="#" data-change-highlight="pink2"><i class="fa fa-circle color-pink2-dark"></i><span class="color-pink2-light">Pink</span></a><a href="#" data-change-highlight="magenta2"><i class="fa fa-circle color-magenta2-dark"></i><span class="color-magenta2-light">Purple</span></a><a href="#" data-change-highlight="aqua"><i class="fa fa-circle color-aqua-dark"></i><span class="color-aqua-light">Aqua</span></a><a href="#" data-change-highlight="teal"><i class="fa fa-circle color-teal-dark"></i><span class="color-teal-light">Teal</span></a><a href="#" data-change-highlight="mint"><i class="fa fa-circle color-mint-dark"></i><span class="color-mint-light">Mint</span></a><a href="#" data-change-highlight="green2"><i class="fa fa-circle color-green2-dark"></i><span class="color-green2-light">Green</span></a><a href="#" data-change-highlight="green1"><i class="fa fa-circle color-green1-dark"></i><span class="color-green1-light">Grass</span></a><a href="#" data-change-highlight="yellow2"><i class="fa fa-circle color-yellow2-dark"></i><span class="color-yellow2-light">Sunny</span></a><a href="#" data-change-highlight="yellow1"><i class="fa fa-circle color-yellow1-dark"></i><span class="color-yellow1-light">Goldish</span></a><a href="#" data-change-highlight="brown1"><i class="fa fa-circle color-brown1-dark"></i><span class="color-brown1-light">Wood</span></a><a href="#" data-change-highlight="dark1"><i class="fa fa-circle color-dark1-dark"></i><span class="color-dark1-light">Night</span></a><a href="#" data-change-highlight="dark2"><i class="fa fa-circle color-dark2-dark"></i><span class="color-dark2-light">Dark</span></a><div class="clearfix"></div></div></div><a href="#" class="close-menu btn btn-full btn-margins rounded-sm shadow-l bg-highlight btn-m font-900 text-uppercase mb-0">Закрыть смену цвета</a>',5),zt=[At];function It(s,o,l,a,t,i){return r(),c("div",Vt,zt)}const Yt=m(Ut,[["render",It]]),Dt={data(){return{load:!1}},mounted(){window.addEventListener("preloader-toggle",s=>{this.load=!this.load}),window.addEventListener("preloader-hide",s=>{this.load=!1}),window.addEventListener("preloader-show",s=>{this.load=!0,setTimeout(()=>{this.load=!1},1e3)})}},Ht={key:0,id:"preloader"},Gt=e("div",{class:"spinner-border color-highlight",role:"status"},null,-1),Kt=[Gt];function Wt(s,o,l,a,t,i){return t.load?(r(),c("div",Ht,Kt)):u("",!0)}const Qt=m(Dt,[["render",Wt]]),Jt={data(){return{id:null}},mounted(){window.addEventListener("open-page-menu-modal",s=>{this.id=s.detail.id||null,$("#menu-page-editor").showMenu()})},methods:{duplicatePage(){this.loading=!0,this.$store.dispatch("duplicatePage",{dataObject:{pageId:this.id}}).then(s=>{this.loading=!1,$("#menu-page-editor").hideMenu(),this.$botPages.reloadPageList()}).catch(()=>{this.loading=!1})},removePage(){this.loading=!0,this.$store.dispatch("removePage",{dataObject:{pageId:this.id}}).then(s=>{this.loading=!1,$("#menu-page-editor").hideMenu(),this.$botPages.reloadPageList()}).catch(()=>{this.loading=!1})}}},Xt={id:"menu-page-editor",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"305","data-menu-effect":"menu-over",style:{height:"275px",display:"block"}},Zt=e("h1",{class:"text-center mt-4"},[e("i",{class:"fa fa-3x fa-check-circle color-green1-dark"})],-1),eo=e("h1",{class:"text-center mt-3 text-uppercase font-700"},"Меню страницы",-1),to=e("i",{class:"fa-solid fa-copy mr-1"},null,-1),oo=e("i",{class:"fa-solid fa-trash mr-1"},null,-1);function so(s,o,l,a,t,i){return r(),c("div",Xt,[Zt,eo,e("a",{href:"javascript:void(0)",onClick:o[0]||(o[0]=(...n)=>i.duplicatePage&&i.duplicatePage(...n)),"data-dismiss":"menu-page-editor",class:"bg-highlight btn btn-m font-900 text-uppercase btn-center-xl mb-2"},[to,p(" Дублировать страницу ")]),e("a",{href:"javascript:void(0)",onClick:o[1]||(o[1]=(...n)=>i.removePage&&i.removePage(...n)),"data-dismiss":"menu-page-editor",class:"bg-red2-dark btn btn-m font-900 text-uppercase btn-center-xl mb-2"},[oo,p(" Удалить страницу ")])])}const no=m(Jt,[["render",so]]),lo={data(){return{rules:[{id:1,title:"Является администратором",rules_block:"bot_user",rule:{is_admin:!0}},{id:2,title:"Является VIP-пользователем",rules_block:"bot_user",rule:{is_vip:!0}},{id:3,title:"Находится в заведении",rules_block:"bot_user",rule:{user_in_location:!0}},{id:4,title:"За работой",rules_block:"bot_user",rule:{is_work:!0}},{id:5,title:"Возраст от ...",rules_block:"bot_user",rule:{age:18}},{id:6,title:"Находится в городе ...",rules_block:"bot_user",rule:{city:"Краснодар"}},{id:7,title:"Пол",rules_block:"bot_user",rule:{sex:!0}},{id:8,title:"Состоит в канале",rules_block:"channels",rule:{channel:1}},{id:9,title:"Является Менеджером",rules_block:"bot_user",rule:{is_manager:!0}}]}},mounted(){window.addEventListener("open-rules-modal",s=>{$("#rules-selector").showMenu()})},methods:{duplicatePage(){this.loading=!0,this.$store.dispatch("duplicatePage",{dataObject:{pageId:this.id}}).then(s=>{this.loading=!1,$("#menu-page-editor").hideMenu(),this.$botPages.reloadPageList()}).catch(()=>{this.loading=!1})},removePage(){this.loading=!0,this.$store.dispatch("removePage",{dataObject:{pageId:this.id}}).then(s=>{this.loading=!1,$("#menu-page-editor").hideMenu(),this.$botPages.reloadPageList()}).catch(()=>{this.loading=!1})},addRules(s){this.$botPages.selectRule(s),$("#rules-selector").hideMenu()}}},io={id:"rules-selector",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"400","data-menu-effect":"menu-over",style:{height:"400px",display:"block"}},ro=e("h1",{class:"text-center mt-3 text-uppercase font-700"},"Выбор правила",-1),co={class:"list-group list-custom-small px-2"},ao=["onClick"],ho=e("i",{class:"fa font-13 fa-check color-green1-dark"},null,-1);function uo(s,o,l,a,t,i){return r(),c("div",io,[ro,e("div",co,[(r(!0),c(g,null,w(t.rules,n=>(r(),c("a",{onClick:f=>i.addRules(n),href:"javascript:void(0)"},[ho,e("span",null,d(n.title||"Не указан"),1)],8,ao))),256))])])}const mo=m(lo,[["render",uo]]),po={data(){return{load:!1,keyboard:null,select:{uuid:null,row:0,col:0,type:null}}},mounted(){window.addEventListener("open-keyboard-editor",s=>{const o=s.detail.select,l=s.detail.keyboard||null;this.select=o,this.keyboard=l,$("#keyboard-editor").showMenu()})},methods:{needRemoveField(s,o,l){Object.keys(this.keyboard[o][l]).forEach(a=>{a!=="text"&&a!==s&&delete this.keyboard[o][l][a]})}}},_o={id:"keyboard-editor",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"405","data-menu-effect":"menu-over",style:{height:"405px",display:"block"}},fo={class:"mb-3"},bo=["for"],go=["id"],wo=e("div",{class:"divider divider-small my-3 bg-highlight"},null,-1),ko=e("div",{class:"alert alert-danger",role:"alert"}," Возможно выбрать только 1 тип действия ",-1),vo={class:"mb-3"},yo=["for"],xo=["id"],$o={class:"mb-3"},Co=["for"],Mo=["id"],qo={class:"mb-3"},Lo=["for"],Bo=["id"],Po={class:"mb-3"},To=["for"],jo=["id"],No={class:"form-check"},So=["id"],Eo=["for"],Fo={key:0,class:"form-check"},Ro=["id"],Oo=["for"],Uo={class:"form-check"},Vo=["id"],Ao=["for"],zo={class:"form-check"},Io=["id"],Yo=["for"];function Do(s,o,l,a,t,i){return r(),c("div",_o,[t.keyboard?(r(),c("form",{key:0,class:"px-3 py-3",onSubmit:o[16]||(o[16]=k((...n)=>s.submitKeyboard&&s.submitKeyboard(...n),["prevent"]))},[e("div",fo,[e("label",{for:"title-row-"+t.select.row+"-col-"+t.select.col,class:"form-label"},"Название поля",8,bo),_(e("input",{type:"text","onUpdate:modelValue":o[0]||(o[0]=n=>t.keyboard[t.select.row][t.select.col].text=n),class:"form-control",id:"title-row-"+t.select.row+"-col-"+t.select.col,placeholder:"Название"},null,8,go),[[b,t.keyboard[t.select.row][t.select.col].text]])]),wo,ko,e("div",vo,[e("label",{for:"command-row-"+t.select.row+"-col-"+t.select.col,class:"form-label"},"Команда (для меню в сообщении)",8,yo),_(e("input",{type:"text",onChange:o[1]||(o[1]=n=>i.needRemoveField("callback_data",t.select.row,t.select.col)),"onUpdate:modelValue":o[2]||(o[2]=n=>t.keyboard[t.select.row][t.select.col].callback_data=n),class:"form-control",id:"command-row-"+t.select.row+"-col-"+t.select.col,placeholder:"/start"},null,40,xo),[[b,t.keyboard[t.select.row][t.select.col].callback_data]])]),e("div",$o,[e("label",{for:"switch-inline-query-row-"+t.select.row+"-col-"+t.select.col,class:"form-label"},"Ссылка на аккаунт в ТЕЛЕГРАММ",8,Co),_(e("input",{type:"text",class:"form-control",onChange:o[3]||(o[3]=n=>i.needRemoveField("switch_inline_query",t.select.row,t.select.col)),"onUpdate:modelValue":o[4]||(o[4]=n=>t.keyboard[t.select.row][t.select.col].switch_inline_query=n),id:"switch-inline-query-row-"+t.select.row+"-col-"+t.select.col,placeholder:"@YourAccountLink"},null,40,Mo),[[b,t.keyboard[t.select.row][t.select.col].switch_inline_query]])]),e("div",qo,[e("label",{for:"url-row-"+t.select.row+"-col-"+t.select.col,class:"form-label"},"Внешняя URL-ссылка",8,Lo),_(e("input",{type:"text",class:"form-control",onChange:o[5]||(o[5]=n=>i.needRemoveField("url",t.select.row,t.select.col)),"onUpdate:modelValue":o[6]||(o[6]=n=>t.keyboard[t.select.row][t.select.col].url=n),id:"url-row-"+t.select.row+"-col-"+t.select.col,placeholder:"https://t.me/example"},null,40,Bo),[[b,t.keyboard[t.select.row][t.select.col].url]])]),e("div",Po,[e("label",{for:"switch-inline-query-current-chat-row-"+t.select.row+"-col-"+t.select.col,class:"form-label"},"Команда всплывающего меню бота",8,To),_(e("input",{type:"text",class:"form-control",onChange:o[7]||(o[7]=n=>i.needRemoveField("switch_inline_query_current_chat",t.select.row,t.select.col)),"onUpdate:modelValue":o[8]||(o[8]=n=>t.keyboard[t.select.row][t.select.col].switch_inline_query_current_chat=n),id:"witch-inline-query-current-chat-row-"+t.select.row+"-col-"+t.select.col,placeholder:"команда"},null,40,jo),[[b,t.keyboard[t.select.row][t.select.col].switch_inline_query_current_chat]])]),e("div",No,[e("input",{type:"radio",onChange:o[9]||(o[9]=n=>i.needRemoveField(null,t.select.row,t.select.col)),name:"request-radio",class:"form-check-input",id:"no-action-row-"+t.select.row+"-col-"+t.select.col},null,40,So),e("label",{class:"form-check-label",for:"no-action-row-"+t.select.row+"-col-"+t.select.col}," Без действий ",8,Eo)]),t.select.row===0?(r(),c("div",Fo,[e("input",{type:"radio",onChange:o[10]||(o[10]=n=>i.needRemoveField("pay",t.select.row,t.select.col)),onClick:o[11]||(o[11]=n=>t.keyboard[t.select.row][t.select.col].pay=!0),name:"request-radio",class:"form-check-input",id:"pay-action-row-"+t.select.row+"-col-"+t.select.col},null,40,Ro),e("label",{class:"form-check-label",for:"pay-action-row-"+t.select.row+"-col-"+t.select.col}," Кнопка оплаты ",8,Oo)])):u("",!0),e("div",Uo,[e("input",{type:"radio",onChange:o[12]||(o[12]=n=>i.needRemoveField("request_contact",t.select.row,t.select.col)),onClick:o[13]||(o[13]=n=>t.keyboard[t.select.row][t.select.col].request_contact=!0),name:"request-radio",class:"form-check-input",id:"phone-row-"+t.select.row+"-col-"+t.select.col},null,40,Vo),e("label",{class:"form-check-label",for:"phone-row-"+t.select.row+"-col-"+t.select.col}," Запросить телефон (для нижнего меню) ",8,Ao)]),e("div",zo,[e("input",{type:"radio",name:"request-radio",onChange:o[14]||(o[14]=n=>i.needRemoveField("request_location",t.select.row,t.select.col)),onClick:o[15]||(o[15]=n=>t.keyboard[t.select.row][t.select.col].request_location=!0),class:"form-check-input",id:"location-row-"+t.select.row+"-col-"+t.select.col},null,40,Io),e("label",{class:"form-check-label",for:"location-row-"+t.select.row+"-col-"+t.select.col}," Запросить локацию (для нижнего меню) ",8,Yo)])],32)):u("",!0)])}const Ho=m(po,[["render",Do]]),Go={data(){return{param:null,channelLink:null}},mounted(){window.addEventListener("open-tg-helper-modal",s=>{this.param=s.detail.param,$("#tg-helper-modal").showMenu()})},methods:{checkLink(){this.channelLink.indexOf("https://t.me/")!==-1&&(this.channelLink="@"+(this.channelLink.split("https://t.me/")[1]||this.channelLink))},requestTelegramChannelId(){this.$store.dispatch("requestTelegramChannelId",{dataObject:{channel:this.channelLink}}).then(s=>{s.ok&&this.$botPages.telegramChannelCallback(this.param,s.result.chat.id),$("#tg-helper-modal").hideMenu(),s.ok&&this.$botNotification.success("Отлично","Канал успешно найден!"),s.ok||(this.$botNotification.warning("Ошибочка!","Неверно указанный канал"),this.$botPages.telegramChannelCallback(this.param,null))}).catch(()=>{})}}},Ko={id:"tg-helper-modal",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"210","data-menu-effect":"menu-over",style:{height:"210px",display:"block"}},Wo=e("p",{class:"mb-1 p-2"},"Укажите в полне ниже ссылку на ПУБЛИЧНЫЙ канал, в котором уже состоит БОТ в качестве администратора канала",-1),Qo=e("button",{class:"btn btn-m btn-full rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100"},"Узнать id канала ",-1);function Jo(s,o,l,a,t,i){return r(),c("div",Ko,[Wo,e("form",{onSubmit:o[2]||(o[2]=k((...n)=>i.requestTelegramChannelId&&i.requestTelegramChannelId(...n),["prevent"])),class:"p-2"},[_(e("input",{type:"text",class:"form-control mb-2",id:"search-description-text","onUpdate:modelValue":o[0]||(o[0]=n=>t.channelLink=n),onChange:o[1]||(o[1]=(...n)=>i.checkLink&&i.checkLink(...n)),placeholder:"@telegram_channel",required:""},null,544),[[b,t.channelLink]]),Qo],32)])}const Xo=m(Go,[["render",Jo]]),Zo={components:{YandexMap:L,YandexMarker:B},setup(){return{center:[45.03547,38.975313]}},data(){return{load:!0,coords:{lat:45.03547,lon:38.975313}}},mounted(){window.addEventListener("open-map-modal",s=>{$("#map-selector").showMenu()})},methods:{onClick(s){let o=s.get("coords");this.load=!1,this.coords.lat=o[0],this.coords.lon=o[1],this.$nextTick(()=>{this.load=!0}),this.$botNotification.notification("Координаты","Вы выбрали координаты"),this.$botPages.mapCallback(this.coords)}}},es={id:"map-selector",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"500","data-menu-effect":"menu-over",style:{height:"500px",display:"block"}},ts=e("h1",{class:"text-center mt-3 text-uppercase font-700"},"Выбор координаты",-1);function os(s,o,l,a,t,i){const n=v("YandexMarker"),f=v("YandexMap");return r(),c("div",es,[ts,h(f,{coordinates:[t.coords.lat,t.coords.lon],onClick:i.onClick},{default:y(()=>[t.load?(r(),M(n,{key:0,coordinates:[t.coords.lat,t.coords.lon],"marker-id":123},null,8,["coordinates"])):u("",!0)]),_:1},8,["coordinates","onClick"])])}const x=m(Zo,[["render",os]]),ss={data(){return{notes:[],param:null}},mounted(){this.loadNotes(),window.addEventListener("open-notes-modal",s=>{this.param=s.detail.param,$("#notes-selector").showMenu()})},methods:{loadNotes(){this.loading=!0,this.$store.dispatch("loadNotes").then(s=>{this.loading=!1}).catch(()=>{this.loading=!1})},selectNote(s){this.$botPages.selectNote(s,this.param),$("#notes-selector").hideMenu()}}},ns={id:"notes-selector",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"400","data-menu-effect":"menu-over",style:{height:"400px",display:"block"}},ls=e("h1",{class:"text-center mt-3 text-uppercase font-700"},"Выбор заметок",-1),is={key:0,class:"list-group list-custom-small px-2"},rs=["onClick"],cs=e("i",{class:"fa font-13 fa-check color-green1-dark"},null,-1);function as(s,o,l,a,t,i){return r(),c("div",ns,[ls,t.notes.length>0?(r(),c("div",is,[(r(!0),c(g,null,w(t.notes,n=>(r(),c("a",{onClick:f=>i.selectNote(n),href:"javascript:void(0)"},[cs,e("span",null,d(n.text||"Не указан"),1)],8,rs))),256))])):u("",!0)])}const ds=m(ss,[["render",as]]);const hs={key:0,class:"page-content",style:{"min-height":"667px"}},us={class:"page-title page-title-small"},ms=e("i",{class:"fa fa-arrow-left"},null,-1),ps={href:"javascript:void(0)",class:"bg-fade-gray1-dark shadow-xl d-flex justify-content-center align-items-center font-18 bot-avatar"},_s={style:{width:"50px","object-fit":"cover","border-radius":"50%"},alt:""},fs=e("div",{class:"card header-card shape-rounded",style:{height:"115px"}},[e("div",{class:"card-overlay bg-highlight opacity-95"}),e("div",{class:"card-overlay dark-mode-tint"}),e("div",{class:"card-bg preload-img","data-src":"/shop/images/pictures/20s.jpg"})],-1),bs={key:0,class:"footer"},gs={class:"card card-style mb-0"},ws={href:"#",class:"footer-title p-4"},ks=e("p",{class:"text-center font-12 mt-n1 mb-3 opacity-70"},[p(" Добавь "),e("span",{class:"color-highlight"},"красок"),p(" в свою жизнь ")],-1),vs={class:"boxed-text-l"},ys={class:"text-center mb-3"},xs=e("i",{class:"fa-solid fa-at"},null,-1),$s=[xs],Cs=e("i",{class:"fa-brands fa-vk"},null,-1),Ms=[Cs],qs=e("i",{class:"fa fa-phone"},null,-1),Ls=[qs],Bs=e("a",{href:"#","data-menu":"menu-share",class:"icon icon-xs rounded-sm mr-1 shadow-l bg-red2-dark text-white"},[e("i",{class:"fa fa-share-alt"})],-1),Ps=e("a",{href:"#",class:"back-to-top icon icon-xs rounded-sm shadow-l bg-highlight text-white"},[e("i",{class:"fa fa-arrow-up"})],-1),Ts=e("p",{class:"footer-copyright pb-3 mb-1"},[p("© CashMan "),e("span",{id:"copyright-year"},"2023"),p(". Все Права защищены.")],-1),js=e("div",{class:"footer-card card shape-rounded","data-card-height":"230",style:{height:"230px"}},[e("div",{class:"card-overlay bg-highlight opacity-95"}),e("div",{class:"card-bg preload-img","data-src":"/shop/images/pictures/20s.jpg"})],-1),Ns={computed:{...S(["getSelf"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},self(){return window.self||null},tg(){return window.Telegram.WebApp},tgUser(){const s=new URLSearchParams(this.tg.initData);return JSON.parse(s.get("user"))},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id)}},created(){window.currentScript=this.slug_id||null,window.currentBot=this.bot.data,this.$store.dispatch("loadSelf").then(()=>{window.self=this.getSelf}),this.$botNotification.success("Главная","Успешно!")},methods:{open(s){this.tg.openLink(s)}}},Hs=Object.assign(Ns,{__name:"Main",props:{bot:{type:Object},slug_id:{type:String}},setup(s){return(o,l)=>{const a=v("router-view"),t=C("lazy");return r(),M(E,null,{default:y(()=>[o.self?(r(),c("div",hs,[e("div",us,[e("h2",null,[e("a",{href:"javascript:void(0)",onClick:l[0]||(l[0]=i=>o.$router.back())},[ms,p(" "+d(o.$route.meta.title||"Меню"),1)])]),e("a",ps,[_(e("img",_s,null,512),[[t,o.logo]])])]),fs,h(a,{bot:s.bot},null,8,["bot"]),o.currentBot?(r(),c("div",bs,[e("div",gs,[e("a",ws,d(o.currentBot.company.title||"CashMan:Shopify"),1),ks,e("p",vs,d(o.currentBot.company.description||"Описание вашего магазина"),1),e("div",ys,[o.currentBot.company.email?(r(),c("a",{key:0,href:"#",onClick:l[1]||(l[1]=i=>o.open("mailTo:"+o.currentBot.company.email)),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-facebook text-white"},$s)):u("",!0),o.currentBot.company.links?(r(),c("a",{key:1,href:"#",onClick:l[2]||(l[2]=i=>o.open(o.currentBot.company.links[0])),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-vk text-white"},Ms)):u("",!0),o.currentBot.company.phones?(r(),c("a",{key:2,href:"#",onClick:l[3]||(l[3]=i=>o.open("tel:"+o.currentBot.company.phones[0])),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-phone text-white"},Ls)):u("",!0),Bs,Ps]),Ts]),js])):u("",!0)])):u("",!0)]),modals:y(()=>[h(Qt),h(P),h(Ce),h(Ne),h(He),h(X),h(Ot),h(Yt),h(T),h(j),h(no),h(Ho),h(mo),h(Xo),h(x),h(x),h(ds)]),_:1})}}});export{Hs as default};
