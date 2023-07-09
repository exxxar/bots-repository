import{m as r,o as i,c as h,b as n,l as d,u as l,X as c,a as t,y as u,F as m,h as f,C as p}from"./app-273ab9a7.js";const g=t("title",null,"CashMan - система твоего бизнеса внутри",-1),_=t("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),b={id:"page"},y=f('<div class="header header-fixed header-auto-show header-logo-app"><a href="#" data-back-button class="header-title header-subtitle">Back to Pages</a><a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a><a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a><a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a><a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a></div><div id="footer-bar" class="footer-bar-5"><a href="/global-scripts/shop/products/isushibot"><i data-feather="heart" data-feather-line="1" data-feather-size="21" data-feather-color="red2-dark" data-feather-bg="red2-fade-light"></i><span>Продукты</span></a><a href="index-media.html"><i data-feather="image" data-feather-line="1" data-feather-size="21" data-feather-color="green1-dark" data-feather-bg="green1-fade-light"></i><span>Media</span></a><a href="/global-scripts/shop/home/isushibot"><i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue2-dark" data-feather-bg="blue2-fade-light"></i><span>Домой</span></a><a href="index-pages.html" class="active-nav"><i data-feather="file" data-feather-line="1" data-feather-size="21" data-feather-color="brown1-dark" data-feather-bg="brown1-fade-light"></i><span>Pages</span></a><a href="index-settings.html"><i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="gray2-dark" data-feather-bg="gray2-fade-light"></i><span>Settings</span></a></div>',2),v=t("div",{id:"menu-share",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-load":"menu-share.html","data-menu-height":"420","data-menu-effect":"menu-over"},null,-1),C=t("div",{id:"menu-highlights",class:"menu menu-box-bottom menu-box-detached rounded-m","data-menu-load":"menu-colors.html","data-menu-height":"510","data-menu-effect":"menu-over"},null,-1),w=t("div",{id:"menu-main",class:"menu menu-box-right menu-box-detached rounded-m","data-menu-width":"260","data-menu-load":"menu-main.html","data-menu-active":"nav-pages","data-menu-effect":"menu-over"},null,-1),x={props:["active"],data(){return{load:!1,bot:null,company:null}},computed:{...r(["getErrors","getCurrentBot","getCurrentCompany"])},watch:{getErrors:function(e,a){Object.keys(e).forEach(s=>{this.$notify({title:"Конструктор ботов",text:e[s],type:"warn"})})}},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",e=>{this.bot=this.getCurrentBot}),window.addEventListener("store_current_company-change-event",e=>{this.company=this.getCurrentCompany})},methods:{loadCurrentCompany(e=null){this.$store.dispatch("updateCurrentCompany",{company:e}).then(()=>{this.company=this.getCurrentCompany})},loadCurrentBot(e=null){this.$store.dispatch("updateCurrentBot",{bot:e}).then(()=>{this.bot=this.getCurrentBot})},resetCompany(){this.$store.dispatch("resetCurrentCompany").then(()=>{this.company=null,window.dispatchEvent(new CustomEvent("store_current_company-change-event"))})},resetBot(){this.$store.dispatch("resetCurrentBot").then(()=>{this.bot=null,window.dispatchEvent(new CustomEvent("store_current_bot-change-event"))})},stopAllDialogs(){this.$store.dispatch("stopDialogs").then(e=>{this.$notify({title:"Конструктор ботов",text:"Все диалоги остановлены",type:"success"})}).catch(e=>{})},reloadWebhooks(){this.load=!0,this.$notify({title:"Конструктор ботов",text:"Процедура обновления зависимостей началась"}),axios.get("/bot/register-webhooks").then(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Зависимости успешно обновлены!",type:"success"})}).catch(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Неудалось обновить зависимости",type:"error"})})}}},B=Object.assign(x,{__name:"ShopLayout",setup(e){return(a,s)=>{const o=p("notifications");return i(),h(m,null,[n(l(c),null,{default:d(()=>[g,_]),_:1}),n(o,{position:"top right"}),t("div",b,[y,u(a.$slots,"default"),v,C,w])],64)}}});export{B as _};
