import{_ as d}from"./MainAdminLayout-8cccebd5.js";import{_ as m,a as u}from"./BotList-7c7dd736.js";import{_ as c}from"./CompanyList-75d6947c.js";import{m as b}from"./index-d069a1eb.js";import{o,H as r,M as h,t as s,v as l,q as i,z as a}from"./index.es-0d0be4b9.js";import"./Mail-4115ec75.js";import"./vue3-json-editor.esm.prod-743a86dd.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./v4-a960c1f4.js";import"./TelegramChannelHelper-bcff88dc.js";import"./SlugForm-b4ddf073.js";import"./Pagination-6b5f82da.js";/* empty css                                                               */const y={class:"container"},C={class:"row mb-2"},_={class:"col-12"},k={class:"btn-group",role:"group","aria-label":"Basic outlined example"},v=["disabled"],g={key:0,class:"row"},f={key:0,class:"col-12"},B={class:"col-12"},$={key:1,class:"row"},w={key:2,class:"row"},S={data(){return{load:!1,step:0,bot:null,company:null}},computed:{...b(["getCurrentBot","getCurrentCompany"])},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",e=>{this.bot=this.getCurrentBot,this.step=this.bot?2:1}),window.addEventListener("store_current_company-change-event",e=>{this.company=this.getCurrentCompany})},methods:{setStep(e){this.step=parseInt(e),localStorage.setItem("cashman_set_botpage_step_index",e)},loadCurrentBot(e=null){this.$store.dispatch("updateCurrentBot",{bot:e}).then(()=>{this.bot=this.getCurrentBot,this.setStep(localStorage.getItem("cashman_set_botpage_step_index")||0)})},loadCurrentCompany(e=null){this.$store.dispatch("updateCurrentCompany",{company:e}).then(()=>{this.company=this.getCurrentCompany})},companyListCallback(e){this.load=!0,this.loadCurrentCompany(e),this.$nextTick(()=>{this.load=!1})},botListCallback(e){this.load=!0,this.step=2,this.loadCurrentBot(e),this.$nextTick(()=>{this.load=!1})}}},P=Object.assign(S,{__name:"BotPage",setup(e){return(t,n)=>(o(),r(d,{active:1},{default:h(()=>[s("div",y,[s("div",C,[s("div",_,[s("div",k,[s("button",{type:"button",onClick:n[0]||(n[0]=p=>t.setStep(0)),class:l([{"btn-primary":t.step===0,"btn-outline-primary":t.step!==0},"btn"])},"Создание бота ",2),s("button",{type:"button",onClick:n[1]||(n[1]=p=>t.setStep(1)),class:l([{"btn-primary":t.step===1,"btn-outline-primary":t.step!==1},"btn"])},"Поиск бота ",2),s("button",{type:"button",disabled:!t.bot,onClick:n[2]||(n[2]=p=>t.setStep(2)),class:l([{"btn-primary":t.step===2,"btn-outline-primary":t.step!==2},"btn"])},"Редактирование бота ",10,v)])])]),t.step===0?(o(),i("div",g,[t.company?a("",!0):(o(),i("div",f,[t.load?a("",!0):(o(),r(c,{key:0,onCallback:t.companyListCallback},null,8,["onCallback"]))])),s("div",B,[t.company&&!t.load?(o(),r(m,{key:0,company:t.company},null,8,["company"])):a("",!0)])])):a("",!0),t.step===1?(o(),i("div",$,[t.load?a("",!0):(o(),r(u,{key:0,editor:!0,onCallback:t.botListCallback},null,8,["onCallback"]))])):a("",!0),t.step===2?(o(),i("div",w,[t.bot&&!t.load?(o(),r(m,{key:0,bot:t.bot,editor:!0},null,8,["bot"])):a("",!0)])):a("",!0)])]),_:1}))}});export{P as default};
