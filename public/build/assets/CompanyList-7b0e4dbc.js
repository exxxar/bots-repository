import{o as n,q as a,t as o,F as d,B as c,v as h,C as m,x as r,D as _,G as g,z as l,H as f}from"./index.es-0d0be4b9.js";import{P as b}from"./Pagination-233c587a.js";import{m as C}from"./index-d069a1eb.js";const v={key:0},k={class:"row"},w={class:"d-flex"},F={class:"dropdown mr-2"},y=o("button",{class:"btn btn-outline-primary dropdown-toggle",type:"button",id:"dropdownMenuButton1","data-bs-toggle":"dropdown","aria-expanded":"false"}," Фильтры ",-1),$={class:"dropdown-menu","aria-labelledby":"dropdownMenuButton1"},j=["onClick"],P={class:"input-group mb-3"},B={key:0,class:"mt-2"},L={class:"badge bg-info mr-1"},O=["onClick"],x=o("i",{class:"fa-solid fa-xmark"},null,-1),A=[x],E={key:0,class:"row"},T={class:"col-12 mb-3"},V={class:"list-group w-100"},D=["onClick"],I=["onClick"],M=o("i",{class:"fa-solid fa-boxes-packing"},null,-1),N=[M],S=["onClick"],z=o("i",{class:"fa-solid fa-box-open"},null,-1),G=[z],q={class:"col-12"},H={data(){return{show:!0,loading:!0,companies:[],search:null,filters:[{name:"Активные",icon:"fa-brands fa-telegram",slug:"active"},{name:"Архивные",icon:"fa-solid fa-box-archive",slug:"archive"}],selectedFilters:[],companies_paginate_object:null}},computed:{...C(["getCompanies","getCompaniesPaginateObject"]),filteredCompanies(){if(!this.companies)return[];if(this.selectedFilters.length===0&&this.search==null)return this.companies;if(this.selectedFilters.length===0&&this.search!=null)return this.companies.filter(e=>(e.title||"").trim().toLowerCase().indexOf(this.search.trim().toLowerCase())!==-1);let s=[];return this.selectedFilters.forEach(e=>{switch(e.slug){case"active":this.companies.filter(t=>t.deleted_at==null).forEach(t=>{s.push(t)});break;case"archive":this.companies.filter(t=>t.deleted_at!=null).forEach(t=>{s.push(t)});break}}),this.search==null?s:s.filter(e=>(e.title||"").trim().toLowerCase().indexOf(this.search.trim().toLowerCase())!==-1)}},mounted(){this.loadCompanies(),this.selectFilter("active")},methods:{addToArchive(s){this.$store.dispatch("removeCompany",{companyId:s}).then(e=>{let t=this.companies_paginate_object.meta.current_page||0;this.loadCompanies(t),this.$notify("Указанный клиент успешно перемещен в архив")})},extractFromArchive(s){this.$store.dispatch("restoreCompany",{companyId:s}).then(e=>{let t=this.companies_paginate_object.meta.current_page||0;this.loadCompanies(t),this.$notify("Указанный клиент успешно перемещен из архива")})},selectFilter(s){let e=this.filters.find(t=>t.slug===s);e&&this.selectedFilters.filter(t=>t.slug===s).length===0&&this.selectedFilters.push(e)},removeSelectedFilter(s){let e=this.selectedFilters.findIndex(t=>t.slug===s);this.selectedFilters.splice(e,1)},selectCompany(s){this.$store.dispatch("updateCurrentCompany",{company:s}),this.$emit("callback",s),this.show=!1,this.$notify("Вы выбрали компанию из спика! Все остальные действия будут производится для этой компании.")},nextCompanies(s){this.loadCompanies(s)},loadCompanies(s=0){this.loading=!0,this.$store.dispatch("loadCompanies",{dataObject:{search:this.search},page:s}).then(e=>{this.loading=!1,this.companies=this.getCompanies,this.companies_paginate_object=this.getCompaniesPaginateObject}).catch(()=>{this.loading=!1})}}},Q=Object.assign(H,{__name:"CompanyList",setup(s){return(e,t)=>e.show?(n(),a("div",v,[o("div",k,[o("div",w,[o("div",F,[y,o("ul",$,[(n(!0),a(d,null,c(e.filters,i=>(n(),a("li",null,[o("a",{class:"dropdown-item",onClick:u=>e.selectFilter(i.slug),href:"#filter"},[o("i",{class:h([i.icon,"mr-2"])},null,2),m(" "+r(i.name||"Не указано"),1)],8,j)]))),256))])]),o("div",P,[_(o("input",{type:"search",class:"form-control",placeholder:"Поиск компании","aria-label":"Поиск компании","onUpdate:modelValue":t[0]||(t[0]=i=>e.search=i),"aria-describedby":"button-addon2"},null,512),[[g,e.search]]),o("button",{class:"btn btn-outline-secondary",onClick:t[1]||(t[1]=(...i)=>e.loadCompanies&&e.loadCompanies(...i)),type:"button",id:"button-addon2"},"Найти ")])]),e.selectedFilters.length>0?(n(),a("p",B,[(n(!0),a(d,null,c(e.selectedFilters,i=>(n(),a("span",L,[m(r(i.name||"не указан")+" ",1),o("a",{onClick:u=>e.removeSelectedFilter(i.slug),class:"ml-1 text-white",href:"#filter"},A,8,O)]))),256))])):l("",!0)]),e.companies.length>0?(n(),a("div",E,[o("div",T,[o("ul",V,[(n(!0),a(d,null,c(e.filteredCompanies,(i,u)=>(n(),a("li",{class:h(["list-group-item btn mb-1 d-flex justify-between",{"btn-outline-info":i.deleted_at==null,"btn-outline-danger border-danger":i.deleted_at!=null}])},[o("span",{onClick:p=>e.selectCompany(i),class:h({"text-danger":i.deleted_at!=null})},r(i.title||"Не указано")+" ("+r(i.slug||"Не указано")+") ",11,D),i.deleted_at==null?(n(),a("button",{key:0,class:"btn btn-outline-info",type:"button",onClick:p=>e.addToArchive(i.id),title:"В архив"},N,8,I)):l("",!0),i.deleted_at!=null?(n(),a("button",{key:1,class:"btn btn-outline-info",type:"button",onClick:p=>e.extractFromArchive(i.id),title:"Из архива"},G,8,S)):l("",!0)],2))),256))])]),o("div",q,[e.companies_paginate_object?(n(),f(b,{key:0,onPagination_page:e.nextCompanies,pagination:e.companies_paginate_object},null,8,["onPagination_page","pagination"])):l("",!0)])])):l("",!0)])):l("",!0)}});export{Q as _};