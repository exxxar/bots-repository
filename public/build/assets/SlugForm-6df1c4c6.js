import{m as S,B as w,o as e,c as n,a as t,F as f,r as b,n as F,f as d,t as _,d as c,e as k,g as v,b as C,l as y,w as g,v as h}from"./app-1adac4c7.js";import{P as j}from"./Pagination-c18394d5.js";import{_ as x}from"./_plugin-vue_export-helper-c27b6911.js";const I={key:0,class:"row"},O={class:"col-12 mb-3"},V={class:"list-group w-100"},$=["onClick"],A={key:0,class:"badge bg-info"},P=["onClick"],L=t("i",{class:"fa-solid fa-trash-can"},null,-1),U=[L],q={class:"col-12"},N={key:2},B={class:"card-body"},D={class:"mb-3"},T={class:"form-label",id:"bot-domain"},E=t("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),G=t("div",null,[d("Измените только текст команды,"),t("br"),d(" если хотите чтоб скрипт вызывался по кнопке из меню. "),t("br"),d(" Или оставьте как есть. "),t("br"),d(" Текст скрипта нужно также указать в качестве пункта меню. ")],-1),J=t("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),M=["disabled"],z={props:["canAdd","bot"],data(){return{search:null,slugs:[],slugs_paginate_object:null,slugForm:{command:null,comment:null,slug:null,is_global:!0,bot_id:null}}},computed:{...S(["getSlugs","getSlugsPaginateObject"]),filteredAllSlugs(){return this.slugs.length===0?[]:this.search==null?this.slugs:this.slugs.filter(o=>{let s=o.slug||"",i=o.command||"",p=o.comment||"";return i.toLowerCase().indexOf(this.search.toLowerCase())!==-1||p.toLowerCase().indexOf(this.search.toLowerCase())!==-1||s.toLowerCase().indexOf(this.search.toLowerCase())!==-1})}},mounted(){this.loadAllSlugs()},methods:{loadAllSlugs(o=0){this.$store.dispatch("loadSlugs",{dataObject:{needGlobal:!0},page:o}).then(s=>{this.slugs=this.getSlugs,this.slugs_paginate_object=this.getSlugsPaginateObject})},removeSlug(o){this.$store.dispatch("removeSlug",{dataObject:{slugId:o.id}}).then(s=>{this.$notify({title:"Конструктор команд",text:"Команда успешно удалена",type:"success"}),this.loadAllSlugs()}).catch(s=>{this.loadAllSlugs()})},nextSlugs(o){this.loadAllSlugs(o)},selectSlug(o){this.slugForm.id=o.id||null,this.slugForm.slug=o.slug,this.slugForm.comment=o.comment,this.slugForm.command=this.command||o.command,this.slugForm.is_global=o.is_global||!1,this.slugForm.bot_dialog_command_id=o.bot_dialog_command_id,this.$emit("select",o)},addSlug(){let o=new FormData;Object.keys(this.slugForm).forEach(s=>{const i=this.slugForm[s]||"";typeof i=="object"?o.append(s,JSON.stringify(i)):o.append(s,i)}),this.bot&&o.append("bot_id",this.bot.id),this.$store.dispatch("createSlug",{slugForm:o}).then(s=>{this.$notify({title:"Конструктор команд",text:"Команда успешно обновлена",type:"success"}),this.slugForm.id=null,this.slugForm.command=null,this.slugForm.comment=null,this.slugForm.bot_id=null,this.slugForm.slug=null,this.slugForm.config=[],this.slugForm.is_global=!0,this.slugForm.bot_dialog_command_id=null,this.$emit("callback")}).catch(s=>{})}}},Bt=Object.assign(z,{__name:"GlobalSlugList",setup(o){return(s,i)=>{const p=w("Popper");return e(),n(f,null,[s.filteredAllSlugs.length>0?(e(),n("div",I,[t("div",O,[t("ul",V,[(e(!0),n(f,null,b(s.filteredAllSlugs,(l,m)=>(e(),n("li",{class:F(["list-group-item cursor-pointer btn mb-1 d-flex align-items-center justify-between",{"btn-outline-info":l.deleted_at==null,"btn-outline-danger border-danger":l.deleted_at!=null}])},[t("strong",{onClick:a=>s.selectSlug(l),style:{"word-wrap":"break-word"}},[t("i",{class:F([{"text-danger":l.deleted_at!=null},"fa-solid fa-scroll"])},null,2),d(" "+_(l.command)+" (",1),t("span",null,_(l.slug),1),d(") ")],8,$),l.comment?(e(),n("span",A,_(l.comment||"Пояснение не указано"),1)):c("",!0),t("button",{class:"btn btn-outline-danger",onClick:a=>s.removeSlug(l),type:"button","data-bs-toggle":"dropdown","aria-expanded":"false"},U,8,P)],2))),256))])]),t("div",q,[s.slugs_paginate_object?(e(),k(j,{key:0,onPagination_page:s.nextSlugs,pagination:s.slugs_paginate_object},null,8,["onPagination_page","pagination"])):c("",!0)])])):(e(),n("p",N,"Глобальных скриптов не обноружено")),o.canAdd&&s.filteredAllSlugs.length>0?(e(),n("form",{key:3,onSubmit:i[1]||(i[1]=v((...l)=>s.addSlug&&s.addSlug(...l),["prevent"])),class:"card mb-3"},[t("div",B,[t("div",D,[t("label",T,[C(p,null,{content:y(()=>[G]),default:y(()=>[E]),_:1}),d(" Команда "),J]),g(t("input",{type:"text",class:"form-control",placeholder:"Команда","aria-label":"Команда","onUpdate:modelValue":i[0]||(i[0]=l=>s.slugForm.command=l),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[h,s.slugForm.command]])]),t("button",{disabled:s.slugForm.slug==null,class:"btn btn-outline-success mt-2 mb-2 w-100"},"Добавить скрипт в бота ",8,M)])],32)):c("",!0)],64)}}}),H={props:["item"],data(){return{simple:!0,configTypes:[{title:"Текстовый или числовой параметр",type:"text"}],slugForm:{bot_id:null,id:null,command:null,comment:null,slug:null,config:[],is_global:!0,bot_dialog_command_id:null}}},mounted(){this.item&&(this.slugForm.id=this.item.id||null,this.slugForm.command=this.item.command,this.slugForm.comment=this.item.comment,this.slugForm.bot_id=this.item.bot_id,this.slugForm.slug=this.item.slug,this.slugForm.config=this.item.config||[],this.slugForm.is_global=this.item.is_global||!1,this.slugForm.bot_dialog_command_id=this.item.bot_dialog_command_id)},methods:{onChangePhotos(o,s){const i=o.target.files;this.slugForm.config[s].value=i[0]},submit(){let o=new FormData;Object.keys(this.slugForm).forEach(s=>{const i=this.slugForm[s]||"";typeof i=="object"?o.append(s,JSON.stringify(i)):o.append(s,i)}),this.$store.dispatch(this.slugForm.id===null?"createSlug":"updateSlug",{slugForm:o}).then(s=>{this.$notify({title:"Конструктор команд",text:"Команда успешно обновлена",type:"success"}),this.slugForm.id===null&&(this.slugForm.id=null,this.slugForm.command=null,this.slugForm.comment=null,this.slugForm.bot_id=null,this.slugForm.slug=null,this.slugForm.config=[],this.slugForm.is_global=!0,this.slugForm.bot_dialog_command_id=null),this.$emit("callback")}).catch(s=>{})},addConfig(o){this.slugForm.config.push({key:null,value:null,type:o||"text"})},removeConfigItem(o){try{this.slugForm.config.splice(o,1)}catch{this.slugForm.config=[]}}}},K={class:"form-floating mb-3"},Q=t("label",{for:"floatingInput"},"Команда",-1),R={class:"form-floating mb-3"},W=t("label",{for:"floatingInput"},"Мнемоническое имя",-1),X={class:"form-floating mb-3"},Y=t("label",{for:"floatingInput"},"Описание скрипта",-1),Z={class:"card"},tt=t("div",{class:"card-header"}," Параметры скрипта ",-1),st={class:"card-body"},ot=t("div",{class:"alert alert-info",role:"alert"}," Данные параметры используются для настройки скриптов на стороне сервера ",-1),lt={class:"dropdown"},et=t("button",{class:"btn btn-outline-info w-100 dropdown-toggle mb-2",type:"button","data-bs-toggle":"dropdown","aria-expanded":"false"}," Добавить ",-1),nt={class:"dropdown-menu w-100"},it=["onClick"],at={class:"row"},rt={class:"col-md-5"},ut={class:"form-floating mb-3"},dt=["onUpdate:modelValue"],ct=t("label",{for:"floatingInput"},"Ключ",-1),mt={key:0,class:"col-md-5"},gt={class:"form-floating mb-3"},ht=["onUpdate:modelValue"],_t=t("label",{for:"floatingInput"},"Значение",-1),pt={key:1,class:"col-md-5"},ft=t("p",null,"Координаты",-1),bt=[ft],Ft={key:2,class:"col-md-5"},yt=["for"],vt=t("span",null,"+ ",-1),St={key:0},wt=["id","onChange"],kt={class:"col-md-2"},Ct=["onClick"],jt=t("i",{class:"fa-solid fa-trash-can"},null,-1),xt=[jt],It={key:1,class:"row"},Ot=t("div",{class:"col-12"},[t("p",null,"Параметры скрипта еще не добавлены")],-1),Vt=[Ot],$t={class:"btn btn-outline-primary w-100 mt-2 p-3"},At={key:0},Pt={key:1};function Lt(o,s,i,p,l,m){return e(),n("form",{onSubmit:s[3]||(s[3]=v((...a)=>m.submit&&m.submit(...a),["prevent"]))},[t("div",K,[g(t("input",{type:"text","onUpdate:modelValue":s[0]||(s[0]=a=>l.slugForm.command=a),class:"form-control",id:"floatingInput",placeholder:"name@example.com",required:""},null,512),[[h,l.slugForm.command]]),Q]),t("div",R,[g(t("input",{type:"text","onUpdate:modelValue":s[1]||(s[1]=a=>l.slugForm.slug=a),class:"form-control",id:"floatingInput",placeholder:"name@example.com",required:""},null,512),[[h,l.slugForm.slug]]),W]),t("div",X,[g(t("input",{type:"text","onUpdate:modelValue":s[2]||(s[2]=a=>l.slugForm.comment=a),class:"form-control",id:"floatingInput",placeholder:"name@example.com",required:""},null,512),[[h,l.slugForm.comment]]),Y]),t("div",Z,[tt,t("div",st,[ot,t("div",lt,[et,t("ul",nt,[(e(!0),n(f,null,b(l.configTypes,(a,r)=>(e(),n("li",null,[t("a",{class:"dropdown-item",onClick:u=>m.addConfig(a.type)},_(a.title||"Не установлен"),9,it)]))),256))])]),l.slugForm.config.length>0?(e(!0),n(f,{key:0},b(l.slugForm.config,(a,r)=>(e(),n("div",at,[t("div",rt,[t("div",ut,[g(t("input",{type:"text",class:"form-control","onUpdate:modelValue":u=>l.slugForm.config[r].key=u,id:"floatingInput",placeholder:"name@example.com",required:""},null,8,dt),[[h,l.slugForm.config[r].key]]),ct])]),l.slugForm.config[r].type==="text"?(e(),n("div",mt,[t("div",gt,[g(t("input",{type:"text",class:"form-control",id:"floatingInput","onUpdate:modelValue":u=>l.slugForm.config[r].value=u,placeholder:"name@example.com",required:""},null,8,ht),[[h,l.slugForm.config[r].value]]),_t])])):c("",!0),l.slugForm.config[r].type==="coords"?(e(),n("div",pt,bt)):c("",!0),l.slugForm.config[r].type==="image"?(e(),n("div",Ft,[t("label",{for:"param-photo-"+r+"-item-"+a.id,style:{"margin-right":"10px"},class:"photo-loader ml-2"},[vt,l.slugForm.config[r].value?(e(),n("span",St,_(l.slugForm.config[r].value),1)):c("",!0),t("input",{type:"file",id:"param-photo-"+r+"-item-"+a.id,accept:"image/*",onChange:u=>m.onChangePhotos(u,r),style:{display:"none"}},null,40,wt)],8,yt)])):c("",!0),t("div",kt,[t("button",{onClick:u=>m.removeConfigItem(r),class:"btn btn-outline-info w-100 p-3",type:"button","data-bs-toggle":"dropdown","aria-expanded":"false"},xt,8,Ct)])]))),256)):(e(),n("div",It,Vt))])]),t("button",$t,[l.slugForm.id==null?(e(),n("span",At," Сохранить команду ")):(e(),n("span",Pt," Обновить команду "))])],32)}const Dt=x(H,[["render",Lt]]);export{Dt as S,Bt as _};
