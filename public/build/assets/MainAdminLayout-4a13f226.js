import{W as p,o as n,q as i,A as h,M as u,u as b,$ as _,t,C as l,x as d,z as r,v as c,L as v,F as f}from"./index.es-ce2063b4.js";import{m as y}from"./index-490ab070.js";const g=t("title",null,"Административная панель",-1),C=t("meta",{name:"description",content:"Административная панель<"},null,-1),k={class:"navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow"},w={class:"navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 cursor-pointer align-items-center d-flex","data-bs-toggle":"modal","data-bs-target":"#selected-company-bot-info"},x={key:0,style:{"font-size":"12px","margin-left":"10px"}},$=["href"],B=t("button",{class:"navbar-toggler position-absolute d-md-none collapsed",type:"button","data-bs-toggle":"collapse","data-bs-target":"#sidebarMenu","aria-controls":"sidebarMenu","aria-expanded":"false","aria-label":"Toggle navigation"},[t("span",{class:"navbar-toggler-icon"})],-1),E={class:"navbar-nav d-flex justify-content-center align-items-center flex-row"},M={class:"nav-item text-nowrap"},j=t("i",{class:"fa-solid fa-mug-hot"},null,-1),L={class:"nav-item text-nowrap"},A=t("i",{class:"fa-solid fa-robot"},null,-1),N={class:"nav-item text-nowrap"},V=t("i",{class:"fa-solid fa-scroll"},null,-1),z=t("div",{class:"navbar-nav d-none d-md-block"},[t("div",{class:"nav-item text-nowrap"},[t("a",{class:"nav-link px-3",href:"/logout"},"Выход")])],-1),D={class:"container-fluid"},F={class:"row"},O={class:"col-md-12 ms-sm-auto col-lg-12 px-md-4"},S={class:"pt-3 pb-2 mb-3"},T={class:"modal fade",id:"selected-company-bot-info",tabindex:"-1","aria-labelledby":"open-construct-label","aria-hidden":"true"},W={class:"modal-dialog"},q={class:"modal-content"},G=t("div",{class:"modal-header"},[t("h1",{class:"modal-title fs-5",id:"open-construct-label"},"У вас выбрано"),t("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"})],-1),X={class:"modal-body"},H={key:0,class:"card border-info mb-2"},I={class:"card-body"},J=t("p",null,"У Вас выбран клиент:",-1),K={class:"d-flex justify-content-between w-100"},P=t("i",{class:"fa-solid fa-xmark"},null,-1),Q=[P],R={key:1,class:"card border-info"},U={class:"card-body"},Y=t("p",null,"У Вас выбран бот:",-1),Z={class:"d-flex justify-content-between w-100"},tt=["href"],et=t("i",{class:"fa-solid fa-xmark"},null,-1),st=[et],ot=t("div",{class:"modal-footer"},[t("button",{type:"button",class:"btn btn-secondary","data-bs-dismiss":"modal"},"Закрыть")],-1),at={props:["active"],data(){return{load:!1,bot:null,company:null}},computed:{...y(["getErrors","getCurrentBot","getCurrentCompany"])},watch:{getErrors:function(e,s){Object.keys(e).forEach(o=>{this.$notify({title:"Конструктор ботов",text:e[o],type:"warn"})})}},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",e=>{this.bot=this.getCurrentBot}),window.addEventListener("store_current_company-change-event",e=>{this.company=this.getCurrentCompany})},methods:{loadCurrentCompany(e=null){this.$store.dispatch("updateCurrentCompany",{company:e}).then(()=>{this.company=this.getCurrentCompany})},loadCurrentBot(e=null){this.$store.dispatch("updateCurrentBot",{bot:e}).then(()=>{this.bot=this.getCurrentBot})},resetCompany(){this.$store.dispatch("resetCurrentCompany").then(()=>{this.company=null,window.dispatchEvent(new CustomEvent("store_current_company-change-event"))})},resetBot(){this.$store.dispatch("resetCurrentBot").then(()=>{this.bot=null,window.dispatchEvent(new CustomEvent("store_current_bot-change-event"))})},stopAllDialogs(){this.$store.dispatch("stopDialogs").then(e=>{this.$notify({title:"Конструктор ботов",text:"Все диалоги остановлены",type:"success"})}).catch(e=>{})},reloadWebhooks(){this.load=!0,this.$notify({title:"Конструктор ботов",text:"Процедура обновления зависимостей началась"}),axios.get("/bot/register-webhooks").then(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Зависимости успешно обновлены!",type:"success"})}).catch(()=>{this.load=!1,this.$notify({title:"Конструктор ботов",text:"Неудалось обновить зависимости",type:"error"})})}}},lt=Object.assign(at,{__name:"MainAdminLayout",setup(e){return(s,o)=>{const m=p("notifications");return n(),i(f,null,[h(b(_),null,{default:u(()=>[g,C]),_:1}),h(m,{position:"top right"}),t("header",k,[t("a",w,[l("CashMan: "),s.bot?(n(),i("span",x,[t("a",{href:"https://t.me/"+(s.bot.bot_domain||"botfather"),target:"_blank"},d(s.bot.bot_domain||"Без имени"),9,$)])):r("",!0)]),B,t("div",E,[t("div",M,[t("a",{class:c(["nav-link px-3",{"border-bottom-active active":e.active==0}]),onClick:o[0]||(o[0]=a=>e.active=0),href:"/company-page"},[j,l(" Клиенты")],2)]),t("div",L,[t("a",{class:c(["nav-link px-3",{"border-bottom-active active":e.active==1}]),onClick:o[1]||(o[1]=a=>e.active=1),href:"/bot-page"},[A,l(" Боты")],2)]),t("div",N,[t("a",{class:c(["nav-link px-3",{"border-bottom-active active":e.active==6}]),onClick:o[2]||(o[2]=a=>e.active=6),href:"/script-page"},[V,l(" Скрипты")],2)])]),z]),t("div",D,[t("div",F,[t("main",O,[t("div",S,[v(s.$slots,"default")])])])]),t("div",T,[t("div",W,[t("div",q,[G,t("div",X,[s.company?(n(),i("div",H,[t("div",I,[J,t("div",K,[t("span",null,d(s.company.title||"Без имени"),1),t("span",{onClick:o[3]||(o[3]=(...a)=>s.resetCompany&&s.resetCompany(...a))},Q)])])])):r("",!0),s.bot?(n(),i("div",R,[t("div",U,[Y,t("div",Z,[t("span",null,[t("a",{href:"https://t.me/"+(s.bot.bot_domain||"botfather"),target:"_blank"},d(s.bot.bot_domain||"Без имени"),9,tt)]),t("span",{onClick:o[4]||(o[4]=(...a)=>s.resetBot&&s.resetBot(...a))},st)])])])):r("",!0)]),ot])])])],64)}}});export{lt as _};
