import{_ as m}from"./MainAdminLayout-0fdbe66d.js";import{_ as d,a as u}from"./BotList-31733654.js";import{_ as b}from"./CompanyList-7b0e4dbc.js";import{m as c}from"./index-d069a1eb.js";import{o,H as r,M as h,t as s,v as l,q as i,z as a}from"./index.es-0d0be4b9.js";import"./Mail-79f2c0ef.js";import"./vue3-json-editor.esm.prod-743a86dd.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./v4-a960c1f4.js";import"./TelegramChannelHelper-1f5aa349.js";import"./SlugForm-ad348806.js";import"./Pagination-233c587a.js";/* empty css                                                               */const y={class:"container"},C={class:"row mb-2"},k={class:"col-12"},v={class:"btn-group",role:"group","aria-label":"Basic outlined example"},_=["disabled"],f={key:0,class:"row"},B={key:0,class:"col-12"},g={class:"col-12"},$={key:1,class:"row"},w={key:2,class:"row"},L={data(){return{load:!1,step:0,bot:null,company:null}},computed:{...c(["getCurrentBot","getCurrentCompany"])},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",e=>{this.bot=this.getCurrentBot,this.step=this.bot?2:1}),window.addEventListener("store_current_company-change-event",e=>{this.company=this.getCurrentCompany})},methods:{loadCurrentBot(e=null){this.$store.dispatch("updateCurrentBot",{bot:e}).then(()=>{this.bot=this.getCurrentBot})},loadCurrentCompany(e=null){this.$store.dispatch("updateCurrentCompany",{company:e}).then(()=>{this.company=this.getCurrentCompany})},companyListCallback(e){this.load=!0,this.loadCurrentCompany(e),this.$nextTick(()=>{this.load=!1})},botListCallback(e){this.load=!0,this.step=2,this.loadCurrentBot(e),this.$nextTick(()=>{this.load=!1})}}},D=Object.assign(L,{__name:"BotPage",setup(e){return(t,n)=>(o(),r(m,{active:1},{default:h(()=>[s("div",y,[s("div",C,[s("div",k,[s("div",v,[s("button",{type:"button",onClick:n[0]||(n[0]=p=>t.step=0),class:l([{"btn-primary":t.step===0,"btn-outline-primary":t.step!==0},"btn"])},"Создание бота ",2),s("button",{type:"button",onClick:n[1]||(n[1]=p=>t.step=1),class:l([{"btn-primary":t.step===1,"btn-outline-primary":t.step!==1},"btn"])},"Поиск бота ",2),s("button",{type:"button",disabled:!t.bot,onClick:n[2]||(n[2]=p=>t.step=2),class:l([{"btn-primary":t.step===2,"btn-outline-primary":t.step!==2},"btn"])},"Редактирование бота ",10,_)])])]),t.step===0?(o(),i("div",f,[t.company?a("",!0):(o(),i("div",B,[t.load?a("",!0):(o(),r(b,{key:0,onCallback:t.companyListCallback},null,8,["onCallback"]))])),s("div",g,[t.company&&!t.load?(o(),r(d,{key:0,company:t.company},null,8,["company"])):a("",!0)])])):a("",!0),t.step===1?(o(),i("div",$,[t.load?a("",!0):(o(),r(u,{key:0,editor:!0,onCallback:t.botListCallback},null,8,["onCallback"]))])):a("",!0),t.step===2?(o(),i("div",w,[t.bot&&!t.load?(o(),r(d,{key:0,bot:t.bot,editor:!0},null,8,["bot"])):a("",!0)])):a("",!0)])]),_:1}))}});export{D as default};