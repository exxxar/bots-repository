import{G as h,q as a,t as l,B as u,O as m,u as b,a1 as g,v as t,y as r,x as i,D as c,A as d,N as f,F as v,R as p,K as w}from"./index.es-f98e90e3.js";const y=t("title",null,"CashMan - система твоего бизнеса внутри",-1),k=t("meta",{name:"description",content:"CashMan - система твоего бизнеса внутри"},null,-1),T={"data-bs-theme":"dark"},$={class:"text-bg-dark collapse",id:"navbarHeader",style:{}},C={class:"container"},S={class:"row"},B={class:"col-sm-8 col-md-7 py-4"},V=t("h4",null,"О нас",-1),j={class:"text-body-primary mb-2"},x={key:0,class:"col-sm-4 offset-md-1 py-4"},L=t("h4",null,"Меню",-1),q={class:"list-unstyled"},M={key:0,class:"fw-bold"},A={class:"navbar navbar-dark bg-dark shadow-sm"},N={class:"container"},H={href:"#",class:"navbar-brand d-flex align-items-center px-3"},O=t("i",{class:"fa-brands fa-shopify mr-2"},null,-1),E=t("button",{class:"navbar-toggler collapsed",type:"button","data-bs-toggle":"collapse","data-bs-target":"#navbarHeader","aria-controls":"navbarHeader","aria-expanded":"false","aria-label":"Toggle navigation"},[t("span",{class:"navbar-toggler-icon"})],-1),F={class:"text-body-secondary",style:{padding:"0px 0px 90px 0px"}},P={class:"container g-2 d-flex justify-content-center flex-column align-items-center"},z=["innerHTML"],D={key:1,class:"mb-3 text-center"},R=t("i",{class:"fa-solid fa-map-location-dot mr-2"},null,-1),G={class:"mb-0"},U={class:"d-flex justify-content-center my-3"},W=t("i",{class:"fa-solid fa-arrow-up mr-2"},null,-1),J={watch:{$route(s){console.log("router",this.$route.name),this.$preloader.show(),this.$nextTick(()=>{document.body.scrollTop=document.documentElement.scrollTop=0})}},computed:{...h(["getSelf","cartTotalCount"]),tg(){return window.Telegram.WebApp},bot(){return window.currentBot}},mounted(){this.changeTheme(this.tg.colorScheme),this.tg.BackButton.show(),this.tg.BackButton.onClick(()=>{document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(s=>s.click()),this.$router.back()})},methods:{goTo(s){this.$router.push({name:s})},changeTheme(s){document.querySelectorAll("[data-bs-theme]").forEach(o=>{console.log("item",o),o.setAttribute("data-bs-theme",s)})},scrollTop(){window.scrollTo(0,80)},openLink(s){this.tg.openLink(s,{try_instant_view:!0})},closeShop(){this.tg.close()}}},K=Object.assign(J,{__name:"SimpleLayout",setup(s){return(e,o)=>(a(),l(v,null,[u(b(g),null,{default:m(()=>[y,k]),_:1}),t("header",T,[t("div",$,[t("div",C,[t("div",S,[t("div",B,[V,t("p",j,r(e.bot.long_description||"Без описания"),1)]),e.bot.company?(a(),l("div",x,[L,t("ul",q,[t("li",null,[t("a",{class:i([{"fw-bold":e.$route.name==="MenuV2"},"text-white"]),onClick:o[0]||(o[0]=n=>e.goTo("MenuV2")),href:"javascript:void(0)"},"Главное меню",2)]),t("li",null,[t("a",{class:i([{"fw-bold":e.$route.name==="ProfileV2"},"text-white"]),onClick:o[1]||(o[1]=n=>e.goTo("ProfileV2")),href:"javascript:void(0)"},"Профиль",2)]),t("li",null,[t("a",{class:i([{"fw-bold":e.$route.name==="CatalogV2"},"text-white"]),onClick:o[2]||(o[2]=n=>e.goTo("CatalogV2")),href:"javascript:void(0)"},"Каталог товаров",2)]),t("li",null,[t("a",{class:i([{"fw-bold":e.$route.name==="ShopCartV2"},"text-white"]),onClick:o[3]||(o[3]=n=>e.goTo("ShopCartV2")),href:"javascript:void(0)"},[c("Корзина "),e.cartTotalCount>0?(a(),l("span",M,"("+r(e.cartTotalCount)+")",1)):d("",!0)],2)])])])):d("",!0)])])]),t("div",A,[t("div",N,[t("a",H,[t("strong",null,[O,c(" "+r(e.$route.meta.title||"Меню"),1)])]),E])])]),f(e.$slots,"default"),t("footer",F,[t("div",P,[e.$route.name!="FeedBackV2"?(a(),l("button",{key:0,onClick:o[4]||(o[4]=n=>e.goTo("FeedBackV2")),class:"btn btn-link mb-2 w-100 p-3 text-primary"},"Обратная связь")):d("",!0),t("p",{class:"mb-3 text-center",innerHTML:e.bot.company.description},null,8,z),e.bot.company.address?(a(),l("p",D,[R,c(r(e.bot.company.address),1)])):d("",!0),t("p",G,r(e.bot.company.title)+"©2024",1),t("p",U,[t("a",{href:"javascript:void(0)",onClick:o[5]||(o[5]=(...n)=>e.scrollTop&&e.scrollTop(...n))},[W,c("Вернуться наверх")])])])])],64))}}),X={computed:{...h(["getSelf"]),logo(){return`/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`},self(){return window.self||null},tg(){return window.Telegram.WebApp},tgUser(){const s=new URLSearchParams(this.tg.initData);return JSON.parse(s.get("user"))},currentBot(){return window.currentBot},qr(){return"https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link},link(){return"https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id)}},created(){window.currentBot=this.bot.data,window.currentScript=this.slug_id||null,this.$store.dispatch("loadSelf").then(()=>{window.self=this.getSelf}),this.$notify({title:"Главная",text:"Успешно!",type:"success"})},methods:{open(s){this.tg.openLink(s)}}},Q=Object.assign(X,{__name:"SimpleMain",props:{bot:{type:Object},slug_id:{type:String}},setup(s){return(e,o)=>{const n=p("notifications"),_=p("router-view");return a(),w(K,null,{default:m(()=>[u(n,{position:"top right",width:"100%",speed:"100"}),u(_,{bot:s.bot},null,8,["bot"])]),_:1})}}});export{Q as default};
