import{m,_,C as b,j as w,o as i,c as n,a as t,b as o,l as r,w as k,t as d,f as p,d as u,h as g,P as y,u as x,X as C,y as T,F as P,e as S}from"./app-bdab52e1.js";const B={data(){return{isActive:!1}},computed:{...m(["cartTotalCount"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},currentBot(){return window.currentBot}},mounted(){}},F={id:"menu-main",class:"menu menu-box-right menu-box-detached rounded-m",style:{width:"260px",display:"block",height:"100vh","overflow-y":"scroll"},"data-menu-active":"nav-features","data-menu-effect":"menu-over"},L={class:"menu-header"},z=g('<a href="#" data-toggle-theme="" class="border-right-0"><i class="fa font-12 color-yellow1-dark fa-lightbulb"></i></a><a href="#" data-menu="menu-highlights" class="border-right-0"><i class="fa font-12 color-green1-dark fa-brush"></i></a><a href="#" data-menu="menu-share" class="border-right-0"><i class="fa font-12 color-red2-dark fa-share-alt"></i></a>',3),A=t("i",{class:"fa color-blue2-dark fa-cog font-12"},null,-1),N=t("a",{class:"border-right-0 close-menu"},[t("i",{class:"fa font-12 color-red2-dark fa-times"})],-1),M={class:"menu-logo text-center"},E={href:"#"},j={class:"rounded-circle bg-highlight",width:"80"},I={class:"p-3 font-800 font-24 text-uppercase"},D={class:"font-11 mt-n2"},O=t("i",{class:"fa-solid fa-location-dot mr-2 text-danger"},null,-1),U={class:"menu-items"},V=t("h5",{class:"text-uppercase opacity-20 font-12 pl-3"},"Главное меню",-1),q=t("i",{class:"fa-solid fa-house-chimney",style:{color:"lightblue"}},null,-1),R=t("span",null,"Главная",-1),W=t("i",{class:"fa fa-circle"},null,-1),G=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),J=t("span",null,"Корзина",-1),Q={key:0,class:"badge bg-highlight color-white"},H=t("i",{class:"fa fa-circle"},null,-1),X=t("i",{class:"fa-solid fa-pizza-slice",style:{color:"orangered"}},null,-1),Z=t("span",null,"Продукция",-1),K=t("i",{class:"fa fa-circle"},null,-1),Y=t("i",{class:"fa-solid fa-star",style:{color:"orange"}},null,-1),tt=t("span",null,"Избранное",-1),at=t("strong",{class:"badge bg-highlight color-white"},"3",-1),et=t("i",{class:"fa fa-circle"},null,-1),st=t("i",{class:"fa-regular fa-clock",style:{color:"#842029"}},null,-1),ot=t("span",null,"История заказов",-1),it=t("strong",{class:"badge bg-highlight color-white"},"3",-1),nt=t("i",{class:"fa fa-circle"},null,-1),ct=t("i",{class:"fa-solid fa-people-group",style:{color:"#6f42c1"}},null,-1),rt=t("span",null,"Наша команда",-1),lt=t("i",{class:"fa fa-circle"},null,-1),dt=t("i",{class:"fa-solid fa-star",style:{color:"orange"}},null,-1),ht=t("span",null,"О нас",-1),ut=t("i",{class:"fa fa-circle"},null,-1),ft=t("i",{class:"fa-regular fa-circle-question",style:{color:"green"}},null,-1),pt=t("span",null,"Правила сервиса",-1),_t=t("i",{class:"fa fa-circle"},null,-1),gt={href:"#",class:"close-menu"},mt={xmlns:"http://www.w3.org/2000/svg",width:"16",height:"16",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":"3","stroke-linecap":"round","stroke-linejoin":"round",class:"feather feather-x","data-feather-line":"3","data-feather-size":"16","data-feather-color":"red2-dark","data-feather-bg":"red2-fade-dark",style:{width:"16px",height:"16px"}},bt=t("line",{x1:"18",y1:"6",x2:"6",y2:"18"},null,-1),vt=t("line",{x1:"6",y1:"6",x2:"18",y2:"18"},null,-1),wt=[bt,vt],kt=t("span",null,"Закрыть",-1),yt=t("i",{class:"fa fa-circle"},null,-1),$t=g('<div class="text-center pt-2"><a href="#" class="icon icon-xs mr-1 rounded-s bg-facebook"><i class="fab fa-facebook"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-twitter"><i class="fab fa-twitter"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-instagram"><i class="fab fa-instagram"></i></a><a href="#" class="icon icon-xs mr-1 rounded-s bg-linkedin"><i class="fab fa-linkedin-in"></i></a><a href="#" class="icon icon-xs rounded-s bg-whatsapp"><i class="fab fa-whatsapp"></i></a><p class="mb-0 pt-3 font-10 opacity-30">Copyright <span class="copyright-year"></span> Enabled. All rights reserved</p></div>',1);function xt(e,a,f,h,l,s){const c=b("router-link"),v=w("lazy");return i(),n("div",F,[t("div",L,[z,o(c,{class:"close-menu",tag:"a",to:"/settings"},{default:r(()=>[A]),_:1}),N]),t("div",M,[t("a",E,[k(t("img",j,null,512),[[v,s.logo]])]),t("h1",I,d(s.currentBot.company.title||s.currentBot.bot_domain||"CashMan:Shopify"),1),t("p",D,[O,p(d(s.currentBot.company.address||"Без описания"),1)])]),t("div",U,[V,o(c,{id:"nav-home","active-class":"nav-item-active",tag:"a",to:"/home"},{default:r(()=>[q,R,W]),_:1}),o(c,{id:"nav-basket","active-class":"nav-item-active",tag:"a",to:"/basket"},{default:r(()=>[G,J,e.cartTotalCount>0?(i(),n("strong",Q,d(e.cartTotalCount),1)):u("",!0),H]),_:1}),o(c,{id:"nav-products","active-class":"nav-item-active",tag:"a",to:"/products"},{default:r(()=>[X,Z,K]),_:1}),o(c,{id:"nav-favorites","active-class":"nav-item-active",tag:"a",to:"/favorites"},{default:r(()=>[Y,tt,at,et]),_:1}),o(c,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/history"},{default:r(()=>[st,ot,it,nt]),_:1}),o(c,{id:"nav-out-team","active-class":"nav-item-active",tag:"a",to:"/our-team"},{default:r(()=>[ct,rt,lt]),_:1}),o(c,{id:"nav-contact-us","active-class":"nav-item-active",tag:"a",to:"/contact-us"},{default:r(()=>[dt,ht,ut]),_:1}),o(c,{id:"nav-terms","active-class":"nav-item-active",tag:"a",to:"/terms"},{default:r(()=>[ft,pt,_t]),_:1}),t("a",gt,[(i(),n("svg",mt,wt)),kt,yt])]),$t])}const Ct=_(B,[["render",xt]]);const Tt={data(){return{title:null,text:null}},mounted(){window.addEventListener("notification",e=>{this.title=e.detail.title||null,this.text=e.detail.text||null,$("#notification-1").toast("show")})}},Pt={id:"notification-1","data-dismiss":"notification-1","data-delay":"3000","data-autohide":"true",class:"notification notification-ios bg-dark1-dark"},St=t("span",{class:"notification-icon"},[t("i",{class:"fa fa-bell"}),t("em",null,"Оповещение"),t("i",{"data-dismiss":"notification-1",class:"fa fa-times-circle"})],-1),Bt={class:"font-15 color-white mb-n3"},Ft={class:"pt-2"};function Lt(e,a,f,h,l,s){return i(),n("div",Pt,[St,t("h1",Bt,d(l.title||"Системное"),1),t("p",Ft,d(l.text||"Ошибка отображения текста"),1)])}const zt=_(Tt,[["render",Lt]]),At={data(){return{product:null}},computed:{...m(["inCart","cartTotalCount","inFav"]),currentBot(){return window.currentBot},self(){return window.self},currentPrice(){return this.product.current_price},checkInCart(){return this.inCart(this.product.id)}},mounted(){window.addEventListener("add-to-cart",e=>{this.product=e.detail.product||null,this.product.in_favorite=this.inFav(this.product.id),this.$nextTick(()=>{$("#menu-product-info").showMenu()})})},methods:{removeFromFavorites(){this.product.in_favorite=!1,this.$store.dispatch("removeFromFavorites",this.product.id).then(()=>{this.$botNotification.notification("Избранное","Успешно удалено из избранного!")})},addToFavorite(){this.product.in_favorite=!0,this.$store.dispatch("addToFavorites",this.product).then(()=>{this.$botNotification.notification("Избранное","Успешно добавлено в избранное!")})},goToProduct(){this.$router.push({name:"product",params:{productId:this.product.id}}),$("#menu-product-info").hideMenu()},incProductCart(){this.checkInCart===0?this.$store.dispatch("addProductToCart",this.product):this.$store.dispatch("incQuantity",this.product.id),this.$botNotification.notification("Добавление товара","Успешно добавлено в корзину!")},decProductCart(){this.checkInCart<=1?this.$store.dispatch("removeProduct",this.product.id):this.$store.dispatch("decQuantity",this.product.id),this.$botNotification.notification("Добавление товара","Товар удален!")}}},Nt={id:"menu-product-info",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"220px",display:"block"},"data-menu-effect":"menu-over"},Mt={key:0,class:"w-100"},Et={class:"text-center font-700 mt-3 pt-1 px-4"},jt={key:0,class:"row text-center mr-2 ml-2 mb-3"},It={class:"col-4 mb-n2"},Dt=t("i",{class:"fa-solid fa-plus font-22"},null,-1),Ot=[Dt],Ut={class:"col-4 mb-n2 d-flex justify-content-center align-items-center"},Vt={class:"font-22"},qt={class:"col-4 mb-n2"},Rt=t("i",{class:"fa-solid fa-minus font-22"},null,-1),Wt=[Rt],Gt={key:1,class:"row text-center mr-2 ml-2 mb-3"},Jt={class:"col-12 mb-n2"},Qt=t("i",{class:"fa-solid fa-cart-plus font-12"},null,-1),Ht={class:"row text-center mr-2 ml-2 mb-3"},Xt={class:"col-12 mb-n2"},Zt=t("i",{class:"fa-regular fa-heart font-12"},null,-1),Kt=t("i",{class:"fa-solid fa-heart font-12"},null,-1);function Yt(e,a,f,h,l,s){return i(),n("div",Nt,[l.product?(i(),n("div",Mt,[t("h4",Et,d(l.product.title||"Нет заголовка"),1),s.checkInCart>0?(i(),n("div",jt,[t("div",It,[t("button",{type:"button",onClick:a[0]||(a[0]=(...c)=>s.incProductCart&&s.incProductCart(...c)),class:"btn p-3 w-100 bg-highlight rounded-s shadow-l"},Ot)]),t("div",Ut,[t("strong",Vt,d(s.checkInCart),1)]),t("div",qt,[t("button",{onClick:a[1]||(a[1]=(...c)=>s.decProductCart&&s.decProductCart(...c)),type:"button",class:"btn p-3 w-100 bg-red1-dark rounded-s shadow-l"},Wt)])])):(i(),n("div",Gt,[t("div",Jt,[t("button",{type:"button",onClick:a[2]||(a[2]=(...c)=>s.incProductCart&&s.incProductCart(...c)),class:"btn p-3 bg-highlight rounded-s shadow-l w-100"},[Qt,p(" В корзину "),t("strong",null,d(s.currentPrice)+"₽",1)])])])),t("div",Ht,[t("div",Xt,[l.product.in_favorite?(i(),n("button",{key:1,type:"button",onClick:a[4]||(a[4]=(...c)=>s.removeFromFavorites&&s.removeFromFavorites(...c)),class:"btn p-3 bg-highlight rounded-s shadow-l w-100"},[Kt,p(" Убрать из избранного ")])):(i(),n("button",{key:0,type:"button",onClick:a[3]||(a[3]=(...c)=>s.addToFavorite&&s.addToFavorite(...c)),class:"btn p-3 bg-red2-dark rounded-s shadow-l w-100"},[Zt,p(" В избранное ")]))])])])):u("",!0)])}const ta=_(At,[["render",Yt]]),aa={},ea={id:"menu-share",class:"menu menu-box-bottom menu-box-detached rounded-m d-block",style:{height:"420px"}},sa=g('<h1 class="text-center font-700 font-26 mt-3 pt-2">Share Azures</h1><p class="boxed-text-xl under-heading"> Share our page with the world, increase <br> your page exposure with the world. </p><div class="divider divider-margins"></div><div class="row text-center mr-4 ml-4 mb-0"><div class="col-3 mb-n2"><a href="https://www.facebook.com/sharer/sharer.php?u=http://pwa-azures.ru/code/store-homepage.html" class="shareToFacebook icon icon-l bg-facebook rounded-s shadow-l"><i class="fab fa-facebook-f font-22"></i><br></a><p class="font-11 opacity-70">Facebook</p></div><div class="col-3 mb-n2"><a href="https://twitter.com/home?status=http://pwa-azures.ru/code/store-homepage.html" class="shareToTwitter icon icon-l bg-twitter rounded-s shadow-l"><i class="fab fa-twitter font-22"></i><br></a><p class="font-11 opacity-70">Twitter</p></div><div class="col-3 mb-n2"><a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=http://pwa-azures.ru/code/store-homepage.html&amp;title=Azures BootStrap&amp;summary=&amp;source=" class="shareToLinkedIn icon icon-l bg-linkedin rounded-s shadow-l"><i class="fab fa-linkedin-in font-22"></i><br></a><p class="font-11 opacity-70">LinkedIn</p></div><div class="col-3 mb-n2"><a href="mailto:?body=http://pwa-azures.ru/code/store-homepage.html" class="shareToMail icon icon-l bg-mail rounded-s shadow-l"><i class="fa fa-envelope font-22"></i><br></a><p class="font-11 opacity-70">Email</p></div><div class="col-3 mb-n2"><a href="whatsapp://send?text=http://pwa-azures.ru/code/store-homepage.html" class="shareToWhatsApp icon icon-l bg-whatsapp rounded-s shadow-l"><i class="fab fa-whatsapp font-22"></i><br></a><p class="font-11 opacity-70">WhatsApp</p></div><div class="col-3 mb-n2"><a href="#" class="shareToCopyLink icon icon-l bg-blue2-dark rounded-s shadow-l"><i class="fa fa-link font-22"></i><br></a><p class="font-11 opacity-70">Copy Link</p></div><div class="col-3 mb-n2"><a href="https://pinterest.com/pin/create/button/?url=http://pwa-azures.ru/code/store-homepage.html" class="shareToPinterest icon icon-l bg-pinterest rounded-s shadow-l"><i class="fab fa-pinterest-p font-22"></i><br></a><p class="font-11 opacity-70">Pinterest</p></div><div class="col-3 mb-n2"><a href="#" class="close-menu icon icon-l bg-red2-dark rounded-s shadow-l"><i class="fa fa-times font-22"></i><br></a><p class="font-11 opacity-70">Close</p></div></div><div class="divider divider-margins mt-n1 mb-3"></div><p class="text-center font-10 mb-0">Copyright <span class="copyright-year"></span> - Enabled. All rights reserved.</p>',6),oa=[sa];function ia(e,a){return i(),n("div",ea,oa)}const na=_(aa,[["render",ia]]),ca={},ra={id:"menu-highlights",class:"menu menu-box-bottom menu-box-detached rounded-m",style:{display:"block",height:"510px"}},la=g('<div class="card header-card shape-rounded h-40"><div class="card-overlay bg-highlight opacity-95"></div><div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div></div><h1 class="text-center color-white mt-4 font-35 font-800">AZURES</h1><p class="text-center color-white font-12 mt-n1 pb-2 mb-3">Put a little color in your life</p><div class="card card-style"><div class="highlight-changer pt-3 pb-2"><p class="text-center"> Azures is packed with a beautiful color scheme.<br>Tap on your favorite! The page will adapt! </p><a href="#" data-change-highlight="blue2"><i class="fa fa-circle color-blue2-dark"></i><span class="color-blue2-light">Default</span></a><a href="#" data-change-highlight="red2"><i class="fa fa-circle color-red1-dark"></i><span class="color-red2-light">Red</span></a><a href="#" data-change-highlight="orange"><i class="fa fa-circle color-orange-dark"></i><span class="color-orange-light">Orange</span></a><a href="#" data-change-highlight="pink2"><i class="fa fa-circle color-pink2-dark"></i><span class="color-pink2-light">Pink</span></a><a href="#" data-change-highlight="magenta2"><i class="fa fa-circle color-magenta2-dark"></i><span class="color-magenta2-light">Purple</span></a><a href="#" data-change-highlight="aqua"><i class="fa fa-circle color-aqua-dark"></i><span class="color-aqua-light">Aqua</span></a><a href="#" data-change-highlight="teal"><i class="fa fa-circle color-teal-dark"></i><span class="color-teal-light">Teal</span></a><a href="#" data-change-highlight="mint"><i class="fa fa-circle color-mint-dark"></i><span class="color-mint-light">Mint</span></a><a href="#" data-change-highlight="green2"><i class="fa fa-circle color-green2-dark"></i><span class="color-green2-light">Green</span></a><a href="#" data-change-highlight="green1"><i class="fa fa-circle color-green1-dark"></i><span class="color-green1-light">Grass</span></a><a href="#" data-change-highlight="yellow2"><i class="fa fa-circle color-yellow2-dark"></i><span class="color-yellow2-light">Sunny</span></a><a href="#" data-change-highlight="yellow1"><i class="fa fa-circle color-yellow1-dark"></i><span class="color-yellow1-light">Goldish</span></a><a href="#" data-change-highlight="brown1"><i class="fa fa-circle color-brown1-dark"></i><span class="color-brown1-light">Wood</span></a><a href="#" data-change-highlight="dark1"><i class="fa fa-circle color-dark1-dark"></i><span class="color-dark1-light">Night</span></a><a href="#" data-change-highlight="dark2"><i class="fa fa-circle color-dark2-dark"></i><span class="color-dark2-light">Dark</span></a><div class="clearfix"></div></div></div><a href="#" class="close-menu btn btn-full btn-margins rounded-sm shadow-l bg-highlight btn-m font-900 text-uppercase mb-0">Закрыть смену цвета</a>',5),da=[la];function ha(e,a,f,h,l,s){return i(),n("div",ra,da)}const ua=_(ca,[["render",ha]]),fa={data(){return{load:!1}},mounted(){window.addEventListener("preloader-toggle",e=>{this.load=!this.load}),window.addEventListener("preloader-hide",e=>{this.load=!1}),window.addEventListener("preloader-show",e=>{this.load=!0,setTimeout(()=>{this.load=!1},500)})}},pa={key:0,id:"preloader"},_a=t("div",{class:"spinner-border color-highlight",role:"status"},null,-1),ga=[_a];function ma(e,a,f,h,l,s){return l.load?(i(),n("div",pa,ga)):u("",!0)}const ba=_(fa,[["render",ma]]);const va=t("title",null,"CashMan - система твоего бизнеса внутри",-1),wa=t("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),ka={id:"page"},ya={class:"header header-fixed header-auto-show header-logo-app"},$a=g('<a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a><a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a><a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>',5),xa={id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},Ca=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),Ta=t("span",{class:"color-white"},"Корзина",-1),Pa={key:0,class:"badge bg-green1-dark"},Sa={key:1},Ba=t("i",{class:"fa-brands fa-shopify"},null,-1),Fa=t("span",{class:"color-white"},"Продукты",-1),La={key:0},za=t("i",{class:"fa fa-home"},null,-1),Aa=t("span",{class:"color-white"},"Домой",-1),Na={key:0},Ma=t("i",{class:"fa fa-heart"},null,-1),Ea=t("span",{class:"color-white"},"Избранное",-1),ja={key:0,class:"badge bg-green1-dark"},Ia={key:1},Da=t("a",{href:"#","data-menu":"menu-main"},[t("i",{class:"fa-solid fa-bars"}),t("span",{class:"color-white"},"Меню")],-1),Oa={watch:{$route(e){this.$preloader.show(),this.$nextTick(()=>{document.body.scrollTop=document.documentElement.scrollTop=0,y.handler(),console.log("on route change")})}},computed:{...m(["cartTotalCount","favoritesCount"]),prefix(){return window.currentPath||""},tg(){return window.Telegram.WebApp},tgUser(){const e=new URLSearchParams(this.tg.initData);return JSON.parse(e.get("user"))}},mounted(){},methods:{openLink(e){this.tg.openLink(e,{try_instant_view:!0})},closeShop(){this.tg.close()}}},Ua=Object.assign(Oa,{__name:"ShopLayout",setup(e){return(a,f)=>{const h=b("router-link");return i(),n(P,null,[o(x(C),null,{default:r(()=>[va,wa]),_:1}),o(ba),o(ta),o(zt),t("div",ka,[t("div",ya,[t("a",{onClick:f[0]||(f[0]=(...l)=>a.closeShop&&a.closeShop(...l)),class:"header-title header-subtitle"},"Вернуться в бота"),$a]),t("div",xa,[o(h,{"active-class":"active-nav",tag:"a",to:a.prefix+"/basket"},{default:r(()=>[Ca,Ta,a.cartTotalCount>0?(i(),n("em",Pa,d(a.cartTotalCount),1)):u("",!0),a.$route.path==a.prefix+"/basket"?(i(),n("strong",Sa)):u("",!0)]),_:1},8,["to"]),o(h,{"active-class":"active-nav",tag:"a",to:a.prefix+"/products"},{default:r(()=>[Ba,Fa,a.$route.path==a.prefix+"/products"?(i(),n("strong",La)):u("",!0)]),_:1},8,["to"]),o(h,{"active-class":"active-nav",tag:"a",to:a.prefix+"/home"},{default:r(()=>[za,Aa,a.$route.path==a.prefix+"/home"?(i(),n("strong",Na)):u("",!0)]),_:1},8,["to"]),o(h,{"active-class":"active-nav",tag:"a",to:a.prefix+"/favorites"},{default:r(()=>[Ma,Ea,a.favoritesCount>0?(i(),n("em",ja,d(a.favoritesCount),1)):u("",!0),a.$route.path==a.prefix+"/favorites"?(i(),n("strong",Ia)):u("",!0)]),_:1},8,["to"]),Da]),T(a.$slots,"default"),o(na),o(ua)])],64)}}}),Va={class:"page-content",style:{"min-height":"667px"}},qa={class:"footer"},Ra={class:"card card-style mb-0"},Wa={href:"#",class:"footer-title p-4"},Ga=t("p",{class:"text-center font-12 mt-n1 mb-3 opacity-70"},[p(" Добавь "),t("span",{class:"color-highlight"},"красок"),p(" в свою жизнь ")],-1),Ja={class:"boxed-text-l"},Qa=t("div",{class:"text-center mb-3"},[t("a",{href:"#",class:"icon icon-xs rounded-sm shadow-l mr-1 bg-facebook"},[t("i",{class:"fab fa-facebook-f"})]),t("a",{href:"#",class:"icon icon-xs rounded-sm shadow-l mr-1 bg-twitter"},[t("i",{class:"fab fa-twitter"})]),t("a",{href:"#",class:"icon icon-xs rounded-sm shadow-l mr-1 bg-phone"},[t("i",{class:"fa fa-phone"})]),t("a",{href:"#","data-menu":"menu-share",class:"icon icon-xs rounded-sm mr-1 shadow-l bg-red2-dark"},[t("i",{class:"fa fa-share-alt"})]),t("a",{href:"#",class:"back-to-top icon icon-xs rounded-sm shadow-l bg-highlight color-white"},[t("i",{class:"fa fa-arrow-up"})])],-1),Ha=t("p",{class:"footer-copyright pb-3 mb-1"},[p("© CashMan "),t("span",{id:"copyright-year"},"2023"),p(". Все Права защищены.")],-1),Xa=t("div",{class:"footer-card card shape-rounded","data-card-height":"230",style:{height:"230px"}},[t("div",{class:"card-overlay bg-highlight opacity-95"}),t("div",{class:"card-bg preload-img","data-src":"/shop/images/pictures/20s.jpg"})],-1),Za={computed:{...m(["getSelf"]),tg(){return window.Telegram.WebApp},tgUser(){const e=new URLSearchParams(this.tg.initData);return JSON.parse(e.get("user"))},currentBot(){return window.currentBot}},created(){window.currentBot=this.bot.data;let e=this.tgUser||null;this.$store.dispatch("loadSelf",{dataObject:{telegram_chat_id:e?e.id:484698703,bot_id:window.currentBot.id}}).then(()=>{window.self=this.getSelf}),this.$notify({type:"success",text:"The operation completed"})}},Ya=Object.assign(Za,{__name:"Main",props:{bot:{type:Object}},setup(e){return(a,f)=>{const h=b("router-view");return i(),S(Ua,null,{default:r(()=>[t("div",Va,[o(h,{bot:e.bot},null,8,["bot"]),t("div",qa,[t("div",Ra,[t("a",Wa,d(a.currentBot.company.title||"CashMan:Shopify"),1),Ga,t("p",Ja,d(a.currentBot.company.description||"Описание вашего магазина"),1),Qa,Ha]),Xa])]),o(Ct)]),_:1})}}});export{Ya as default};
