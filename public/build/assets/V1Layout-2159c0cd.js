import{g as _,L as m,o as a,k as s,u as n,A as i,K as f,a6 as p,l as t,p as o,t as h,n as g,z as u,F as b,x as k}from"./index.es-00b6813f.js";import{b as v}from"./vue3-simple-typeahead-3fbd52b3.js";const w=t("title",null,"CashMan - система твоего бизнеса внутри",-1),y=t("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),$={id:"page"},C={class:"header header-fixed header-auto-show header-logo-app"},T=t("i",{class:"fas fa-arrow-left"},null,-1),S=[T],V=k('<a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a><a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>',3),B={key:0,href:"#","data-menu":"menu-main",class:"header-icon header-icon-4"},L=t("i",{class:"fas fa-bars"},null,-1),N=[L],E={key:0,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},A=t("i",{class:"fa-solid fa-basket-shopping"},null,-1),F=t("span",{class:"color-white"},"Корзина",-1),M={key:0,class:"badge bg-green1-dark"},j={key:1},z=t("i",{class:"fa-brands fa-shopify"},null,-1),D=t("span",{class:"color-white"},"Продукты",-1),G={key:0},J=t("i",{class:"fa fa-home"},null,-1),K=t("span",{class:"color-white"},"Домой",-1),O={key:0},R=t("i",{class:"fa fa-heart"},null,-1),W=t("span",{class:"color-white"},"Избранное",-1),X={key:0,class:"badge bg-green1-dark"},q={key:1},H=t("a",{href:"#","data-menu":"menu-main"},[t("i",{class:"fa-solid fa-bars"}),t("span",{class:"color-white"},"Меню")],-1),I={key:1,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},P=t("i",{class:"fa-solid fa-robot"},null,-1),Q=t("span",{class:"color-white"},"Настройка",-1),U={key:0},Y=t("i",{class:"fa-solid fa-ranking-star"},null,-1),Z=t("span",{class:"color-white"},"Статистика",-1),x={key:0},tt=t("i",{class:"fa fa-home"},null,-1),et=t("span",{class:"color-white"},"Главная",-1),at={key:0},st=t("i",{class:"fa-solid fa-file-lines"},null,-1),ot=t("span",{class:"color-white"},"Страницы",-1),nt={key:0},it=t("a",{href:"#","data-menu":"menu-admin-main"},[t("i",{class:"fa-solid fa-bars"}),t("span",{class:"color-white"},"Меню")],-1),rt={key:2,id:"footer-bar",class:"footer-bar-5 bg-transparent mb-2 ml-2 mr-2 rounded-m"},lt={class:"badge badge-danger",style:{"margin-top":"12px"}},dt={watch:{$route(c){this.$preloader.show(),v.handler(),this.$nextTick(()=>{document.body.scrollTop=document.documentElement.scrollTop=0})}},computed:{..._(["cartTotalCount","favoritesCount","getSelf"]),tg(){return window.Telegram.WebApp}},mounted(){},methods:{openLink(c){this.tg.openLink(c,{try_instant_view:!0})},closeShop(){this.tg.close()},scrollToBasket(){window.dispatchEvent(new CustomEvent("scroll-to-basket"))}}},ut=Object.assign(dt,{__name:"V1Layout",setup(c){return(e,l)=>{const r=m("router-link");return a(),s(b,null,[n(f(p),null,{default:i(()=>[w,y]),_:1}),t("div",$,[t("div",C,[t("a",{onClick:l[0]||(l[0]=(...d)=>e.closeShop&&e.closeShop(...d)),class:"header-title header-subtitle"},"Вернуться в бота"),t("a",{onClick:l[1]||(l[1]=d=>e.$router.back()),"data-back-button":"",class:"header-icon header-icon-1"},S),V,e.$route.meta.hide_menu?o("",!0):(a(),s("a",B,N))]),e.$route.meta.hide_menu?o("",!0):(a(),s("div",E,[n(r,{"active-class":"active-nav",tag:"a",to:"/basket"},{default:i(()=>[A,F,e.cartTotalCount>0?(a(),s("em",M,h(e.cartTotalCount),1)):o("",!0),e.$route.path=="/basket"?(a(),s("strong",j)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/products"},{default:i(()=>[z,D,e.$route.path=="/products"?(a(),s("strong",G)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/home"},{default:i(()=>[J,K,e.$route.path=="/home"?(a(),s("strong",O)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/favorites"},{default:i(()=>[R,W,e.favoritesCount>0?(a(),s("em",X,h(e.favoritesCount),1)):o("",!0),e.$route.path=="/favorites"?(a(),s("strong",q)):o("",!0)]),_:1}),H])),e.$route.meta.need_admin_menu?(a(),s("div",I,[n(r,{"active-class":"active-nav",tag:"a",to:"/admin-bot-manager"},{default:i(()=>[P,Q,e.$route.path=="/admin-bot-manager"?(a(),s("strong",U)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-statistic"},{default:i(()=>[Y,Z,e.$route.path=="/admin-statistic"?(a(),s("strong",x)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-main"},{default:i(()=>[tt,et,e.$route.path=="/admin-main"?(a(),s("strong",at)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-bot-page"},{default:i(()=>[st,ot,e.$route.path=="/admin-bot-page"?(a(),s("strong",nt)):o("",!0)]),_:1}),it])):o("",!0),e.$route.meta.show_cart&&e.cartTotalCount>0?(a(),s("div",rt,[t("button",{type:"button",onClick:l[2]||(l[2]=(...d)=>e.scrollToBasket&&e.scrollToBasket(...d)),class:"btn btn-m btn-full mb-3 rounded-l text-uppercase font-900 shadow-s bg-green2-dark position-relative w-100"},[g(" Перейти в корзину "),t("span",lt,h(e.cartTotalCount),1)])])):o("",!0),u(e.$slots,"default"),u(e.$slots,"modals")])],64)}}});export{ut as _};
