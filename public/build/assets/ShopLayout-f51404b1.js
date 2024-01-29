import{G as _,Y as m,q as t,t as s,B as n,O as i,u as f,a0 as p,v as e,A as o,y as h,N as u,F as g,M as v}from"./index.es-71d37bd0.js";import{b as k}from"./app-6ccacaf2.js";const b=e("title",null,"CashMan - система твоего бизнеса внутри",-1),w=e("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),y={id:"page"},$={class:"header header-fixed header-auto-show header-logo-app"},C=e("i",{class:"fas fa-arrow-left"},null,-1),S=[C],T=v('<a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a><a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>',3),B={key:0,href:"#","data-menu":"menu-main",class:"header-icon header-icon-4"},N=e("i",{class:"fas fa-bars"},null,-1),V=[N],L={key:0,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},M=e("i",{class:"fa-solid fa-basket-shopping"},null,-1),A=e("span",{class:"color-white"},"Корзина",-1),E={key:0,class:"badge bg-green1-dark"},F={key:1},G=e("i",{class:"fa-brands fa-shopify"},null,-1),O=e("span",{class:"color-white"},"Продукты",-1),j={key:0},q=e("i",{class:"fa fa-home"},null,-1),D=e("span",{class:"color-white"},"Домой",-1),J={key:0},R=e("i",{class:"fa fa-heart"},null,-1),W=e("span",{class:"color-white"},"Избранное",-1),X={key:0,class:"badge bg-green1-dark"},Y={key:1},z=e("a",{href:"#","data-menu":"menu-main"},[e("i",{class:"fa-solid fa-bars"}),e("span",{class:"color-white"},"Меню")],-1),H={key:1,id:"footer-bar",class:"footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m"},I=e("i",{class:"fa-solid fa-robot"},null,-1),K=e("span",{class:"color-white"},"Настройка",-1),P={key:0},Q=e("i",{class:"fa-solid fa-ranking-star"},null,-1),U=e("span",{class:"color-white"},"Статистика",-1),Z={key:0},x=e("i",{class:"fa fa-home"},null,-1),ee=e("span",{class:"color-white"},"Главная",-1),ae={key:0},te=e("i",{class:"fa-solid fa-file-lines"},null,-1),se=e("span",{class:"color-white"},"Страницы",-1),oe={key:0},ne=e("a",{href:"#","data-menu":"menu-admin-main"},[e("i",{class:"fa-solid fa-bars"}),e("span",{class:"color-white"},"Меню")],-1),ie={watch:{$route(c){this.$preloader.show(),k.handler(),this.$nextTick(()=>{document.body.scrollTop=document.documentElement.scrollTop=0})}},computed:{..._(["cartTotalCount","favoritesCount","getSelf"]),tg(){return window.Telegram.WebApp}},mounted(){console.log(window.currentBot)},methods:{openLink(c){this.tg.openLink(c,{try_instant_view:!0})},closeShop(){this.tg.close()}}},ce=Object.assign(ie,{__name:"ShopLayout",setup(c){return(a,l)=>{const r=m("router-link");return t(),s(g,null,[n(f(p),null,{default:i(()=>[b,w]),_:1}),e("div",y,[e("div",$,[e("a",{onClick:l[0]||(l[0]=(...d)=>a.closeShop&&a.closeShop(...d)),class:"header-title header-subtitle"},"Вернуться в бота"),e("a",{onClick:l[1]||(l[1]=d=>a.$router.back()),"data-back-button":"",class:"header-icon header-icon-1"},S),T,a.$route.meta.hide_menu?o("",!0):(t(),s("a",B,V))]),a.$route.meta.hide_menu?o("",!0):(t(),s("div",L,[n(r,{"active-class":"active-nav",tag:"a",to:"/basket"},{default:i(()=>[M,A,a.cartTotalCount>0?(t(),s("em",E,h(a.cartTotalCount),1)):o("",!0),a.$route.path=="/basket"?(t(),s("strong",F)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/products"},{default:i(()=>[G,O,a.$route.path=="/products"?(t(),s("strong",j)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/home"},{default:i(()=>[q,D,a.$route.path=="/home"?(t(),s("strong",J)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/favorites"},{default:i(()=>[R,W,a.favoritesCount>0?(t(),s("em",X,h(a.favoritesCount),1)):o("",!0),a.$route.path=="/favorites"?(t(),s("strong",Y)):o("",!0)]),_:1}),z])),a.$route.meta.need_admin_menu?(t(),s("div",H,[n(r,{"active-class":"active-nav",tag:"a",to:"/admin-bot-manager"},{default:i(()=>[I,K,a.$route.path=="/admin-bot-manager"?(t(),s("strong",P)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-statistic"},{default:i(()=>[Q,U,a.$route.path=="/admin-statistic"?(t(),s("strong",Z)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-main"},{default:i(()=>[x,ee,a.$route.path=="/admin-main"?(t(),s("strong",ae)):o("",!0)]),_:1}),n(r,{"active-class":"active-nav",tag:"a",to:"/admin-bot-page"},{default:i(()=>[te,se,a.$route.path=="/admin-bot-page"?(t(),s("strong",oe)):o("",!0)]),_:1}),ne])):o("",!0),u(a.$slots,"default"),u(a.$slots,"modals")])],64)}}});export{ce as _};
