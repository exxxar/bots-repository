import{G as g,L as m,q as n,t as i,v as t,z as b,D as r,y as d,H as c,J as f,a4 as v,A as l,K as _,F as u,C as y,x as k}from"./index.es-560b785d.js";import{P as h}from"./TelegramChannelHelper.vue_vue_type_style_index_0_lang-6ebd6e3a.js";const w={class:"container pt-5"},B=t("h6",null,"Ваши доступные слоты",-1),C={class:"row"},j={class:"col-md-6"},S={class:"progress",role:"progressbar","aria-label":"Success striped example","aria-valuenow":"25","aria-valuemin":"0","aria-valuemax":"100"},$={class:"col-md-4 d-flex justify-content-between"},P={class:"col-md-2"},O={class:"row d-flex justify-content-center"},z={class:"col-md-6 col-12"},A={class:"input-group mb-3"},D={class:"w-100 d-flex justify-content-center flex-wrap"},F=t("p",{class:"mb-0 text-primary font-bold"},[t("small",null,"Тип сортировки")],-1),L={class:"list-group d-flex flex-row w-100 justify-content-center"},M={key:0},V=t("i",{class:"fa-solid fa-caret-down"},null,-1),I=[V],K={key:1},N=t("i",{class:"fa-solid fa-caret-up"},null,-1),G=[N],T={key:0},q=t("i",{class:"fa-solid fa-caret-down"},null,-1),E=[q],H={key:1},J=t("i",{class:"fa-solid fa-caret-up"},null,-1),U=[J],Q={key:0},R=t("i",{class:"fa-solid fa-caret-down"},null,-1),W=[R],X={key:1},Y=t("i",{class:"fa-solid fa-caret-up"},null,-1),Z=[Y],x={key:0},tt=t("i",{class:"fa-solid fa-caret-down"},null,-1),st=[tt],et={key:1},ot=t("i",{class:"fa-solid fa-caret-up"},null,-1),nt=[ot],it={key:0,class:"row my-2"},at={class:"col-12"},lt={key:0,class:"container pb-5"},rt={key:0,href:"/bot-page"},dt={class:"row row-cols-1 row-cols-lg-3 row-cols-md-1 g-4"},ct={class:"col"},_t={class:"card h-100"},ut={class:"card-img-top"},ht={class:"card-body"},pt={class:"card-title text-center"},gt={class:"card-title text-center"},mt={class:"card-text text-center"},bt={class:"text-center"},ft=["onClick"],vt={class:"row my-5"},yt={class:"col-12"},kt={key:1,class:"container"},wt=t("div",{class:"alert alert-info",role:"alert"},[t("p",null,"У вас еще нет созданных ботов, попробуйте с чего-то простого;)")],-1),Bt={class:"d-flex justify-content-center"},Ct={data(){return{search:null,bots:[],direction:"desc",order:"updated_at",bots_paginate_object:null}},computed:{...g(["getBots","getBotsPaginateObject"]),getSelf(){return window.profile||null},slotsCount(){return this.getSelf.manager.max_bot_slot_count},slotsPercent(){return this.bots_paginate_object?(this.bots_paginate_object.meta.total||0)/(this.summarySlotCount+this.slotsCount)*100:this.bots.length/(this.summarySlotCount+this.slotsCount)*100},summarySlotCount(){return this.bots_paginate_object?this.bots_paginate_object.meta.total:0}},mounted(){this.loadBots(0)},methods:{nextBots(a){this.loadBots(a)},loadAndOrder(a){this.order=a,this.direction=this.direction==="desc"?"asc":"desc",this.loadBots(0)},loadBots(a=0){this.$store.dispatch("loadBots",{dataObject:{search:this.search,order:this.order,direction:this.direction},page:a,size:20}).then(()=>{this.bots=this.getBots||[],this.bots_paginate_object=this.getBotsPaginateObject||null})},loadCurrentBot(a=null){this.$store.dispatch("updateCurrentBot",{bot:a}).then(()=>{this.bot=this.getCurrentBot})},gotoBot(a){this.loadCurrentBot(a),localStorage.setItem("cashman_set_botform_step_index",0),localStorage.setItem("cashman_set_botpage_step_index",2),window.location.href="/bot-page"},callback(a){this.$emit("callback",a)}}},Pt=Object.assign(Ct,{__name:"ManagerSlotList",setup(a){return(s,o)=>{const p=m("lazy");return n(),i(u,null,[t("div",w,[B,t("div",C,[t("div",j,[t("div",S,[t("div",{class:"progress-bar progress-bar-striped bg-success",style:b({width:s.slotsPercent+"%"})},null,4)])]),t("div",$,[t("p",null,[r("Число слотов "),t("strong",null,d(s.getSelf.manager.max_bot_slot_count),1),r(". Отображается ботов "),t("strong",null,d(s.summarySlotCount),1),r(" шт. под вашим управлением")])]),t("div",P,[t("a",{href:"#",onClick:o[0]||(o[0]=e=>s.callback(12))},"Пополнить слоты")])]),t("div",O,[t("div",z,[t("div",A,[c(t("input",{type:"search",class:"form-control",placeholder:"Поиск бота","aria-label":"Поиск бота","onUpdate:modelValue":o[1]||(o[1]=e=>s.search=e),onKeydown:o[2]||(o[2]=v(e=>s.loadBots(0),["enter"])),"aria-describedby":"button-addon2"},null,544),[[f,s.search]]),t("button",{class:"btn btn-outline-secondary",onClick:o[3]||(o[3]=e=>s.loadBots(0)),type:"button",id:"button-addon2"},"Найти ")]),t("div",D,[F,t("ul",L,[t("li",{class:"list-inline-item mr-2 cursor-pointer",onClick:o[4]||(o[4]=e=>s.loadAndOrder("id"))},[s.direction==="desc"&&s.order==="id"?(n(),i("span",M,I)):l("",!0),s.direction==="asc"&&s.order==="id"?(n(),i("span",K,G)):l("",!0),r(" Id ")]),t("li",{class:"list-inline-item mr-2 cursor-pointer",onClick:o[5]||(o[5]=e=>s.loadAndOrder("title"))},[s.direction==="desc"&&s.order==="title"?(n(),i("span",T,E)):l("",!0),s.direction==="asc"&&s.order==="title"?(n(),i("span",H,U)):l("",!0),r(" Название бота ")]),t("li",{class:"list-inline-item mr-2 cursor-pointer",onClick:o[6]||(o[6]=e=>s.loadAndOrder("bot_domain"))},[s.direction==="desc"&&s.order==="bot_domain"?(n(),i("span",Q,W)):l("",!0),s.direction==="asc"&&s.order==="bot_domain"?(n(),i("span",X,Z)):l("",!0),r(" Домен бота ")]),t("li",{class:"list-inline-item mr-2 cursor-pointer",onClick:o[7]||(o[7]=e=>s.loadAndOrder("updated_at"))},[s.direction==="desc"&&s.order==="updated_at"?(n(),i("span",x,st)):l("",!0),s.direction==="asc"&&s.order==="updated_at"?(n(),i("span",et,nt)):l("",!0),r(" Дата обновления ")])])])])]),s.bots.length>0?(n(),i("div",it,[t("div",at,[s.bots_paginate_object?(n(),_(h,{key:0,onPagination_page:s.nextBots,pagination:s.bots_paginate_object},null,8,["onPagination_page","pagination"])):l("",!0)])])):l("",!0)]),s.bots.length>0?(n(),i("div",lt,[t("h6",null,[r("Ваши созданные боты "),s.getSelf.is_admin?(n(),i("a",rt,"перейти в раздел")):l("",!0)]),t("div",dt,[(n(!0),i(u,null,y(s.bots,e=>(n(),i("div",ct,[t("div",_t,[c(t("img",ut,null,512),[[p,"/images/companies/"+e.bot_domain+"/logo.jpg"]]),t("div",ht,[t("h6",pt,"#"+d(e.id),1),t("h5",gt,d(e.title||e.id),1),t("p",mt,d(e.short_description||"Без описания"),1),t("p",bt,[t("small",null,d(s.$filters.currentFull(e.updated_at)),1)])]),t("div",{class:k(["card-footer",{"bg-danger":(e.bot_token||"").length<40,"bg-success":(e.bot_token||"").length>=40}])},[t("button",{type:"button",onClick:jt=>s.gotoBot(e),class:"btn btn-link text-white w-100"},"Редактировать ",8,ft)],2)])]))),256))]),t("div",vt,[t("div",yt,[s.bots_paginate_object?(n(),_(h,{key:0,onPagination_page:s.nextBots,pagination:s.bots_paginate_object},null,8,["onPagination_page","pagination"])):l("",!0)])])])):(n(),i("div",kt,[wt,t("div",Bt,[t("button",{class:"btn btn-primary",onClick:o[8]||(o[8]=e=>s.callback(0))},"Поехали создавать")])]))],64)}}});export{Pt as _};
