import{_ as u}from"./MainAdminLayout-9c473c44.js";import{_ as d,a as m}from"./BotList-3f0a4bc5.js";import{_ as c}from"./CompanyList-9fab8f7a.js";import{m as b,e as r,l as h,o,a as s,n as l,c as i,d as a}from"./app-1adac4c7.js";import"./Pagination-c18394d5.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./v4-a960c1f4.js";import"./SlugForm-6df1c4c6.js";const y={class:"container"},C={class:"row mb-2"},k={class:"col-12"},v={class:"btn-group",role:"group","aria-label":"Basic outlined example"},_=["disabled"],f={key:0,class:"row"},B={key:0,class:"col-12"},g={class:"col-12"},$={key:1,class:"row"},w={key:2,class:"row"},L={data(){return{load:!1,step:0,bot:null,company:null}},computed:{...b(["getCurrentBot","getCurrentCompany"])},mounted(){this.loadCurrentCompany(),this.loadCurrentBot(),window.addEventListener("store_current_bot-change-event",e=>{this.bot=this.getCurrentBot,this.step=this.bot?2:1}),window.addEventListener("store_current_company-change-event",e=>{this.company=this.getCurrentCompany})},methods:{loadCurrentBot(e=null){this.$store.dispatch("updateCurrentBot",{bot:e}).then(()=>{this.bot=this.getCurrentBot})},loadCurrentCompany(e=null){this.$store.dispatch("updateCurrentCompany",{company:e}).then(()=>{this.company=this.getCurrentCompany})},companyListCallback(e){this.load=!0,this.loadCurrentCompany(e),this.$nextTick(()=>{this.load=!1})},botListCallback(e){this.load=!0,this.step=2,this.loadCurrentBot(e),this.$nextTick(()=>{this.load=!1})}}},P=Object.assign(L,{__name:"BotPage",setup(e){return(t,n)=>(o(),r(u,{active:1},{default:h(()=>[s("div",y,[s("div",C,[s("div",k,[s("div",v,[s("button",{type:"button",onClick:n[0]||(n[0]=p=>t.step=0),class:l([{"btn-primary":t.step===0,"btn-outline-primary":t.step!==0},"btn"])},"Создание бота ",2),s("button",{type:"button",onClick:n[1]||(n[1]=p=>t.step=1),class:l([{"btn-primary":t.step===1,"btn-outline-primary":t.step!==1},"btn"])},"Поиск бота ",2),s("button",{type:"button",disabled:!t.bot,onClick:n[2]||(n[2]=p=>t.step=2),class:l([{"btn-primary":t.step===2,"btn-outline-primary":t.step!==2},"btn"])},"Редактирование бота ",10,_)])])]),t.step===0?(o(),i("div",f,[t.company?a("",!0):(o(),i("div",B,[t.load?a("",!0):(o(),r(c,{key:0,onCallback:t.companyListCallback},null,8,["onCallback"]))])),s("div",g,[t.company&&!t.load?(o(),r(d,{key:0,company:t.company},null,8,["company"])):a("",!0)])])):a("",!0),t.step===1?(o(),i("div",$,[t.load?a("",!0):(o(),r(m,{key:0,editor:!0,onCallback:t.botListCallback},null,8,["onCallback"]))])):a("",!0),t.step===2?(o(),i("div",w,[t.bot&&!t.load?(o(),r(d,{key:0,bot:t.bot,editor:!0},null,8,["bot"])):a("",!0)])):a("",!0)])]),_:1}))}});export{P as default};
