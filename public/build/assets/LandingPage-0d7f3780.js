import{m as p,o as r,c as d,b as m,l as h,u as g,X as b,y as f,F as x,a as e,h as v,A as y,e as w,d as u,w as c,v as o,f as l,j as k}from"./app-9a48c6e5.js";import{V as _}from"./VisitCardConstructor-f2a2a629.js";import"./_plugin-vue_export-helper-c27b6911.js";const C=e("title",null,"CashMan - система твоего бизнеса внутри",-1),q=e("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),M=v('<nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav"><div class="container px-5"><a class="bg-light px-3 text-gradient fs-3 fw-lighter" href="#page-top"> Next IT </a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"> Menu <i class="bi-list"></i></button><div class="collapse navbar-collapse" id="navbarResponsive"><ul class="navbar-nav ms-auto me-4 my-3 my-lg-0"><li class="nav-item"><a class="nav-link me-lg-3" href="#constructor">Конструктор</a></li><li class="nav-item"><a class="nav-link me-lg-3" href="#prices">Тарифы</a></li><li class="nav-item"><a class="nav-link me-lg-3" href="#employes">Сотрудничество</a></li></ul><button class="btn btn-primary rounded-pill px-3 mb-2 mb-lg-0" data-bs-toggle="modal" data-bs-target="#feedbackModal"><span class="d-flex align-items-center"><i class="bi-chat-text-fill me-2"></i><span class="small">Перезвонить</span></span></button></div></div></nav>',1),B={props:["active"],data(){return{load:!1,bot:null,company:null}},computed:{...p(["getErrors","getCurrentBot","getCurrentCompany"])},watch:{getErrors:function(a,s){Object.keys(a).forEach(t=>{this.$notify({title:"Конструктор ботов",text:a[t],type:"warn"})})}},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",a=>{this.bot=this.getCurrentBot}),window.addEventListener("store_current_company-change-event",a=>{this.company=this.getCurrentCompany})},methods:{loadCurrentCompany(a=null){this.$store.dispatch("updateCurrentCompany",{company:a}).then(()=>{this.company=this.getCurrentCompany})},loadCurrentBot(a=null){this.$store.dispatch("updateCurrentBot",{bot:a}).then(()=>{this.bot=this.getCurrentBot})},resetCompany(){this.$store.dispatch("resetCurrentCompany").then(()=>{this.company=null,window.dispatchEvent(new CustomEvent("store_current_company-change-event"))})},resetBot(){this.$store.dispatch("resetCurrentBot").then(()=>{this.bot=null,window.dispatchEvent(new CustomEvent("store_current_bot-change-event"))})},stopAllDialogs(){this.$store.dispatch("stopDialogs").then(a=>{this.$notify({title:"Конструктор ботов",text:"Все диалоги остановлены",type:"success"})}).catch(a=>{})},reloadWebhooks(){this.load=!0,this.$notify({title:"Конструктор ботов",text:"Процедура обновления зависимостей началась"}),axios.get("/bot/register-webhooks").then(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Зависимости успешно обновлены!",type:"success"})}).catch(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Неудалось обновить зависимости",type:"error"})})}}},E=Object.assign(B,{__name:"ClientLayout",setup(a){return(s,t)=>{const n=y("notifications");return r(),d(x,null,[m(g(b),null,{default:h(()=>[C,q]),_:1}),m(n,{position:"top right"}),M,f(s.$slots,"default")],64)}}}),F=e("header",{class:"masthead"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 align-items-center"},[e("div",{class:"col-lg-6"},[e("div",{class:"mb-5 mb-lg-0 text-center text-lg-start"},[e("h1",{class:"display-1 lh-1 mb-3"},"Showcase your app beautifully."),e("p",{class:"lead fw-normal text-muted mb-5"},"Launch your mobile app landing page faster with this free, open source theme from Start Bootstrap!"),e("div",{class:"d-flex flex-column flex-lg-row align-items-center"},[e("a",{class:"me-lg-3 mb-4 mb-lg-0",href:"#!"},[e("img",{class:"app-badge",src:"/landing/assets/img/google-play-badge.svg",alt:"..."})]),e("a",{href:"#!"},[e("img",{class:"app-badge",src:"/landing/assets/img/app-store-badge.svg",alt:"..."})])])])]),e("div",{class:"col-lg-6"},[e("div",{class:"masthead-device-mockup"},[e("svg",{class:"circle",viewBox:"0 0 100 100",xmlns:"http://www.w3.org/2000/svg"},[e("defs",null,[e("linearGradient",{id:"circleGradient",gradientTransform:"rotate(45)"},[e("stop",{class:"gradient-start-color",offset:"0%"}),e("stop",{class:"gradient-end-color",offset:"100%"})])]),e("circle",{cx:"50",cy:"50",r:"50"})]),e("svg",{class:"shape-1 d-none d-sm-block",viewBox:"0 0 240.83 240.83",xmlns:"http://www.w3.org/2000/svg"},[e("rect",{x:"-32.54",y:"78.39",width:"305.92",height:"84.05",rx:"42.03",transform:"translate(120.42 -49.88) rotate(45)"}),e("rect",{x:"-32.54",y:"78.39",width:"305.92",height:"84.05",rx:"42.03",transform:"translate(-49.88 120.42) rotate(-45)"})]),e("svg",{class:"shape-2 d-none d-sm-block",viewBox:"0 0 100 100",xmlns:"http://www.w3.org/2000/svg"},[e("circle",{cx:"50",cy:"50",r:"50"})]),e("div",{class:"device-wrapper"},[e("div",{class:"device","data-device":"iPhoneX","data-orientation":"portrait","data-color":"black"},[e("div",{class:"screen bg-black"},[e("video",{muted:"muted",autoplay:"",loop:"",style:{"max-width":"100%",height:"100%"}},[e("source",{src:"/landing/assets/img/demo-screen.mp4",type:"video/mp4"})])])])])])])])])],-1),j=e("aside",{class:"text-center bg-gradient-primary-to-secondary"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 justify-content-center"},[e("div",{class:"col-xl-8"},[e("div",{class:"h2 fs-1 text-white mb-4"},'"An intuitive solution to a common problem that we all face, wrapped up in a single app!" '),e("img",{src:"/landing/assets/img/tnw-logo.svg",alt:"...",style:{height:"3rem"}})])])])],-1),$=e("section",{class:"bg-light py-5"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 justify-content-center"},[e("div",{class:"col-xxl-8"},[e("div",{class:"text-center my-5"},[e("h2",{class:"display-5 fw-bolder"},[e("span",{class:"text-gradient d-inline"},"About Me")]),e("p",{class:"lead fw-light mb-4"},"My name is Start Bootstrap and I help brands grow."),e("p",{class:"text-muted"},"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit dolorum itaque qui unde quisquam consequatur autem. Eveniet quasi nobis aliquid cumque officiis sed rem iure ipsa! Praesentium ratione atque dolorem?"),e("div",{class:"d-flex justify-content-center fs-2 gap-4"},[e("a",{class:"text-gradient",href:"#!"},[e("i",{class:"bi bi-twitter"})]),e("a",{class:"text-gradient",href:"#!"},[e("i",{class:"bi bi-linkedin"})]),e("a",{class:"text-gradient",href:"#!"},[e("i",{class:"bi bi-github"})])])])])])])],-1),T=e("section",{class:"py-5"},[e("div",{class:"container px-5 mb-5"},[e("div",{class:"text-center mb-5"},[e("h1",{class:"display-5 fw-bolder mb-0"},[e("span",{class:"text-gradient d-inline"},"Наши кейсы")])]),e("div",{class:"row gx-5 justify-content-center"},[e("div",{class:"col-lg-11 col-xl-9 col-xxl-8"},[e("div",{class:"card overflow-hidden shadow rounded-4 border-0 mb-5"},[e("div",{class:"card-body p-0"},[e("div",{class:"d-flex align-items-center"},[e("div",{class:"p-5"},[e("h2",{class:"fw-bolder"},"Project Name 1"),e("p",null,"Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum illum aperiam placeat esse? Mollitia omnis minima saepe recusandae libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!")]),e("img",{class:"img-fluid",src:"https://dummyimage.com/300x400/343a40/6c757d",alt:"..."})])])]),e("div",{class:"card overflow-hidden shadow rounded-4 border-0"},[e("div",{class:"card-body p-0"},[e("div",{class:"d-flex align-items-center"},[e("div",{class:"p-5"},[e("h2",{class:"fw-bolder"},"Project Name 2"),e("p",null,"Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum illum aperiam placeat esse? Mollitia omnis minima saepe recusandae libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!")]),e("img",{class:"img-fluid",src:"https://dummyimage.com/300x400/343a40/6c757d",alt:"..."})])])])])])])],-1),A={key:0,class:"py-5 bg-gradient-primary-to-secondary text-white"},L={class:"container px-5 my-5"},S={class:"text-center"},P=e("h2",{class:"display-4 fw-bolder mb-4"},"Давайте создадим что-нибудь вместе!",-1),V=e("a",{class:"btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder ml-2","data-bs-toggle":"modal","data-bs-target":"#feedbackModal"},"Позвать помошника",-1),N={key:1,id:"constructor",class:"py-5 bg-gradient-primary-to-secondary text-white"},I={class:"container px-5 my-5"},D={class:"w-100 d-flex justify-content-center mt-5"},R=e("i",{class:"fa-solid fa-arrows-rotate text-white fw-bold",style:{"font-size":"26px"}},null,-1),G=[R],U=e("section",{id:"features"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 align-items-center"},[e("div",{class:"col-lg-8 order-lg-1 mb-5 mb-lg-0"},[e("div",{class:"container-fluid px-5"},[e("div",{class:"row gx-5"},[e("div",{class:"col-md-6 mb-5"},[e("div",{class:"text-center"},[e("i",{class:"bi-phone icon-feature text-gradient d-block mb-3"}),e("h3",{class:"font-alt"},"Device Mockups"),e("p",{class:"text-muted mb-0"},"Ready to use HTML/CSS device mockups, no Photoshop required!")])]),e("div",{class:"col-md-6 mb-5"},[e("div",{class:"text-center"},[e("i",{class:"bi-camera icon-feature text-gradient d-block mb-3"}),e("h3",{class:"font-alt"},"Flexible Use"),e("p",{class:"text-muted mb-0"},"Put an image, video, animation, or anything else in the screen!")])])]),e("div",{class:"row"},[e("div",{class:"col-md-6 mb-5 mb-md-0"},[e("div",{class:"text-center"},[e("i",{class:"bi-gift icon-feature text-gradient d-block mb-3"}),e("h3",{class:"font-alt"},"Free to Use"),e("p",{class:"text-muted mb-0"},"As always, this theme is free to download and use for any purpose!")])]),e("div",{class:"col-md-6"},[e("div",{class:"text-center"},[e("i",{class:"bi-patch-check icon-feature text-gradient d-block mb-3"}),e("h3",{class:"font-alt"},"Open Source"),e("p",{class:"text-muted mb-0"},"Since this theme is MIT licensed, you can use it commercially!")])])])])]),e("div",{class:"col-lg-4 order-lg-0"},[e("div",{class:"features-device-mockup"},[e("svg",{class:"circle",viewBox:"0 0 100 100",xmlns:"http://www.w3.org/2000/svg"},[e("defs",null,[e("linearGradient",{id:"circleGradient",gradientTransform:"rotate(45)"},[e("stop",{class:"gradient-start-color",offset:"0%"}),e("stop",{class:"gradient-end-color",offset:"100%"})])]),e("circle",{cx:"50",cy:"50",r:"50"})]),e("svg",{class:"shape-1 d-none d-sm-block",viewBox:"0 0 240.83 240.83",xmlns:"http://www.w3.org/2000/svg"},[e("rect",{x:"-32.54",y:"78.39",width:"305.92",height:"84.05",rx:"42.03",transform:"translate(120.42 -49.88) rotate(45)"}),e("rect",{x:"-32.54",y:"78.39",width:"305.92",height:"84.05",rx:"42.03",transform:"translate(-49.88 120.42) rotate(-45)"})]),e("svg",{class:"shape-2 d-none d-sm-block",viewBox:"0 0 100 100",xmlns:"http://www.w3.org/2000/svg"},[e("circle",{cx:"50",cy:"50",r:"50"})]),e("div",{class:"device-wrapper"},[e("div",{class:"device","data-device":"iPhoneX","data-orientation":"portrait","data-color":"black"},[e("div",{class:"screen bg-black"},[e("iframe",{src:"http://localhost:8000/web/obedy_go_bot",style:{height:"100%",width:"100%"}})])])])])])])])],-1),O=e("section",{class:"bg-light"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 align-items-center justify-content-center justify-content-lg-between"},[e("div",{class:"col-12 col-lg-5"},[e("h2",{class:"display-4 lh-1 mb-4"},"Enter a new age of web design"),e("p",{class:"lead fw-normal text-muted mb-5 mb-lg-0"},"This section is perfect for featuring some information about your application, why it was built, the problem it solves, or anything else! There's plenty of space for text here, so don't worry about writing too much.")]),e("div",{class:"col-sm-8 col-md-6"},[e("div",{class:"px-5 px-sm-0"},[e("img",{class:"img-fluid rounded-circle",src:"https://source.unsplash.com/u8Jn2rzYIps/900x900",alt:"..."})])])])])],-1),W=e("section",{class:"cta"},[e("div",{class:"cta-content"},[e("div",{class:"container px-5"},[e("h2",{class:"text-white display-1 lh-1 mb-4"},[l(" Stop waiting. "),e("br"),l(" Start building. ")]),e("a",{class:"btn btn-outline-light py-3 px-4 rounded-pill",href:"https://startbootstrap.com/theme/new-age",target:"_blank"},"Download for free")])])],-1),X=e("section",{id:"prices"},[e("div",{class:"container px-5"},[e("div",{class:"row gx-5 justify-content-center"},[e("div",{class:"col-xxl-8"},[e("div",{class:"text-center my-5"},[e("h2",{class:"display-5 fw-bolder"},[e("span",{class:"text-gradient d-inline"},"Наши тарифы")]),e("p",{class:"lead fw-light mb-4"},"My name is Start Bootstrap and I help brands grow."),e("p",{class:"text-muted"},"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit dolorum itaque qui unde quisquam consequatur autem. Eveniet quasi nobis aliquid cumque officiis sed rem iure ipsa! Praesentium ratione atque dolorem?")])])]),e("div",{class:"row"},[e("div",{class:"col"},[e("div",{class:"card bg-gradient-primary-to-secondary shadow text-white mb-5"},[e("div",{class:"card-header pt-4"},[e("h4",{class:"text-center"},"Индивидуальный")]),e("div",{class:"card-body px-3 py-4"},[e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Соберем тариф под Ваши нужды")]),e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Рассылки неограничены")]),e("button",{"data-bs-toggle":"modal","data-bs-target":"#feedbackModal",type:"button",class:"btn btn-outline-light w-100"},"Обсудить тариф ")])]),e("div",{class:"content"},[e("h3",{class:"text-secondary"}," Список услуг"),e("ul",{id:"services",class:"list-group list-group-flush"},[e("li",{class:"list-group-item"},"Online-chat"),e("li",{class:"list-group-item"},"Бот для мессенджеров"),e("li",{class:"list-group-item"},"Бот для Whatsapp"),e("li",{class:"list-group-item"},"Сбор заявок"),e("li",{class:"list-group-item"},"Графический редактор"),e("li",{class:"list-group-item"},"Отправка медиафайлов"),e("li",{class:"list-group-item"},"CRM"),e("li",{class:"list-group-item"},"Перенос клиентской базы"),e("li",{class:"list-group-item"},"Инструменты оператора"),e("li",{class:"list-group-item"},"Статистика"),e("li",{class:"list-group-item"},"Рассылки"),e("li",{class:"list-group-item"},"Bitrix, AmoCRM"),e("li",{class:"list-group-item"},"Работа с API"),e("li",{class:"list-group-item"},"Запись/бронирование"),e("li",{class:"list-group-item"},"Работа с Google таблицами"),e("li",{class:"list-group-item"},"Программируемая логика"),e("li",{class:"list-group-item"},"Высокая нагрузка"),e("li",{class:"list-group-item"},"Оплаты в боте")])])]),e("div",{class:"col"},[e("div",{class:"card mb-3",style:{border:"none","min-height":"290px"}},[e("div",{class:"card-header bg-white pt-4"},[e("h4",{class:"text-center"},"Базовый")]),e("div",{class:"card-body d-flex flex-column justify-content-between"},[e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Многоуровневые автоворонки")]),e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Первый месяц бесплатно")]),e("h2",{class:"gray"},[l("1099 "),e("small",null,"₽ / мес")]),e("button",{"data-bs-toggle":"modal","data-bs-target":"#feedbackModal",class:"btn btn-outline-primary w-100"},"Попробовать ")])]),e("div",{class:"content"},[e("h5",{class:"text-primary text-center"},"Для старта"),e("ul",{class:"list-group list-group-flush"},[e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})])])])]),e("div",{class:"col"},[e("div",{class:"card mb-3",style:{border:"none","min-height":"290px"}},[e("div",{class:"card-header bg-white pt-4"},[e("h4",{class:"text-center"},"Стандартный")]),e("div",{class:"card-body d-flex flex-column justify-content-between"},[e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Автоворонки и рассылки")]),e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Первый месяц бесплатно")]),e("h2",{class:"gray"},[l("1099 "),e("small",null,"₽ / мес")]),e("button",{"data-bs-toggle":"modal","data-bs-target":"#feedbackModal",class:"btn btn-outline-primary w-100"},"Попробовать ")])]),e("div",{class:"content"},[e("h5",{class:"text-primary text-center"},"Для малого бизнеса"),e("ul",{class:"list-group list-group-flush"},[e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})])])])]),e("div",{class:"col"},[e("div",{class:"card mb-3",style:{border:"none","min-height":"290px"}},[e("div",{class:"card-header bg-white pt-4"},[e("h4",{class:"text-center"},"Премиум")]),e("div",{class:"card-body d-flex flex-column justify-content-between"},[e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Полная автоматизация")]),e("p",null,[e("i",{class:"fa-solid fa-check mr-2"}),l(" Первый месяц бесплатно")]),e("h2",{class:"gray"},[l("1099 "),e("small",null,"₽ / мес")]),e("button",{"data-bs-toggle":"modal","data-bs-target":"#feedbackModal",class:"btn btn-outline-primary w-100"},"Попробовать ")])]),e("div",{class:"content"},[e("h5",{class:"text-primary text-center"},"Для компаний"),e("ul",{class:"list-group list-group-flush"},[e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-check mr-2 text-success"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})]),e("li",{class:"have list-group-item text-center",style:{border:"none"}},[e("i",{class:"fa-solid fa-xmark mr-2 text-danger"})])])])])])])],-1),z=e("section",{class:"bg-gradient-primary-to-secondary",id:"download"},[e("div",{class:"container px-5"},[e("h2",{class:"text-center text-white font-alt mb-4"},"Get the app now!"),e("div",{class:"d-flex flex-column flex-lg-row align-items-center justify-content-center"},[e("a",{class:"me-lg-3 mb-4 mb-lg-0",href:"#!"},[e("img",{class:"app-badge",src:"/landing/assets/img/google-play-badge.svg",alt:"..."})]),e("a",{href:"#!"},[e("img",{class:"app-badge",src:"/landing/assets/img/app-store-badge.svg",alt:"..."})])])])],-1),H=e("footer",{class:"bg-black text-center py-5"},[e("div",{class:"container px-5"},[e("div",{class:"text-white-50 small"},[e("div",{class:"mb-2"},"© Your Website 2023. All Rights Reserved."),e("a",{href:"#!"},"Privacy"),e("span",{class:"mx-1"},"·"),e("a",{href:"#!"},"Terms"),e("span",{class:"mx-1"},"·"),e("a",{href:"#!"},"FAQ")])])],-1),Y={class:"modal fade",id:"feedbackModal",tabindex:"-1","aria-labelledby":"feedbackModalLabel","aria-hidden":"true"},J={class:"modal-dialog modal-dialog-centered"},K={class:"modal-content"},Q=e("div",{class:"modal-header bg-gradient-primary-to-secondary p-4"},[e("h5",{class:"modal-title font-alt text-white",id:"feedbackModalLabel"},"Заказать обратную связь"),e("button",{class:"btn-close btn-close-white",type:"button","data-bs-dismiss":"modal","aria-label":"Close"})],-1),Z={class:"modal-body border-0 p-4"},ee={id:"contactForm","data-sb-form-api-token":"API_TOKEN"},se={class:"form-floating mb-3"},te=e("label",{for:"name"},"Ваше Ф.И.О.",-1),ae=e("div",{class:"invalid-feedback","data-sb-feedback":"name:required"},"Является обязательным ",-1),le={class:"form-floating mb-3"},ie=e("label",{for:"email"},"Ваша почта",-1),ce=e("div",{class:"invalid-feedback","data-sb-feedback":"email:required"},"An email is required. ",-1),oe=e("div",{class:"invalid-feedback","data-sb-feedback":"email:email"},"Email is not valid. ",-1),re={class:"form-floating mb-3"},ne=e("label",{for:"phone"},"Номер телефона",-1),de=e("div",{class:"invalid-feedback","data-sb-feedback":"phone:required"},"A phone number is required. ",-1),me={class:"form-floating mb-3"},ue=e("label",{for:"phone"},"Когда вам удобно?",-1),pe=e("div",{class:"invalid-feedback","data-sb-feedback":"phone:required"},"A phone number is required. ",-1),he={class:"form-floating mb-3"},ge=e("label",{for:"message"},"Сообщение менеджеру",-1),be=e("div",{class:"invalid-feedback","data-sb-feedback":"message:required"},"A message is required. ",-1),fe=e("div",{class:"d-none",id:"submitSuccessMessage"},[e("div",{class:"text-center mb-3"},[e("div",{class:"fw-bolder"},"Form submission successful!"),l(" To activate this form, sign up at "),e("br"),e("a",{href:"https://startbootstrap.com/solution/contact-forms"},"https://startbootstrap.com/solution/contact-forms")])],-1),xe=e("div",{class:"d-none",id:"submitErrorMessage"},[e("div",{class:"text-center text-danger mb-3"},"Error sending message!")],-1),ve=e("div",{class:"d-grid"},[e("button",{class:"btn btn-primary rounded-pill btn-lg disabled",id:"submitButton",type:"submit"},"Отправить заявку ")],-1),ye={data(){let a=new Date;return{need_start:!1,feedbackForm:{name:null,email:null,phone:null,time:a.getHours()+":"+a.getMinutes(),message:"Добрый день! Заинтересовала данная система, хочу запросить перезвон менеджера для обсуждения деталей!"}}},computed:{...p(["getCurrentCompany"])},mounted(){},methods:{}},Ce=Object.assign(ye,{__name:"LandingPage",setup(a){return(s,t)=>{const n=k("mask");return r(),w(E,null,{default:h(()=>[F,j,$,T,s.need_start?u("",!0):(r(),d("section",A,[e("div",L,[e("div",S,[P,e("a",{class:"btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder",onClick:t[0]||(t[0]=i=>s.need_start=!0)},"Я создам!"),V])])])),s.need_start?(r(),d("section",N,[e("div",I,[m(_,{start:1}),e("div",D,[e("button",{title:"Перезапустить",class:"btn btn-link",onClick:t[1]||(t[1]=i=>s.need_start=!1)},G)])])])):u("",!0),U,O,W,X,z,H,e("div",Y,[e("div",J,[e("div",K,[Q,e("div",Z,[e("form",ee,[e("div",se,[c(e("input",{class:"form-control","onUpdate:modelValue":t[2]||(t[2]=i=>s.feedbackForm.name=i),id:"name",type:"text",placeholder:"Enter your name...","data-sb-validations":"required"},null,512),[[o,s.feedbackForm.name]]),te,ae]),e("div",le,[c(e("input",{class:"form-control","onUpdate:modelValue":t[3]||(t[3]=i=>s.feedbackForm.email=i),id:"email",type:"email",placeholder:"name@example.com"},null,512),[[o,s.feedbackForm.email]]),ie,ce,oe]),e("div",re,[c(e("input",{class:"form-control","onUpdate:modelValue":t[4]||(t[4]=i=>s.feedbackForm.phone=i),id:"phone",type:"text",placeholder:"+7(123)456-78-90","data-sb-validations":"required"},null,512),[[n,"+7(###)###-##-##"],[o,s.feedbackForm.phone]]),ne,de]),e("div",me,[c(e("input",{class:"form-control",id:"time","onUpdate:modelValue":t[5]||(t[5]=i=>s.feedbackForm.time=i),type:"time",placeholder:"12:00"},null,512),[[o,s.feedbackForm.time]]),ue,pe]),e("div",he,[c(e("textarea",{"onUpdate:modelValue":t[6]||(t[6]=i=>s.feedbackForm.message=i),class:"form-control",id:"message",type:"text",placeholder:"Enter your message here...",style:{height:"10rem"},"data-sb-validations":"required"},null,512),[[o,s.feedbackForm.message]]),ge,be]),fe,xe,ve])])])])])]),_:1})}}});export{Ce as default};
