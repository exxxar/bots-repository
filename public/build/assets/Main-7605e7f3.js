import{o as i,q as n,t,x as r,F as w,C as m,z as h,B as x,H as b,L as C,K as B,J as k,G as g,O as y,A as c,N as d,u as T,Y as M,M as S,D as L}from"./index.es-0047b125.js";import{_ as p}from"./_plugin-vue_export-helper-c27b6911.js";import{m as v}from"./index-4c54c503.js";import{b as F}from"./app-ab069e06.js";import"./default-1552e1c5.js";import"./vue3-perfect-scrollbar-2ae52499.js";const P={data(){return{title:null,text:null}},mounted(){window.addEventListener("notification",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#notification-1").toast("show")}),window.addEventListener("success",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#menu-success-1").showMenu()}),window.addEventListener("warning",s=>{this.title=s.detail.title||null,this.text=s.detail.text||null,$("#menu-warning-1").showMenu()})}},z={id:"notification-1","data-dismiss":"notification-1","data-delay":"3000","data-autohide":"true",class:"notification notification-ios bg-dark1-dark"},N=t("span",{class:"notification-icon"},[t("i",{class:"fa fa-bell"}),t("em",null,"Оповещение"),t("i",{"data-dismiss":"notification-1",class:"fa fa-times-circle"})],-1),q={class:"font-15 color-white mb-n3"},A={class:"pt-2"},E={id:"menu-success-1",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"305","data-menu-effect":"menu-over",style:{display:"block",height:"305px"}},j=t("h1",{class:"text-center mt-4"},[t("i",{class:"fa fa-3x fa-check-circle color-green1-dark"})],-1),U={class:"text-center mt-3 text-uppercase font-700"},O=["innerHTML"],D=t("a",{"data-dismiss":"menu-success-1",class:"close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-green1-light"},"Хорошо",-1),I={id:"menu-warning-1",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-height":"305","data-menu-effect":"menu-over",style:{display:"block",height:"305px"}},V=t("h1",{class:"text-center mt-4"},[t("i",{class:"fa fa-3x fa-times color-red2-dark"})],-1),G={class:"text-center mt-3 text-uppercase font-700"},H=["innerHTML"],W=t("a",{"data-dismiss":"menu-warning-1",class:"close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-red1-light"},"Хорошо",-1);function J(s,e,u,_,a,o){return i(),n(w,null,[t("div",z,[N,t("h1",q,r(a.title||"Системное"),1),t("p",A,r(a.text||"Ошибка отображения текста"),1)]),t("div",E,[j,t("h1",U,r(a.title||"Системное"),1),t("p",{class:"boxed-text-l",innerHTML:a.text},null,8,O),D]),t("div",I,[V,t("h1",G,r(a.title||"Системное"),1),t("p",{class:"boxed-text-l",innerHTML:a.text},null,8,H),W])],64)}const R=p(P,[["render",J]]),K={data(){return{product:null}},computed:{...v(["inCart","cartTotalCount","inFav"]),currentBot(){return window.currentBot},self(){return window.self},currentPrice(){return this.product.current_price},checkInCart(){return this.inCart(this.product.id)}},mounted(){window.addEventListener("add-to-cart",s=>{this.product=s.detail.product||null,this.product.in_favorite=this.inFav(this.product.id),this.$nextTick(()=>{$("#menu-product-info").showMenu()})})},methods:{removeFromFavorites(){this.product.in_favorite=!1,this.$store.dispatch("removeFromFavorites",this.product.id).then(()=>{this.$botNotification.notification("Избранное","Успешно удалено из избранного!")})},addToFavorite(){this.product.in_favorite=!0,this.$store.dispatch("addToFavorites",this.product).then(()=>{this.$botNotification.notification("Избранное","Успешно добавлено в избранное!")})},goToProduct(){this.$router.push({name:"product",params:{productId:this.product.id}}),$("#menu-product-info").hideMenu()},incProductCart(){this.checkInCart===0?this.$store.dispatch("addProductToCart",this.product):this.$store.dispatch("incQuantity",this.product.id),this.$botNotification.notification("Добавление товара","Успешно добавлено в корзину!")},decProductCart(){this.checkInCart<=1?this.$store.dispatch("removeProduct",this.product.id):this.$store.dispatch("decQuantity",this.product.id),this.$botNotification.notification("Добавление товара","Товар удален!")}}},Q={id:"menu-product-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"220px",display:"block"},"data-menu-effect":"menu-over"},X={key:0,class:"w-100"},Y={class:"text-center font-700 mt-3 pt-1 px-4"},Z={key:0,class:"row text-center mr-2 ml-2 mb-3"},tt={class:"col-4 mb-n2"},et=t("i",{class:"fa-solid fa-plus font-22"},null,-1),st=[et],ot={class:"col-4 mb-n2 d-flex justify-content-center align-items-center"},at={class:"font-22"},it={class:"col-4 mb-n2"},nt=t("i",{class:"fa-solid fa-minus font-22"},null,-1),lt=[nt],ct={key:1,class:"row text-center mr-2 ml-2 mb-3"},rt={class:"col-12 mb-n2"},dt=t("i",{class:"fa-solid fa-cart-plus font-12"},null,-1),ht={class:"row text-center mr-2 ml-2 mb-3"},ut={class:"col-12 mb-n2"},_t=t("i",{class:"fa-regular fa-heart font-12"},null,-1),ft=t("i",{class:"fa-solid fa-heart font-12"},null,-1);function mt(s,e,u,_,a,o){return i(),n("div",Q,[a.product?(i(),n("div",X,[t("h4",Y,r(a.product.title||"Нет заголовка"),1),o.checkInCart>0?(i(),n("div",Z,[t("div",tt,[t("button",{type:"button",onClick:e[0]||(e[0]=(...l)=>o.incProductCart&&o.incProductCart(...l)),class:"btn p-3 w-100 bg-highlight rounded-s shadow-l"},st)]),t("div",ot,[t("strong",at,r(o.checkInCart),1)]),t("div",it,[t("button",{onClick:e[1]||(e[1]=(...l)=>o.decProductCart&&o.decProductCart(...l)),type:"button",class:"btn p-3 w-100 bg-red1-dark rounded-s shadow-l"},lt)])])):(i(),n("div",ct,[t("div",rt,[t("button",{type:"button",onClick:e[2]||(e[2]=(...l)=>o.incProductCart&&o.incProductCart(...l)),class:"btn p-3 bg-highlight rounded-s shadow-l w-100"},[dt,m(" В корзину "),t("strong",null,r(o.currentPrice)+"₽",1)])])])),t("div",ht,[t("div",ut,[a.product.in_favorite?(i(),n("button",{key:1,type:"button",onClick:e[4]||(e[4]=(...l)=>o.removeFromFavorites&&o.removeFromFavorites(...l)),class:"btn p-3 bg-highlight rounded-s shadow-l w-100"},[ft,m(" Убрать из избранного ")])):(i(),n("button",{key:0,type:"button",onClick:e[3]||(e[3]=(...l)=>o.addToFavorite&&o.addToFavorite(...l)),class:"btn p-3 bg-red2-dark rounded-s shadow-l w-100"},[_t,m(" В избранное ")]))])])])):h("",!0)])}const pt=p(K,[["render",mt]]),gt={data(){return{item:null}},mounted(){window.addEventListener("show-chashback-info",s=>{this.item=s.detail.item||null,this.$nextTick(()=>{$("#cashback-item-info").showMenu()})})},methods:{}},bt={id:"cashback-item-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"50vh",display:"block"},"data-menu-effect":"menu-over"},vt={key:0,class:"w-100"},$t={class:"table table-borderless rounded-sm shadow-l m-0",style:{overflow:"hidden"}},wt=t("thead",null,[t("tr",{class:"bg-gray1-dark"},[t("th",{scope:"col",class:"color-theme"},"Параметр"),t("th",{scope:"col",class:"color-theme"},"Значение")])],-1),kt=t("th",{scope:"row"},"Тип операции",-1),yt={class:"font-weight-bold"},xt=t("th",{scope:"row"},"Сумма CashBack, руб",-1),Ct={class:"font-weight-bold"},Bt={key:0},Tt=t("th",{scope:"row"},"Уровень начисления",-1),Mt={class:"font-weight-bold"},St={key:1},Lt=t("th",{scope:"row"},"Сумма в чеке, руб",-1),Ft={class:"font-weight-bold"},Pt=t("th",{scope:"row"},"Дата операции",-1),zt={class:"font-weight-bold"},Nt=t("th",{scope:"row"},"Описание операции",-1),qt={class:"font-weight-bold"},At=t("th",{scope:"row"},"TG id сотрудника",-1),Et={class:"font-weight-bold"},jt=t("th",{scope:"row"},"Имя сотрудника",-1),Ut={class:"font-weight-bold"},Ot=t("th",{scope:"row"},"Телефон сотрудника",-1),Dt={class:"font-weight-bold"};function It(s,e,u,_,a,o){return i(),n("div",bt,[a.item?(i(),n("div",vt,[t("table",$t,[wt,t("tbody",null,[t("tr",null,[kt,t("td",yt,r(a.item.operation_type?"Начисление":"Списание"),1)]),t("tr",null,[xt,t("td",Ct,r(a.item.amount||0),1)]),a.item.operation_type?(i(),n("tr",Bt,[Tt,t("td",Mt,r(a.item.level||0),1)])):h("",!0),a.item.operation_type?(i(),n("tr",St,[Lt,t("td",Ft,r(a.item.money_in_check||0),1)])):h("",!0),t("tr",null,[Pt,t("td",zt,r(s.$filters.current(a.item.created_at)),1)]),t("tr",null,[Nt,t("td",qt,r(a.item.description||"Нет описания"),1)]),t("tr",null,[At,t("td",Et,r(a.item.employee.telegram_chat_id||"Не указано"),1)]),t("tr",null,[jt,t("td",Ut,r(a.item.employee.fio_from_telegram||"Не указано"),1)]),t("tr",null,[Ot,t("td",Dt,r(a.item.employee.phone||"Не указано"),1)])])])])):h("",!0)])}const Vt=p(gt,[["render",It]]),Gt={data(){return{params:{win:"Номер выигрыша",name:"Ф.И.О.",phone:"Телефон",answered_by:"Кто проверил",answered_at:"Дата ответа"},item:null,eventCallbackForm:{info:null},loading:!1}},mounted(){window.addEventListener("show-event-info",s=>{this.item=s.detail.item||null,this.$nextTick(()=>{$("#event-item-info").showMenu()})})},methods:{sendApproveToUser(){this.loading=!0,this.$store.dispatch("sendApproveToUser",{dataObject:{user_telegram_chat_id:this.item.bot_user.telegram_chat_id,action_id:this.item.id,...this.eventCallbackForm}}).then(s=>{this.loading=!1,this.eventCallbackForm.info=null,$("#event-item-info").hideMenu(),this.$botNotification.success("Отлично!","Оповещение успешно отправлено")}).catch(()=>{this.loading=!1,$("#event-item-info").hideMenu(),this.$botNotification.warning("Упс!","Что-то пошло не так")})}}},Ht={id:"event-item-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"50vh",display:"block"},"data-menu-effect":"menu-over"},Wt={key:0,class:"w-100 p-3"},Jt=t("h4",null,"Информирование пользователя",-1),Rt={class:"mb-0"},Kt={class:"mb-3"},Qt=t("label",{for:"bill-info",class:"form-label"},"Сообщение для пользователя",-1),Xt={class:"mb-3"},Yt=["disabled"];function Zt(s,e,u,_,a,o){return i(),n("div",Ht,[a.item?(i(),n("div",Wt,[Jt,t("ul",null,[t("li",null," № события: "+r(a.item.slug.id),1),t("li",null," Название события: "+r(a.item.slug.command),1),t("li",null," Использовано попыток: "+r(a.item.current_attempts),1),t("li",null," Дата прохождения: "+r(s.$filters.current(a.item.completed_at)),1),(i(!0),n(w,null,x(a.item.data,l=>(i(),n("li",null,[(i(!0),n(w,null,x(Object.keys(l),f=>(i(),n("p",Rt,r(a.params[f])+":"+r(l[f]||"Не установлено"),1))),256))]))),256))]),t("form",{onSubmit:e[1]||(e[1]=B((...l)=>o.sendApproveToUser&&o.sendApproveToUser(...l),["prevent"]))},[t("div",Kt,[Qt,b(t("textarea",{class:"form-control",placeholder:"Информация","onUpdate:modelValue":e[0]||(e[0]=l=>a.eventCallbackForm.info=l),id:"bill-info",rows:"3",required:""},null,512),[[C,a.eventCallbackForm.info]])]),t("div",Xt,[t("button",{disabled:a.loading,type:"submit",class:"btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100"}," Отправить ",8,Yt)])],32)])):h("",!0)])}const te=p(Gt,[["render",Zt]]),ee={computed:{self(){return window.self},tg(){return window.Telegram.WebApp},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id)}},methods:{open(s){this.tg.openLink(s)},copy(){var s=$("<input>");$("body").append(s),s.val(this.link).select(),document.execCommand("copy"),s.remove()}}},se={id:"menu-share",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"420px"}},oe=t("h1",{class:"text-center font-700 font-26 mt-3 pt-2"},"Поделиться контактами",-1),ae=t("p",{class:"boxed-text-xl under-heading m-0 p-0"}," Делитесь ссылкой с друзьями ",-1),ie=t("div",{class:"divider divider-margins"},null,-1),ne={class:"row text-center mr-4 ml-4 mb-0"},le={class:"col-3 mb-n2"},ce=t("i",{class:"fab fa-facebook-f font-22"},null,-1),re=t("br",null,null,-1),de=[ce,re],he=t("p",{class:"font-11 opacity-70"},"Facebook",-1),ue={class:"col-3 mb-n2"},_e=t("i",{class:"fab fa-twitter font-22"},null,-1),fe=t("br",null,null,-1),me=[_e,fe],pe=t("p",{class:"font-11 opacity-70"},"Twitter",-1),ge={class:"col-3 mb-n2"},be=t("i",{class:"fa-brands fa-vk font-22"},null,-1),ve=t("br",null,null,-1),$e=[be,ve],we=t("p",{class:"font-11 opacity-70"},"VK",-1),ke={class:"col-3 mb-n2"},ye=t("i",{class:"fa fa-envelope font-22"},null,-1),xe=t("br",null,null,-1),Ce=[ye,xe],Be=t("p",{class:"font-11 opacity-70"},"Email",-1),Te={class:"col-3 mb-n2"},Me=t("i",{class:"fab fa-whatsapp font-22"},null,-1),Se=t("br",null,null,-1),Le=[Me,Se],Fe=t("p",{class:"font-11 opacity-70"},"WhatsApp",-1),Pe={class:"col-3 mb-n2"},ze=t("i",{class:"fa fa-link font-22"},null,-1),Ne=t("br",null,null,-1),qe=[ze,Ne],Ae=t("p",{class:"font-11 opacity-70"},"Копировать",-1),Ee={class:"col-3 mb-n2"},je=t("i",{class:"fab fa-pinterest-p font-22"},null,-1),Ue=t("br",null,null,-1),Oe=[je,Ue],De=t("p",{class:"font-11 opacity-70"},"Pinterest",-1),Ie=t("div",{class:"col-3 mb-n2"},[t("a",{href:"#",class:"close-menu icon icon-l bg-red2-dark rounded-s shadow-l text-white"},[t("i",{class:"fa fa-times font-22"}),t("br")]),t("p",{class:"font-11 opacity-70"},"Закрыть")],-1),Ve=t("div",{class:"divider divider-margins mt-n1 mb-3"},null,-1),Ge={class:"w-100 p-3 object-fit-cover my-0",alt:""},He=t("div",{class:"divider divider-margins mt-n1 mb-3"},null,-1),We=t("p",{class:"footer-copyright font-10 text-center pb-3 mb-1"},[m("© CashMan "),t("span",{id:"copyright-year"},"2023"),m(". Все Права защищены.")],-1);function Je(s,e,u,_,a,o){const l=k("lazy");return i(),n("div",se,[oe,ae,ie,t("div",ne,[t("div",le,[t("a",{href:"#",onClick:e[0]||(e[0]=f=>o.open("https://www.facebook.com/sharer/sharer.php?u="+o.link)),class:"icon icon-l bg-facebook rounded-s shadow-l text-white"},de),he]),t("div",ue,[t("a",{href:"#",onClick:e[1]||(e[1]=f=>o.open("https://twitter.com/home?status="+o.link)),class:"icon icon-l bg-twitter rounded-s shadow-l text-white"},me),pe]),t("div",ge,[t("a",{href:"#",onClick:e[2]||(e[2]=f=>o.open("https://vk.com/share.php?url="+o.link)),class:"icon icon-l bg-linkedin rounded-s shadow-l text-white"},$e),we]),t("div",ke,[t("a",{href:"#",onClick:e[3]||(e[3]=f=>o.open("mailto:?body="+o.link)),class:"icon icon-l bg-mail rounded-s shadow-l text-white"},Ce),Be]),t("div",Te,[t("a",{href:"#",onClick:e[4]||(e[4]=f=>o.open("whatsapp://send?text="+o.link)),class:"icon icon-l bg-whatsapp rounded-s shadow-l text-white"},Le),Fe]),t("div",Pe,[t("a",{href:"#",onClick:e[5]||(e[5]=(...f)=>o.copy&&o.copy(...f)),class:"shareToCopyLink icon icon-l bg-blue2-dark rounded-s shadow-l text-white"},qe),Ae]),t("div",Ee,[t("a",{href:"#",onClick:e[6]||(e[6]=f=>o.open("https://pinterest.com/pin/create/button/?url="+o.link)),class:"icon icon-l bg-pinterest rounded-s shadow-l text-white"},Oe),De]),Ie]),Ve,b(t("img",Ge,null,512),[[l,o.qr]]),He,We])}const Re=p(ee,[["render",Je]]),Ke={},Qe={id:"menu-highlights",class:"menu menu-box-bottom menu-box-detached rounded-m",style:{display:"block",height:"510px"}},Xe=g('<div class="card header-card shape-rounded h-40"><div class="card-overlay bg-highlight opacity-95"></div><div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div></div><h1 class="text-center mt-4 font-35 font-800">CashMan</h1><p class="text-center font-12 mt-n1 pb-2 mb-3">Добавь красок в свою жизнь</p><div class="card card-style"><div class="highlight-changer pt-3 pb-2"><p class="text-center px-3"> Доступные цветовые схемы для использования в приложении. Нажми на кружочек и цветовая схема автоматически будет применена. </p><a href="#" data-change-highlight="blue2"><i class="fa fa-circle color-blue2-dark"></i><span class="color-blue2-light">Default</span></a><a href="#" data-change-highlight="red2"><i class="fa fa-circle color-red1-dark"></i><span class="color-red2-light">Red</span></a><a href="#" data-change-highlight="orange"><i class="fa fa-circle color-orange-dark"></i><span class="color-orange-light">Orange</span></a><a href="#" data-change-highlight="pink2"><i class="fa fa-circle color-pink2-dark"></i><span class="color-pink2-light">Pink</span></a><a href="#" data-change-highlight="magenta2"><i class="fa fa-circle color-magenta2-dark"></i><span class="color-magenta2-light">Purple</span></a><a href="#" data-change-highlight="aqua"><i class="fa fa-circle color-aqua-dark"></i><span class="color-aqua-light">Aqua</span></a><a href="#" data-change-highlight="teal"><i class="fa fa-circle color-teal-dark"></i><span class="color-teal-light">Teal</span></a><a href="#" data-change-highlight="mint"><i class="fa fa-circle color-mint-dark"></i><span class="color-mint-light">Mint</span></a><a href="#" data-change-highlight="green2"><i class="fa fa-circle color-green2-dark"></i><span class="color-green2-light">Green</span></a><a href="#" data-change-highlight="green1"><i class="fa fa-circle color-green1-dark"></i><span class="color-green1-light">Grass</span></a><a href="#" data-change-highlight="yellow2"><i class="fa fa-circle color-yellow2-dark"></i><span class="color-yellow2-light">Sunny</span></a><a href="#" data-change-highlight="yellow1"><i class="fa fa-circle color-yellow1-dark"></i><span class="color-yellow1-light">Goldish</span></a><a href="#" data-change-highlight="brown1"><i class="fa fa-circle color-brown1-dark"></i><span class="color-brown1-light">Wood</span></a><a href="#" data-change-highlight="dark1"><i class="fa fa-circle color-dark1-dark"></i><span class="color-dark1-light">Night</span></a><a href="#" data-change-highlight="dark2"><i class="fa fa-circle color-dark2-dark"></i><span class="color-dark2-light">Dark</span></a><div class="clearfix"></div></div></div><a href="#" class="close-menu btn btn-full btn-margins rounded-sm shadow-l bg-highlight btn-m font-900 text-uppercase mb-0">Закрыть смену цвета</a>',5),Ye=[Xe];function Ze(s,e,u,_,a,o){return i(),n("div",Qe,Ye)}const ts=p(Ke,[["render",Ze]]),es={data(){return{load:!1}},mounted(){window.addEventListener("preloader-toggle",s=>{this.load=!this.load}),window.addEventListener("preloader-hide",s=>{this.load=!1}),window.addEventListener("preloader-show",s=>{this.load=!0,setTimeout(()=>{this.load=!1},1e3)})}},ss={key:0,id:"preloader"},os=t("div",{class:"spinner-border color-highlight",role:"status"},null,-1),as=[os];function is(s,e,u,_,a,o){return a.load?(i(),n("div",ss,as)):h("",!0)}const ns=p(es,[["render",is]]),ls={data(){return{isActive:!1}},computed:{...v(["cartTotalCount","favoritesCount"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},currentBot(){return window.currentBot}},mounted(){}},cs={id:"menu-main",class:"menu menu-box-right menu-box-detached rounded-m",style:{width:"260px",display:"block",height:"100vh","overflow-y":"scroll"}},rs={class:"menu-header"},ds=g('<a href="#" data-toggle-theme="" class="border-right-0"><i class="fa font-12 color-yellow1-dark fa-lightbulb"></i></a><a href="#" data-menu="menu-highlights" class="border-right-0"><i class="fa font-12 color-green1-dark fa-brush"></i></a><a href="#" data-menu="menu-share" class="border-right-0"><i class="fa font-12 color-red2-dark fa-share-alt"></i></a>',3),hs=t("i",{class:"fa color-blue2-dark fa-cog font-12"},null,-1),us=t("a",{class:"border-right-0 close-menu"},[t("i",{class:"fa font-12 color-red2-dark fa-times"})],-1),_s={class:"menu-logo text-center"},fs={href:"#"},ms={class:"rounded-circle bg-highlight",width:"80"},ps={class:"p-3 font-800 font-24 text-uppercase"},gs={class:"font-11 mt-n2"},bs=t("i",{class:"fa-solid fa-location-dot mr-2 text-danger"},null,-1),vs={class:"menu-items"},$s=t("h5",{class:"text-uppercase opacity-20 font-12 pl-3"},"Главное меню",-1),ws=t("i",{class:"fa-solid fa-house-chimney",style:{color:"lightblue"}},null,-1),ks=t("span",null,"Главная",-1),ys=t("i",{class:"fa fa-circle"},null,-1),xs=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),Cs=t("span",null,"Корзина",-1),Bs={key:0,class:"badge bg-highlight color-white"},Ts=t("i",{class:"fa fa-circle"},null,-1),Ms=t("i",{class:"fa-solid fa-pizza-slice",style:{color:"orangered"}},null,-1),Ss=t("span",null,"Продукция",-1),Ls=t("i",{class:"fa fa-circle"},null,-1),Fs=t("i",{class:"fa-solid fa-star",style:{color:"orange"}},null,-1),Ps=t("span",null,"Избранное",-1),zs={key:0,class:"badge bg-highlight color-white"},Ns=t("i",{class:"fa fa-circle"},null,-1),qs=t("i",{class:"fa-regular fa-clock",style:{color:"#842029"}},null,-1),As=t("span",null,"История заказов",-1),Es=t("strong",{class:"badge bg-highlight color-white"},"3",-1),js=t("i",{class:"fa fa-circle"},null,-1),Us=t("i",{class:"fa-solid fa-people-group",style:{color:"#6f42c1"}},null,-1),Os=t("span",null,"Наша команда",-1),Ds=t("i",{class:"fa fa-circle"},null,-1),Is=t("i",{class:"fa-solid fa-star",style:{color:"orange"}},null,-1),Vs=t("span",null,"О нас",-1),Gs=t("i",{class:"fa fa-circle"},null,-1),Hs=t("i",{class:"fa-regular fa-circle-question",style:{color:"green"}},null,-1),Ws=t("span",null,"Правила сервиса",-1),Js=t("i",{class:"fa fa-circle"},null,-1),Rs={href:"#",class:"close-menu"},Ks={xmlns:"http://www.w3.org/2000/svg",width:"16",height:"16",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":"3","stroke-linecap":"round","stroke-linejoin":"round",class:"feather feather-x","data-feather-line":"3","data-feather-size":"16","data-feather-color":"red2-dark","data-feather-bg":"red2-fade-dark",style:{width:"16px",height:"16px"}},Qs=t("line",{x1:"18",y1:"6",x2:"6",y2:"18"},null,-1),Xs=t("line",{x1:"6",y1:"6",x2:"18",y2:"18"},null,-1),Ys=[Qs,Xs],Zs=t("span",null,"Закрыть",-1),to=t("i",{class:"fa fa-circle"},null,-1),eo=g('<div class="text-center pt-2"><a href="#" class="icon icon-xs mr-1 rounded-s bg-facebook"><i class="fab fa-facebook"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-twitter"><i class="fab fa-twitter"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-instagram"><i class="fab fa-instagram"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-linkedin"><i class="fab fa-linkedin-in"></i></a><a href="#" class="icon icon-xs rounded-s bg-whatsapp"><i class="fab fa-whatsapp"></i></a><p class="mb-0 pt-3 font-10 opacity-30">Copyright <span class="copyright-year"></span> Enabled. All rights reserved</p></div>',1);function so(s,e,u,_,a,o){const l=y("router-link"),f=k("lazy");return i(),n("div",cs,[t("div",rs,[ds,c(l,{class:"close-menu",tag:"a",to:"/settings"},{default:d(()=>[hs]),_:1}),us]),t("div",_s,[t("a",fs,[b(t("img",ms,null,512),[[f,o.logo]])]),t("h1",ps,r(o.currentBot.company.title||o.currentBot.bot_domain||"CashMan:Shopify"),1),t("p",gs,[bs,m(r(o.currentBot.company.address||"Без описания"),1)])]),t("div",vs,[$s,c(l,{id:"nav-home","active-class":"nav-item-active",tag:"a",to:"/home"},{default:d(()=>[ws,ks,ys]),_:1}),c(l,{id:"nav-basket","active-class":"nav-item-active",tag:"a",to:"/basket"},{default:d(()=>[xs,Cs,s.cartTotalCount>0?(i(),n("strong",Bs,r(s.cartTotalCount),1)):h("",!0),Ts]),_:1}),c(l,{id:"nav-products","active-class":"nav-item-active",tag:"a",to:"/products"},{default:d(()=>[Ms,Ss,Ls]),_:1}),c(l,{id:"nav-favorites","active-class":"nav-item-active",tag:"a",to:"/favorites"},{default:d(()=>[Fs,Ps,s.favoritesCount>0?(i(),n("strong",zs,r(s.favoritesCount),1)):h("",!0),Ns]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/history"},{default:d(()=>[qs,As,Es,js]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/our-team"},{default:d(()=>[Us,Os,Ds]),_:1}),c(l,{id:"nav-contact-us","active-class":"nav-item-active",tag:"a",to:"/contact-us"},{default:d(()=>[Is,Vs,Gs]),_:1}),c(l,{id:"nav-terms","active-class":"nav-item-active",tag:"a",to:"/terms"},{default:d(()=>[Hs,Ws,Js]),_:1}),t("a",Rs,[(i(),n("svg",Ks,Ys)),Zs,to])]),eo])}const oo=p(ls,[["render",so]]),ao={data(){return{isActive:!1}},computed:{...v(["cartTotalCount","favoritesCount"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},currentBot(){return window.currentBot}},mounted(){}},io={id:"menu-admin-main",class:"menu menu-box-right menu-box-detached rounded-m",style:{width:"260px",display:"block",height:"100vh","overflow-y":"scroll"}},no={class:"menu-header"},lo=g('<a href="#" data-toggle-theme="" class="border-right-0"><i class="fa font-12 color-yellow1-dark fa-lightbulb"></i></a><a href="#" data-menu="menu-highlights" class="border-right-0"><i class="fa font-12 color-green1-dark fa-brush"></i></a><a href="#" data-menu="menu-share" class="border-right-0"><i class="fa font-12 color-red2-dark fa-share-alt"></i></a>',3),co=t("i",{class:"fa color-blue2-dark fa-cog font-12"},null,-1),ro=t("a",{class:"border-right-0 close-menu"},[t("i",{class:"fa font-12 color-red2-dark fa-times"})],-1),ho={class:"menu-logo text-center"},uo={href:"#"},_o={class:"rounded-circle bg-highlight",width:"80"},fo=t("h1",{class:"p-3 font-800 font-24 text-uppercase"},"Админ.панель",-1),mo={class:"font-11 mt-n2"},po={class:"menu-items"},go=t("h5",{class:"text-uppercase opacity-20 font-12 pl-3"},"Главное меню",-1),bo=t("i",{class:"fa-solid fa-house-chimney",style:{color:"lightblue"}},null,-1),vo=t("span",null,"Главная",-1),$o=t("i",{class:"fa fa-circle"},null,-1),wo=t("i",{class:"fa-solid fa-briefcase",style:{color:"saddlebrown"}},null,-1),ko=t("span",null,"Рабочий день",-1),yo=t("i",{class:"fa fa-circle"},null,-1),xo=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),Co=t("span",null,"Менеджер магазина",-1),Bo=t("i",{class:"fa fa-circle"},null,-1),To=t("i",{class:"fa-solid fa-ranking-star",style:{color:"orangered"}},null,-1),Mo=t("span",null,"Статистика",-1),So=t("i",{class:"fa fa-circle"},null,-1),Lo=t("i",{class:"fa-brands fa-adversal",style:{color:"orange"}},null,-1),Fo=t("span",null,"Реклама",-1),Po=t("i",{class:"fa fa-circle"},null,-1),zo=t("i",{class:"fa-regular fa-clock",style:{color:"#842029"}},null,-1),No=t("span",null,"История заказов",-1),qo=t("strong",{class:"badge bg-highlight color-white"},"3",-1),Ao=t("i",{class:"fa fa-circle"},null,-1),Eo=t("i",{class:"fa-solid fa-bolt",style:{color:"#6f42c1"}},null,-1),jo=t("span",null,"События",-1),Uo=t("i",{class:"fa fa-circle"},null,-1),Oo=t("i",{class:"fa-regular fa-building",style:{color:"#ff5454"}},null,-1),Do=t("span",null,"Настройка компании",-1),Io=t("i",{class:"fa fa-circle"},null,-1),Vo=t("i",{class:"fa-solid fa-robot",style:{color:"#00ff38"}},null,-1),Go=t("span",null,"Настройка бота",-1),Ho=t("i",{class:"fa fa-circle"},null,-1),Wo={href:"#",class:"close-menu"},Jo={xmlns:"http://www.w3.org/2000/svg",width:"16",height:"16",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":"3","stroke-linecap":"round","stroke-linejoin":"round",class:"feather feather-x","data-feather-line":"3","data-feather-size":"16","data-feather-color":"red2-dark","data-feather-bg":"red2-fade-dark",style:{width:"16px",height:"16px"}},Ro=t("line",{x1:"18",y1:"6",x2:"6",y2:"18"},null,-1),Ko=t("line",{x1:"6",y1:"6",x2:"18",y2:"18"},null,-1),Qo=[Ro,Ko],Xo=t("span",null,"Закрыть",-1),Yo=t("i",{class:"fa fa-circle"},null,-1),Zo=g('<div class="text-center pt-2"><a href="#" class="icon icon-xs mr-1 rounded-s bg-facebook"><i class="fab fa-facebook"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-twitter"><i class="fab fa-twitter"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-instagram"><i class="fab fa-instagram"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-linkedin"><i class="fab fa-linkedin-in"></i></a><a href="#" class="icon icon-xs rounded-s bg-whatsapp"><i class="fab fa-whatsapp"></i></a><p class="mb-0 pt-3 font-10 opacity-30">Copyright <span class="copyright-year"></span> Enabled. All rights reserved</p></div>',1);function ta(s,e,u,_,a,o){const l=y("router-link"),f=k("lazy");return i(),n("div",io,[t("div",no,[lo,c(l,{class:"close-menu",tag:"a",to:"/settings"},{default:d(()=>[co]),_:1}),ro]),t("div",ho,[t("a",uo,[b(t("img",_o,null,512),[[f,o.logo]])]),fo,t("p",mo,r(o.currentBot.company.title||o.currentBot.bot_domain||"CashMan:Shopify"),1)]),t("div",po,[go,c(l,{id:"nav-home","active-class":"nav-item-active",tag:"a",to:"/admin-main"},{default:d(()=>[bo,vo,$o]),_:1}),c(l,{id:"nav-home","active-class":"nav-item-active",tag:"a",to:"/admin-work-status"},{default:d(()=>[wo,ko,yo]),_:1}),c(l,{id:"nav-basket","active-class":"nav-item-active",tag:"a",to:"/admin-shop-manager"},{default:d(()=>[xo,Co,Bo]),_:1}),c(l,{id:"nav-products","active-class":"nav-item-active",tag:"a",to:"/admin-statistic"},{default:d(()=>[To,Mo,So]),_:1}),c(l,{id:"nav-favorites","active-class":"nav-item-active",tag:"a",to:"/admin-promotion"},{default:d(()=>[Lo,Fo,Po]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/history"},{default:d(()=>[zo,No,qo,Ao]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/admin-actions"},{default:d(()=>[Eo,jo,Uo]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/admin-company-config"},{default:d(()=>[Oo,Do,Io]),_:1}),c(l,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/admin-bot-config"},{default:d(()=>[Vo,Go,Ho]),_:1}),t("a",Wo,[(i(),n("svg",Jo,Qo)),Xo,Yo])]),Zo])}const ea=p(ao,[["render",ta]]);const sa=t("title",null,"CashMan - система твоего бизнеса внутри",-1),oa=t("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),aa={id:"page"},ia={class:"header header-fixed header-auto-show header-logo-app"},na=t("i",{class:"fas fa-arrow-left"},null,-1),la=[na],ca=g('<a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a><a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>',3),ra={key:0,href:"#","data-menu":"menu-main",class:"header-icon header-icon-4"},da=t("i",{class:"fas fa-bars"},null,-1),ha=[da],ua={key:0,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},_a=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),fa=t("span",{class:"color-white"},"Корзина",-1),ma={key:0,class:"badge bg-green1-dark"},pa={key:1},ga=t("i",{class:"fa-brands fa-shopify"},null,-1),ba=t("span",{class:"color-white"},"Продукты",-1),va={key:0},$a=t("i",{class:"fa fa-home"},null,-1),wa=t("span",{class:"color-white"},"Домой",-1),ka={key:0},ya=t("i",{class:"fa fa-heart"},null,-1),xa=t("span",{class:"color-white"},"Избранное",-1),Ca={key:0,class:"badge bg-green1-dark"},Ba={key:1},Ta=t("a",{href:"#","data-menu":"menu-main"},[t("i",{class:"fa-solid fa-bars"}),t("span",{class:"color-white"},"Меню")],-1),Ma={key:1,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},Sa=t("i",{class:"fa-solid fa-bolt"},null,-1),La=t("span",{class:"color-white"},"События",-1),Fa={key:0},Pa=t("i",{class:"fa-solid fa-ranking-star"},null,-1),za=t("span",{class:"color-white"},"Статистика",-1),Na={key:0},qa=t("i",{class:"fa fa-home"},null,-1),Aa=t("span",{class:"color-white"},"Главная",-1),Ea={key:0},ja=t("i",{class:"fa-solid fa-briefcase"},null,-1),Ua=t("span",{class:"color-white"},"Работа",-1),Oa={key:0},Da=t("a",{href:"#","data-menu":"menu-admin-main"},[t("i",{class:"fa-solid fa-bars"}),t("span",{class:"color-white"},"Меню")],-1),Ia={watch:{$route(s){this.$preloader.show(),F.handler(),this.$nextTick(()=>{document.body.scrollTop=document.documentElement.scrollTop=0})}},computed:{...v(["cartTotalCount","favoritesCount"]),prefix(){return window.currentPath||""},tg(){return window.Telegram.WebApp},tgUser(){const s=new URLSearchParams(this.tg.initData);return JSON.parse(s.get("user"))}},mounted(){},methods:{openLink(s){this.tg.openLink(s,{try_instant_view:!0})},closeShop(){this.tg.close()}}},Va=Object.assign(Ia,{__name:"ShopLayout",setup(s){return(e,u)=>{const _=y("router-link");return i(),n(w,null,[c(T(M),null,{default:d(()=>[sa,oa]),_:1}),c(ns),c(pt),c(Vt),c(te),c(R),t("div",aa,[t("div",ia,[t("a",{onClick:u[0]||(u[0]=(...a)=>e.closeShop&&e.closeShop(...a)),class:"header-title header-subtitle"},"Вернуться в бота"),t("a",{onClick:u[1]||(u[1]=a=>e.$router.back()),"data-back-button":"",class:"header-icon header-icon-1"},la),ca,e.$route.meta.hide_menu?h("",!0):(i(),n("a",ra,ha))]),e.$route.meta.hide_menu?h("",!0):(i(),n("div",ua,[c(_,{"active-class":"active-nav",tag:"a",to:"/basket"},{default:d(()=>[_a,fa,e.cartTotalCount>0?(i(),n("em",ma,r(e.cartTotalCount),1)):h("",!0),e.$route.path=="/basket"?(i(),n("strong",pa)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/products"},{default:d(()=>[ga,ba,e.$route.path=="/products"?(i(),n("strong",va)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/home"},{default:d(()=>[$a,wa,e.$route.path=="/home"?(i(),n("strong",ka)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/favorites"},{default:d(()=>[ya,xa,e.favoritesCount>0?(i(),n("em",Ca,r(e.favoritesCount),1)):h("",!0),e.$route.path=="/favorites"?(i(),n("strong",Ba)):h("",!0)]),_:1}),Ta])),e.$route.meta.need_admin_menu?(i(),n("div",Ma,[c(_,{"active-class":"active-nav",tag:"a",to:"/admin-actions"},{default:d(()=>[Sa,La,e.$route.path=="/admin-actions"?(i(),n("strong",Fa)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/admin-statistic"},{default:d(()=>[Pa,za,e.$route.path=="/admin-statistic"?(i(),n("strong",Na)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/admin-main"},{default:d(()=>[qa,Aa,e.$route.path=="/admin-main"?(i(),n("strong",Ea)):h("",!0)]),_:1}),c(_,{"active-class":"active-nav",tag:"a",to:"/admin-work-status"},{default:d(()=>[ja,Ua,e.$route.path=="/admin-work-status"?(i(),n("strong",Oa)):h("",!0)]),_:1}),Da])):h("",!0),S(e.$slots,"default"),c(Re),c(ts),c(oo),c(ea)])],64)}}});const Ga={key:0,class:"page-content",style:{"min-height":"667px"}},Ha={class:"page-title page-title-small"},Wa=t("i",{class:"fa fa-arrow-left"},null,-1),Ja=["href"],Ra={style:{width:"50px","object-fit":"cover","border-radius":"50%"},alt:""},Ka=t("div",{class:"card header-card shape-rounded",style:{height:"115px"}},[t("div",{class:"card-overlay bg-highlight opacity-95"}),t("div",{class:"card-overlay dark-mode-tint"}),t("div",{class:"card-bg preload-img","data-src":"/shop/images/pictures/20s.jpg"})],-1),Qa={class:"footer"},Xa={class:"card card-style mb-0"},Ya={href:"#",class:"footer-title p-4"},Za=t("p",{class:"text-center font-12 mt-n1 mb-3 opacity-70"},[m(" Добавь "),t("span",{class:"color-highlight"},"красок"),m(" в свою жизнь ")],-1),ti={class:"boxed-text-l"},ei={class:"text-center mb-3"},si=t("i",{class:"fa-solid fa-at"},null,-1),oi=[si],ai=t("i",{class:"fa-brands fa-vk"},null,-1),ii=[ai],ni=t("i",{class:"fa fa-phone"},null,-1),li=[ni],ci=t("a",{href:"#","data-menu":"menu-share",class:"icon icon-xs rounded-sm mr-1 shadow-l bg-red2-dark text-white"},[t("i",{class:"fa fa-share-alt"})],-1),ri=t("a",{href:"#",class:"back-to-top icon icon-xs rounded-sm shadow-l bg-highlight text-white"},[t("i",{class:"fa fa-arrow-up"})],-1),di=t("p",{class:"footer-copyright pb-3 mb-1"},[m("© CashMan "),t("span",{id:"copyright-year"},"2023"),m(". Все Права защищены.")],-1),hi=t("div",{class:"footer-card card shape-rounded","data-card-height":"230",style:{height:"230px"}},[t("div",{class:"card-overlay bg-highlight opacity-95"}),t("div",{class:"card-bg preload-img","data-src":"/shop/images/pictures/20s.jpg"})],-1),ui={computed:{...v(["getSelf"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},self(){return window.self||null},tg(){return window.Telegram.WebApp},tgUser(){const s=new URLSearchParams(this.tg.initData);return JSON.parse(s.get("user"))},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id)}},created(){window.currentBot=this.bot.data,window.currentScript=this.slug_id||null;let s=this.tgUser||null;this.$store.dispatch("loadSelf",{dataObject:{telegram_chat_id:s?s.id:484698703,bot_id:window.currentBot.id}}).then(()=>{window.self=this.getSelf}),this.$notify({type:"success",text:"The operation completed"})},methods:{open(s){this.tg.openLink(s)}}},vi=Object.assign(ui,{__name:"Main",props:{bot:{type:Object},slug_id:{type:String}},setup(s){return(e,u)=>{const _=y("router-view"),a=k("lazy");return i(),L(Va,null,{default:d(()=>[e.self?(i(),n("div",Ga,[t("div",Ha,[t("h2",null,[t("a",{onClick:u[0]||(u[0]=o=>e.$router.back())},[Wa,m(" "+r(e.$route.meta.title||"Меню"),1)])]),t("a",{href:e.$route.meta.hide_menu?"#":"#/contact-us",class:"bg-fade-gray1-dark shadow-xl d-flex justify-content-center align-items-center font-18 bot-avatar"},[b(t("img",Ra,null,512),[[a,e.logo]])],8,Ja)]),Ka,c(_,{bot:s.bot},null,8,["bot"]),t("div",Qa,[t("div",Xa,[t("a",Ya,r(e.currentBot.company.title||"CashMan:Shopify"),1),Za,t("p",ti,r(e.currentBot.company.description||"Описание вашего магазина"),1),t("div",ei,[e.currentBot.company.email?(i(),n("a",{key:0,href:"#",onClick:u[1]||(u[1]=o=>e.open("mailTo:"+e.currentBot.company.email)),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-facebook text-white"},oi)):h("",!0),e.currentBot.company.links[0]?(i(),n("a",{key:1,href:"#",onClick:u[2]||(u[2]=o=>e.open(e.currentBot.company.links[0])),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-vk text-white"},ii)):h("",!0),e.currentBot.company.phones[0]?(i(),n("a",{key:2,href:"#",onClick:u[3]||(u[3]=o=>e.open("tel:"+e.currentBot.company.phones[0])),class:"icon icon-xs rounded-sm shadow-l mr-1 bg-phone text-white"},li)):h("",!0),ci,ri]),di]),hi])])):h("",!0)]),_:1})}}});export{vi as default};