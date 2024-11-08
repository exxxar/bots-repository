import{G as b,R as p,q as a,t as n,v as l,H as r,J as c,F as g,C as _,D as d,y as i,x as f,K as S,A as u,P as F,B as v,O as m}from"./index.es-87f808e1.js";import{P as y}from"./Pagination-221154ef.js";const w={class:"row"},A={class:"col-12"},j={class:"form-floating mb-3"},k=l("label",{for:"floatingInput"},"Быстрый поиск команды",-1),M={key:0,class:"row"},G={class:"col-lg-3 col-md-6 col-12 mb-3"},P=["onClick"],O={class:"card-body"},V={style:{"word-wrap":"break-word"},class:"mb-0"},$=l("i",{class:"fa-solid fa-scroll"},null,-1),C={class:"mb-2"},B={class:"col-12"},N={key:2},D={key:3,class:"modal fade",id:"add-slug-to-bot",tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},I={class:"modal-dialog"},q={class:"modal-content"},E={class:"modal-body"},L={class:"mb-3"},J={class:"form-label",id:"bot-domain"},T=l("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),U=l("div",null,[d("Измените только текст команды,"),l("br"),d(" если хотите чтоб скрипт вызывался по кнопке из меню. "),l("br"),d(" Или оставьте как есть. "),l("br"),d(" Текст скрипта нужно также указать в качестве пункта меню. ")],-1),x=l("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),z=l("div",{class:"alert alert-info",role:"alert"}," При добавлении вы можете указать любое имя, которое вам нравится. Не обязательно использовать стандартное. ",-1),H=["disabled"],K=l("div",{class:"modal-footer"},[l("button",{type:"button",class:"btn btn-secondary","data-bs-dismiss":"modal"},"Не добавлять")],-1),R={props:["canAdd","bot","hideAddModal"],data(){return{search:null,slugs:[],slugs_paginate_object:null,addSlugModal:null,slugForm:{command:null,comment:null,slug:null,config:[],is_global:!0,bot_id:null}}},computed:{...b(["getGlobalSlugs","getGlobalSlugsPaginateObject"])},watch:{search:function(t,s){this.loadAllSlugs()}},mounted(){this.loadAllSlugs(),this.addSlugModal=new bootstrap.Modal(document.getElementById("add-slug-to-bot"),{})},methods:{loadAllSlugs(t=0){this.$store.dispatch("loadGlobalSlugs",{dataObject:{needGlobal:!0,search:this.search},page:t}).then(s=>{this.slugs=this.getGlobalSlugs,this.slugs_paginate_object=this.getGlobalSlugsPaginateObject})},removeSlug(t){this.$store.dispatch("removeSlug",{dataObject:{slugId:t.id}}).then(s=>{this.$notify({title:"Конструктор команд",text:"Команда успешно удалена",type:"success"}),this.loadAllSlugs()}).catch(s=>{this.loadAllSlugs()})},nextSlugs(t){this.loadAllSlugs(t)},selectSlug(t){this.slugForm.id=t.id||null,this.slugForm.slug=t.slug,this.slugForm.comment=t.comment,this.slugForm.command=this.command||t.command,this.slugForm.config=t.config||[],this.slugForm.is_global=t.is_global||!1,this.$emit("select",t),this.hideAddModal||this.addSlugModal.show()},addSlug(){let t=new FormData;Object.keys(this.slugForm).forEach(s=>{const e=this.slugForm[s]||"";typeof e=="object"?t.append(s,JSON.stringify(e)):t.append(s,e)}),this.bot&&t.append("bot_id",this.bot.id),this.$store.dispatch("createSlug",{slugForm:t}).then(s=>{this.$notify({title:"Скрипты",text:"Скрипт успешно добавлен",type:"success"}),this.slugForm.id=null,this.slugForm.command=null,this.slugForm.comment=null,this.slugForm.bot_id=null,this.slugForm.slug=null,this.slugForm.config=[],this.slugForm.is_global=!0,this.$emit("callback"),this.addSlugModal.hide()}).catch(s=>{})}}},Z=Object.assign(R,{__name:"GlobalSlugList",setup(t){return(s,e)=>{const h=p("Popper");return a(),n(g,null,[l("div",w,[l("div",A,[l("div",j,[r(l("input",{type:"search","onUpdate:modelValue":e[0]||(e[0]=o=>s.search=o),class:"form-control",id:"floatingInput",placeholder:"Название команды"},null,512),[[c,s.search]]),k])])]),s.slugs.length>0?(a(),n("div",M,[(a(!0),n(g,null,_(s.slugs,(o,Q)=>(a(),n("div",G,[l("div",{class:f(["card",{"btn-outline-info":o.deleted_at==null,"btn-outline-danger border-danger":o.deleted_at!=null}]),style:{"min-height":"160px"},onClick:W=>s.selectSlug(o)},[l("div",O,[l("p",V,[$,d(" "+i(o.command),1)]),l("p",C,[l("small",null,[l("strong",null,[l("em",null,i(o.slug),1)])])]),l("p",null,i(o.comment||"Пояснение не указано"),1)])],10,P)]))),256)),l("div",B,[s.slugs_paginate_object?(a(),S(y,{key:0,onPagination_page:s.nextSlugs,pagination:s.slugs_paginate_object},null,8,["onPagination_page","pagination"])):u("",!0)])])):(a(),n("p",N,"Глобальных скриптов не обнаружено")),t.canAdd?(a(),n("div",D,[l("div",I,[l("div",q,[l("div",E,[t.canAdd&&s.slugs.length>0?(a(),n("form",{key:0,onSubmit:e[2]||(e[2]=F((...o)=>s.addSlug&&s.addSlug(...o),["prevent"]))},[l("p",null,"Идентификатор скрипта #"+i(s.slugForm.id||"Не указан"),1),l("div",L,[l("label",J,[v(h,null,{content:m(()=>[U]),default:m(()=>[T]),_:1}),d(" Команда "),x]),r(l("input",{type:"text",class:"form-control",placeholder:"Команда","aria-label":"Команда","onUpdate:modelValue":e[1]||(e[1]=o=>s.slugForm.command=o),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[c,s.slugForm.command]])]),l("p",null,[l("small",null,[l("em",null,i(s.slugForm.comment||"Не указан"),1)])]),z,l("button",{disabled:s.slugForm.slug==null,class:"btn btn-success mt-2 mb-2 w-100 p-3"},"Добавить скрипт в бота ",8,H)],32)):u("",!0)]),K])])])):u("",!0)],64)}}});export{Z as _};
