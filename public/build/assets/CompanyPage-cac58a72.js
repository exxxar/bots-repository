import{_ as d}from"./MainAdminLayout-635ec8b4.js";import{_ as m}from"./CompanyForm-fc03a719.js";import{_ as c}from"./CompanyList-fc255b8d.js";import{G as u,q as a,K as l,O as y,v as o,x as i,t as r,A as s}from"./index.es-77235f88.js";import"./_plugin-vue_export-helper-c27b6911.js";/* empty css                                                    */import"./Pagination-df5e6be2.js";const b={class:"container"},C={class:"row mb-2"},k={class:"col-12"},h={class:"btn-group",role:"group","aria-label":"Basic outlined example"},_=["disabled"],v={key:0,class:"row"},f={class:"col-12"},$={key:1,class:"row"},g={class:"col-12"},w={key:2,class:"row"},B={class:"col-12"},T={data(){return{load:!1,step:0,company:null}},computed:{...u(["getCurrentCompany"])},mounted(){this.loadCurrentCompany()},methods:{loadCurrentCompany(n=null){this.$store.dispatch("updateCurrentCompany",{company:n}).then(()=>{this.company=this.getCurrentCompany})},companyListCallback(n){this.load=!0,this.loadCurrentCompany(n),this.step=2,this.$nextTick(()=>{this.load=!1})},companyCallback(n){this.load=!0,this.$nextTick(()=>{this.load=!1}),document.documentElement.scrollTop=0}}},q=Object.assign(T,{__name:"CompanyPage",setup(n){return(t,e)=>(a(),l(d,{active:0,"need-menu":!0},{default:y(()=>[o("div",b,[o("div",C,[o("div",k,[o("div",h,[o("button",{type:"button",onClick:e[0]||(e[0]=p=>t.step=0),class:i([{"btn-primary":t.step===0,"btn-outline-primary":t.step!==0},"btn"])},"Создание клиента в системе",2),o("button",{type:"button",onClick:e[1]||(e[1]=p=>t.step=1),class:i([{"btn-primary":t.step===1,"btn-outline-primary":t.step!==1},"btn"])},"Поиск существующего в системе клиента",2),o("button",{type:"button",disabled:!t.company,onClick:e[2]||(e[2]=p=>t.step=2),class:i([{"btn-primary":t.step===2,"btn-outline-primary":t.step!==2},"btn"])},"Редактирование выбранного клиента",10,_)])])]),t.step===0?(a(),r("div",v,[o("div",f,[t.load?s("",!0):(a(),l(m,{key:0,onCallback:t.companyCallback},null,8,["onCallback"]))])])):s("",!0),t.step===1?(a(),r("div",$,[o("div",g,[t.load?s("",!0):(a(),l(c,{key:0,onCallback:t.companyListCallback},null,8,["onCallback"]))])])):s("",!0),t.step===2?(a(),r("div",w,[o("div",B,[!t.load&&t.company?(a(),l(m,{key:0,company:t.company,editor:!0,onCallback:t.companyCallback},null,8,["company","onCallback"])):s("",!0)])])):s("",!0)])]),_:1}))}});export{q as default};
