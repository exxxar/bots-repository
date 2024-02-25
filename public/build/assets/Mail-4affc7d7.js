import{G as v,q as i,t as n,v as t,H as r,J as h,R as k,K as p,A as a,F as u,C as _,x as y,D as m,y as f,u as R,B as w,X as K,P as j,L as V}from"./index.es-71d37bd0.js";import{P}from"./Pagination-c0ff4992.js";import{V as g}from"./vue3-json-editor.esm.prod-61e9e0df.js";import{v as C}from"./v4-a960c1f4.js";import{T}from"./TelegramChannelHelper-93d52940.js";const S={class:"row"},B={class:"input-group mb-3"},M={class:"row"},q={class:"col-12"},O={class:"form-check"},U=t("label",{class:"form-check-label",for:"need-new-first"},"Сперва новые",-1),J={class:"col-12"},I={class:"form-check"},D=t("label",{class:"form-check-label",for:"needDeleted"},"Отобразить удаленные",-1),A={key:0,class:"row"},L={key:0,class:"col-12"},N={class:"col-12 mb-3"},E={class:"list-group w-100"},z={class:"d-flex justify-content-between"},G=["onClick"],H={key:0},X={key:1},Y={key:2,title:"Вы не можете выбрать данную страницу"},Q=t("i",{class:"fa-solid fa-lock ml-2 text-danger"},null,-1),W=[Q],Z={key:0},x={class:"dropdown"},ee=t("button",{class:"btn btn-outline-secondary",type:"button","data-bs-toggle":"dropdown","aria-expanded":"false"},[t("i",{class:"fa-solid fa-ellipsis"})],-1),te={class:"dropdown-menu"},oe={key:0},se=["onClick"],le=t("i",{class:"fa-solid fa-copy mr-1"},null,-1),ie={key:1},ne=["onClick"],ae=t("i",{class:"fa-solid fa-ban mr-1"},null,-1),de=["onClick"],re=t("i",{class:"fa-solid fa-copy mr-1"},null,-1),ce={key:2},he=["onClick"],ue=t("i",{class:"fa-solid fa-trash mr-1"},null,-1),be={key:0,class:"component-icons"},me={key:0},ye=t("i",{class:"fa-regular fa-images"},null,-1),pe=[ye],_e={key:1},fe=t("i",{class:"fa-regular fa-note-sticky"},null,-1),ke=[fe],ge={key:2},$e=t("i",{class:"fa-solid fa-photo-film"},null,-1),ve=[$e],we={key:3},Ce=t("i",{class:"fa-regular fa-file-audio"},null,-1),Fe=[Ce],Pe={key:4},Re=t("i",{class:"fa-regular fa-file-word"},null,-1),Ke=[Re],je={key:5},Ve=t("i",{class:"fa-solid fa-link"},null,-1),Te=[Ve],Se={key:6},Be=t("i",{class:"fa-solid fa-scroll"},null,-1),Me=[Be],qe={key:7},Oe=t("i",{class:"fa-regular fa-comment-dots"},null,-1),Ue=[Oe],Je={key:8},Ie=t("i",{class:"fa-solid fa-scale-balanced"},null,-1),De=[Ie],Ae={key:9},Le=t("i",{class:"fa-regular fa-keyboard"},null,-1),Ne=[Le],Ee={key:10},ze=t("i",{class:"fa-solid fa-ellipsis"},null,-1),Ge=[ze],He={class:"col-12"},Xe={key:1,class:"row"},Ye=t("div",{class:"col-12"},[t("div",{class:"alert alert-warning",role:"alert"}," Созданных страниц не найдено! ")],-1),Qe=[Ye],We={props:["editor","current","selected"],data(){return{bot:null,current_page:0,need_deleted:!1,need_new_first:!0,loading:!0,pages:[],search:null,pages_paginate_object:null,need_new_page:!1}},watch:{need_new_first:function(s,e){this.nextPages(0)},need_deleted:function(s,e){this.nextPages(0)},getPages:function(s,e){this.$nextTick(()=>{this.search||(this.pages=this.getPages)})},search:function(s,e){this.nextPages(0)}},computed:{...v(["getPages","getCurrentBot","getPagesPaginateObject"])},mounted(){this.loadCurrentBot().then(()=>{this.bot&&(this.current_page=localStorage.getItem(`cashman_pagelist_${this.bot.id}_page_index`)||0),this.loadPages()})},methods:{loadCurrentBot(s=null){return this.$store.dispatch("updateCurrentBot",{bot:s}).then(()=>{this.bot=this.getCurrentBot})},selectPage(s){this.$emit("callback",s)},nextPages(s){this.current_page=s,this.bot&&localStorage.setItem(`cashman_pagelist_${this.bot.id}_page_index`,this.current_page),this.loadPages()},duplicatePage(s){this.loading=!0,this.$store.dispatch("duplicatePage",{dataObject:{pageId:s}}).then(e=>{this.loading=!1,this.loadPages()}).catch(()=>{this.loading=!1})},forceRemovePage(s){this.loading=!0,this.$store.dispatch("forceRemovePage",{dataObject:{pageId:s}}).then(e=>{this.loading=!1,this.loadPages()}).catch(()=>{this.loading=!1})},restorePage(s){this.loading=!0,this.$store.dispatch("restorePage",{dataObject:{pageId:s}}).then(e=>{this.loading=!1,this.loadPages()}).catch(()=>{this.loading=!1})},removePage(s){this.loading=!0,this.$store.dispatch("removePage",{dataObject:{pageId:s}}).then(e=>{this.loading=!1,this.loadPages()}).catch(()=>{this.loading=!1})},loadPages(){this.loading=!0,this.$store.dispatch("loadPages",{dataObject:{botId:this.bot.id||null,search:this.search||null,needDeleted:this.need_deleted,needNewFirst:this.need_new_first},page:this.current_page||0}).then(s=>{this.loading=!1,this.pages=this.getPages,this.pages_paginate_object=this.getPagesPaginateObject,this.pages.length===0&&localStorage.setItem(`cashman_pagelist_${this.bot.id}_page_index`,0)}).catch(()=>{this.loading=!1})}}},Ze=Object.assign(We,{__name:"PagesList",setup(s){return(e,o)=>(i(),n(u,null,[t("div",S,[t("div",B,[r(t("input",{type:"search",class:"form-control",placeholder:"Поиск страницы","aria-label":"Поиск бота","onUpdate:modelValue":o[0]||(o[0]=l=>e.search=l),"aria-describedby":"button-addon2"},null,512),[[h,e.search]]),t("button",{class:"btn btn-outline-secondary",onClick:o[1]||(o[1]=(...l)=>e.loadPages&&e.loadPages(...l)),type:"button",id:"button-addon2"},"Найти ")])]),t("div",M,[t("div",q,[t("div",O,[r(t("input",{class:"form-check-input","onUpdate:modelValue":o[2]||(o[2]=l=>e.need_new_first=l),type:"checkbox",id:"need-new-first"},null,512),[[k,e.need_new_first]]),U])]),t("div",J,[t("div",I,[r(t("input",{class:"form-check-input","onUpdate:modelValue":o[3]||(o[3]=l=>e.need_deleted=l),type:"checkbox",id:"needDeleted"},null,512),[[k,e.need_deleted]]),D])])]),e.pages.length>0?(i(),n("div",A,[e.pages.length>7?(i(),n("div",L,[e.pages_paginate_object?(i(),p(P,{key:0,onPagination_page:e.nextPages,pagination:e.pages_paginate_object},null,8,["onPagination_page","pagination"])):a("",!0)])):a("",!0),t("div",N,[t("ul",E,[(i(!0),n(u,null,_(e.pages,(l,d)=>(i(),n("li",{class:y(["list-group-item cursor-pointer page-menu-item btn btn-outline-info mb-1",{"border border-warning":l.deleted_at!=null}])},[t("div",z,[t("strong",{onClick:c=>e.selectPage(l)},[m("#"+f(l.id||"Не указано")+" ",1),l.slug?(i(),n("span",H,f(l.slug.command||"Не указано"),1)):(i(),n("span",X,"Не привязано к команде")),s.current&&s.current==l.id||(s.selected||[]).indexOf(l.id)!=-1?(i(),n("span",Y,W)):a("",!0)],8,G),s.editor?(i(),n("div",Z,[t("div",x,[ee,t("ul",te,[l.deleted_at!=null?(i(),n("li",oe,[t("a",{class:"dropdown-item",onClick:c=>e.restorePage(l.id)},[le,m("Восстановить")],8,se)])):a("",!0),l.deleted_at!=null?(i(),n("li",ie,[t("a",{class:"dropdown-item",onClick:c=>e.forceRemovePage(l.id)},[ae,m("Удалить полностью")],8,ne)])):a("",!0),t("li",null,[t("a",{class:"dropdown-item",onClick:c=>e.duplicatePage(l.id)},[re,m("Дублировать")],8,de)]),l.deleted_at==null?(i(),n("li",ce,[t("a",{class:"dropdown-item",onClick:c=>e.removePage(l.id)},[ue,m("Удалить")],8,he)])):a("",!0)])])])):a("",!0)]),l?(i(),n("ul",be,[(l.images||[]).length>0?(i(),n("li",me,pe)):a("",!0),l.sticker?(i(),n("li",_e,ke)):a("",!0),(l.videos||[]).length>0?(i(),n("li",ge,ve)):a("",!0),(l.audios||[]).length>0?(i(),n("li",we,Fe)):a("",!0),(l.documents||[]).length>0?(i(),n("li",Pe,Ke)):a("",!0),l.next_page_id?(i(),n("li",je,Te)):a("",!0),l.next_bot_menu_slug_id?(i(),n("li",Se,Me)):a("",!0),l.next_bot_dialog_command_id?(i(),n("li",qe,Ue)):a("",!0),l.rules_if?(i(),n("li",Je,De)):a("",!0),l.reply_keyboard_id?(i(),n("li",Ae,Ne)):a("",!0),l.inline_keyboard_id?(i(),n("li",Ee,Ge)):a("",!0)])):a("",!0)],2))),256))])]),t("div",He,[e.pages_paginate_object?(i(),p(P,{key:0,onPagination_page:e.nextPages,pagination:e.pages_paginate_object},null,8,["onPagination_page","pagination"])):a("",!0)])])):(i(),n("div",Xe,Qe))],64))}});const xe={key:0},et={class:"row"},tt={class:"col-12 d-flex justify-content-between"},ot=t("i",{class:"fa-solid fa-arrows-up-to-line"},null,-1),st=[ot],lt=t("i",{class:"fa-solid fa-arrows-down-to-line"},null,-1),it=[lt],nt=t("i",{class:"fa-solid fa-xmark"},null,-1),at=[nt],dt={class:"d-flex flex-column"},rt={class:"form-check"},ct=["id"],ht=["for"],ut={key:0,class:"col-12"},bt={class:"row"},mt={class:"col-2 d-flex justify-content-around p-2"},yt=["onClick"],pt=t("i",{class:"fa-solid fa-plus"},null,-1),_t=[pt],ft=["onClick"],kt=t("i",{class:"fa-solid fa-minus"},null,-1),gt=[kt],$t=["onClick"],vt=t("i",{class:"fa-solid fa-caret-left"},null,-1),wt=[vt],Ct=["onClick"],Ft=t("i",{class:"fa-solid fa-caret-right"},null,-1),Pt=[Ft],Rt={class:"col-10 d-flex justify-content-center p-1"},Kt=["onClick"],jt=["onUpdate:modelValue"],Vt=t("i",{class:"fa-solid fa-bars"},null,-1),Tt=[Vt],St={key:0,class:"row"},Bt={class:"col-12"},Mt=t("label",{class:"form-label",id:"bot-domain"},"JSON-код клавиатуры",-1),qt={key:1},Ot={class:"row"},Ut={class:"col-12 mb-2"},Jt={class:"col-12"},It=t("div",{class:"alert alert-danger",role:"alert"}," Возможно выбрать только 1 тип действия ",-1),Dt={class:"mb-3"},At=["for"],Lt={class:"input-group"},Nt=["id"],Et=t("i",{class:"fa-solid fa-bars"},null,-1),zt=[Et],Gt=t("hr",null,null,-1),Ht={key:0,class:"mb-3"},Xt=["for"],Yt=["id"],Qt={key:1,class:"mb-3"},Wt=["for"],Zt=["id"],xt={key:2,class:"mb-3"},eo=["for"],to=["id"],oo={key:3,class:"mb-3"},so=["for"],lo=["id"],io={key:4,class:"form-check"},no=["id"],ao=["for"],ro={key:5,class:"form-check"},co=["checked","id"],ho=["for"],uo={key:6,class:"form-check"},bo=["checked","id"],mo=["for"],yo=["id"],po={class:"modal-dialog"},_o={class:"modal-content"},fo={class:"modal-body"},ko={props:["editedKeyboard","type"],components:{Vue3JsonEditor:g},computed:{uuid(){return C()}},watch:{keyboard:{handler:function(s){this.save()},deep:!0}},data(){return{pageModal:null,mode:0,editor:!1,showCode:!1,showAssign:!1,selectedRow:null,load:!1,rowCount:1,keyboard:[],select:{row:0,col:0,type:this.type||"reply"}}},mounted(){this.pageModal=new bootstrap.Modal(document.getElementById("page-list-in-keyboard-"+this.uuid),{}),this.editedKeyboard&&this.$nextTick(()=>{this.keyboard=this.editedKeyboard.menu})},methods:{openPageModal(){this.pageModal.show()},attachPage(s){let e=(s.slug.command||"Нет команды").replace(".*","");this.keyboard[this.select.row][this.select.col].text=e,this.$notify({title:"Конструктор страниц",text:"Вы успешно выбрали страницу",type:"success"}),this.type==="inline"&&(this.keyboard[this.select.row][this.select.col].callback_data=e)},reset(){this.selectedRow=null,this.select={row:-1,col:-1,type:this.type||"reply"}},needRemoveField(s,e,o){Object.keys(this.keyboard[e][o]).forEach(l=>{l!=="text"&&l!==s&&delete this.keyboard[e][o][l]})},save(){this.$emit("save",this.keyboard)},onJsonChange(s){this.keyboard=s,this.save()},removeColFromRow(s){this.keyboard[s].length>1?this.keyboard[s].splice(this.keyboard[s].length-1,1):this.keyboard.splice(s,1),this.keyboard.length===0&&(this.selectedRow=null),this.save()},addRowAbove(){this.addRow(!0)},moveCol(s,e=0){s!==this.select.row&&(this.select.row=s,this.select.col=0,this.select.text=this.keyboard[this.select.row][this.select.col].text);let o=this.select.row,l=this.select.col,d=this.keyboard[o].length,c=e===0?l-1>=0?l-1:d-1:l<d-1?l+1:0,b=this.keyboard[o][l];this.keyboard[o][l]=this.keyboard[o][c],this.keyboard[o][c]=b,this.select.row=o,this.select.col=c,this.select.text=this.keyboard[o][c].text},moveRow(s=0){if(this.selectedRow==null)return;let e=this.keyboard.length,o=s===0?this.selectedRow-1>=0?this.selectedRow-1:e-1:this.selectedRow<e-1?this.selectedRow+1:0,l=this.keyboard[this.selectedRow];this.keyboard[this.selectedRow]=this.keyboard[o],this.keyboard[o]=l,this.select.row=o,this.select.col=0,this.select.text=this.keyboard[o][0].text,this.selectedRow=o},addRowBelow(){this.addRow(!1)},addRow(s=!1){if(this.selectedRow==null)this.keyboard?this.keyboard.push([{text:"Нет команды"}]):this.keyboard=[[{text:"Нет команды"}]],this.selectedRow=null;else{let e=s?this.selectedRow:this.selectedRow+1;this.keyboard.splice(e,0,[{text:"Нет команды"}])}this.save()},addColToRow(s){this.keyboard[s].push({text:"Нет команды"}),this.save()},selectIndex(s,e){this.selectedRow=s,this.select.row=s,this.select.col=e,this.select.text=this.keyboard[s][e].text||"",this.load=!0,this.$nextTick(()=>{this.load=!1})},removeCol(s,e){this.keyboard[s].length>1?this.keyboard[s].splice(e,1):this.keyboard.splice(1,1),this.save()}}},F=Object.assign(ko,{__name:"KeyboardConstructor",setup(s){return(e,o)=>(i(),n(u,null,[!e.editor&&e.mode===0?(i(),n("div",xe,[t("div",et,[t("div",tt,[t("div",null,[e.selectedRow==null?(i(),n("button",{key:0,type:"button",class:"btn btn-primary mb-2",onClick:o[0]||(o[0]=(...l)=>e.addRow&&e.addRow(...l))},"Добавить строку ")):a("",!0),e.selectedRow!=null?(i(),n("button",{key:1,type:"button",class:"btn btn-primary mb-2",onClick:o[1]||(o[1]=(...l)=>e.addRowAbove&&e.addRowAbove(...l))},"Добавить строку выше ")):a("",!0),e.selectedRow!=null?(i(),n("button",{key:2,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:o[2]||(o[2]=(...l)=>e.addRowBelow&&e.addRowBelow(...l))},"Добавить строку ниже ")):a("",!0),e.selectedRow!=null?(i(),n("button",{key:3,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:o[3]||(o[3]=l=>e.moveRow(0))},st)):a("",!0),e.selectedRow!=null?(i(),n("button",{key:4,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:o[4]||(o[4]=l=>e.moveRow(1))},it)):a("",!0),e.selectedRow!=null?(i(),n("button",{key:5,type:"button",class:"btn btn-outline-danger mb-2 ml-2",onClick:o[5]||(o[5]=(...l)=>e.reset&&e.reset(...l))},at)):a("",!0)]),t("div",dt,[t("div",rt,[r(t("input",{class:"form-check-input",type:"checkbox","onUpdate:modelValue":o[6]||(o[6]=l=>e.showCode=l),id:"showCode"+e.uuid},null,8,ct),[[k,e.showCode]]),t("label",{class:"form-check-label",for:"showCode"+e.uuid}," Отобразить код ",8,ht)])])]),(e.keyboard||[]).length>0?(i(),n("div",ut,[(i(!0),n(u,null,_(e.keyboard,(l,d)=>(i(),n("div",bt,[t("div",mt,[t("button",{type:"button",class:"btn btn-link w-100",onClick:c=>e.addColToRow(d)},_t,8,yt),t("button",{type:"button",class:"btn btn-link w-100",onClick:c=>e.removeColFromRow(d)},gt,8,ft),t("button",{type:"button",class:"btn btn-link w-100",onClick:c=>e.moveCol(d,0)},wt,8,$t),t("button",{type:"button",class:"btn btn-link w-100",onClick:c=>e.moveCol(d,1)},Pt,8,Ct)]),t("div",Rt,[(i(!0),n(u,null,_(l,(c,b)=>(i(),n("div",{class:"btn-group dropdown-center w-100 m-1",onClick:$=>e.selectIndex(d,b)},[r(t("input",{type:"text",class:y([{"btn-outline-primary":e.select.row!=d||e.select.col!=b,"btn-primary":e.select.row==d&&e.select.col==b},"btn w-100"]),"onUpdate:modelValue":$=>e.keyboard[d][b].text=$},null,10,jt),[[h,e.keyboard[d][b].text]]),t("button",{type:"button",onClick:o[7]||(o[7]=$=>e.mode=1),class:"btn btn-outline-primary","aria-expanded":"false"},Tt)],8,Kt))),256))])]))),256))])):a("",!0)]),e.showCode?(i(),n("div",St,[t("div",Bt,[Mt,e.load?a("",!0):(i(),p(R(g),{key:0,mode:"code",modelValue:e.keyboard,"onUpdate:modelValue":o[8]||(o[8]=l=>e.keyboard=l),"show-btns":!1,expandedOnStart:!0,onJsonChange:e.onJsonChange},null,8,["modelValue","onJsonChange"]))])])):a("",!0)])):a("",!0),e.mode===1?(i(),n("div",qt,[t("div",Ot,[t("div",Ut,[t("button",{type:"button",onClick:o[9]||(o[9]=l=>e.mode=0),class:"btn btn-outline-primary"},"Назад ")]),t("form",Jt,[It,t("div",Dt,[t("label",{for:"command-title-"+e.select.row+"-col-"+e.select.сol,class:"form-label"},"Название кнопки",8,At),t("div",Lt,[r(t("input",{type:"text",id:"command-title-"+e.select.row+"-col-"+e.select.col,class:"form-control","onUpdate:modelValue":o[10]||(o[10]=l=>e.keyboard[e.select.row][e.select.col].text=l)},null,8,Nt),[[h,e.keyboard[e.select.row][e.select.col].text]]),t("button",{type:"button",onClick:o[11]||(o[11]=(...l)=>e.openPageModal&&e.openPageModal(...l)),class:"btn btn-outline-primary","aria-expanded":"false"},zt)])]),Gt,s.type==="inline"?(i(),n("div",Ht,[t("label",{for:"command-row-"+e.select.row+"-col-"+e.select.col,class:"form-label"},"Команда (для меню в сообщении)",8,Xt),r(t("input",{type:"text",onChange:o[12]||(o[12]=l=>e.needRemoveField("callback_data",e.select.row,e.select.col)),"onUpdate:modelValue":o[13]||(o[13]=l=>e.keyboard[e.select.row][e.select.col].callback_data=l),class:"form-control",id:"command-row-"+e.select.row+"-col-"+e.select.col,placeholder:"/start"},null,40,Yt),[[h,e.keyboard[e.select.row][e.select.col].callback_data]])])):a("",!0),s.type==="inline"?(i(),n("div",Qt,[t("label",{for:"switch-inline-query-row-"+e.select.row+"-col-"+e.select.col,class:"form-label"},"Ссылка на аккаунт в ТЕЛЕГРАММ",8,Wt),r(t("input",{type:"text",class:"form-control",onChange:o[14]||(o[14]=l=>e.needRemoveField("switch_inline_query",e.select.row,e.select.col)),"onUpdate:modelValue":o[15]||(o[15]=l=>e.keyboard[e.select.row][e.select.col].switch_inline_query=l),id:"switch-inline-query-row-"+e.select.row+"-col-"+e.select.col,placeholder:"@YourAccountLink"},null,40,Zt),[[h,e.keyboard[e.select.row][e.select.col].switch_inline_query]])])):a("",!0),s.type==="inline"?(i(),n("div",xt,[t("label",{for:"url-row-"+e.select.row+"-col-"+e.select.col,class:"form-label"},"Внешняя URL-ссылка",8,eo),r(t("input",{type:"text",class:"form-control",onChange:o[16]||(o[16]=l=>e.needRemoveField("url",e.select.row,e.select.col)),"onUpdate:modelValue":o[17]||(o[17]=l=>e.keyboard[e.select.row][e.select.col].url=l),id:"url-row-"+e.select.row+"-col-"+e.colIndex,placeholder:"https://t.me/example"},null,40,to),[[h,e.keyboard[e.select.row][e.select.col].url]])])):a("",!0),s.type==="inline"?(i(),n("div",oo,[t("label",{for:"switch-inline-query-current-chat-row-"+e.select.row+"-col-"+e.select.col,class:"form-label"},"Команда всплывающего меню бота",8,so),r(t("input",{type:"text",class:"form-control",onChange:o[18]||(o[18]=l=>e.needRemoveField("switch_inline_query_current_chat",e.select.row,e.select.col)),"onUpdate:modelValue":o[19]||(o[19]=l=>e.keyboard[e.select.row][e.select.col].switch_inline_query_current_chat=l),id:"witch-inline-query-current-chat-row-"+e.select.row+"-col-"+e.select.col,placeholder:"команда"},null,40,lo),[[h,e.keyboard[e.select.row][e.select.col].switch_inline_query_current_chat]])])):a("",!0),s.type==="reply"?(i(),n("div",io,[t("input",{type:"radio",onChange:o[20]||(o[20]=l=>e.needRemoveField(null,e.select.row,e.select.col)),name:"request-radio",class:"form-check-input",id:"no-action-row-"+e.select.row+"-col-"+e.select.col},null,40,no),t("label",{class:"form-check-label",for:"no-action-row-"+e.select.row+"-col-"+e.select.col}," Без действий ",8,ao)])):a("",!0),s.type==="reply"?(i(),n("div",ro,[t("input",{type:"radio",onChange:o[21]||(o[21]=l=>e.needRemoveField("request_contact",e.select.row,e.select.col)),onClick:o[22]||(o[22]=l=>e.keyboard[e.select.row][e.select.col].request_contact=!0),name:"request-radio",checked:e.keyboard[e.select.row][e.select.col].request_contact,class:"form-check-input",id:"phone-row-"+e.select.row+"-col-"+e.select.col},null,40,co),t("label",{class:"form-check-label",for:"phone-row-"+e.select.row+"-col-"+e.select.col}," Запросить телефон ",8,ho)])):a("",!0),s.type==="reply"?(i(),n("div",uo,[t("input",{type:"radio",name:"request-radio",checked:e.keyboard[e.select.row][e.select.col].request_location,onChange:o[23]||(o[23]=l=>e.needRemoveField("request_location",e.select.row,e.select.col)),onClick:o[24]||(o[24]=l=>e.keyboard[e.select.row][e.select.col].request_location=!0),class:"form-check-input",id:"location-row-"+e.select.row+"-col-"+e.select.col},null,40,bo),t("label",{class:"form-check-label",for:"location-row-"+e.select.row+"-col-"+e.select.col}," Запросить локацию ",8,mo)])):a("",!0)])])])):a("",!0),t("div",{class:"modal fade",id:"page-list-in-keyboard-"+e.uuid,tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},[t("div",po,[t("div",_o,[t("div",fo,[w(Ze,{onCallback:e.attachPage,editor:!1},null,8,["onCallback"])])])])],8,yo)],64))}});const go={key:0,class:"card"},$o={key:0,class:"card-header d-flex justify-content-between align-items-center"},vo=["disabled"],wo=t("i",{class:"fa-solid fa-arrow-left"},null,-1),Co=[wo],Fo=["disabled"],Po=t("i",{class:"fa-regular fa-pen-to-square"},null,-1),Ro=[Po],Ko=["disabled"],jo=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),Vo=[jo],To={key:1,class:"card-header d-flex justify-content-between align-items-center"},So={class:"mr-2"},Bo=["disabled"],Mo=t("i",{class:"fa-regular fa-pen-to-square"},null,-1),qo=[Mo],Oo=["disabled"],Uo=t("i",{class:"fa-regular fa-clone"},null,-1),Jo=[Uo],Io=["disabled"],Do=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),Ao=[Do],Lo=["disabled"],No=t("i",{class:"fa-solid fa-trash-can"},null,-1),Eo=[No],zo={class:"card-body"},Go={key:0,class:"row"},Ho={class:"col-md-6 col-12"},Xo={class:"mb-3"},Yo=t("label",{class:"form-label",id:"bot-domain"},"Тип",-1),Qo=t("option",{value:"reply"},"Нижняя клавиатура",-1),Wo=t("option",{value:"inline"},"Встроенная клавиатура",-1),Zo=[Qo,Wo],xo={class:"col-md-6 col-12"},es={class:"mb-3"},ts=t("label",{class:"form-label",id:"bot-domain"},"Мнемоническое имя",-1),os={class:"row"},ss={class:"col-12"},ls={class:"row"},is={class:"col-12"},ns={class:"row"},as={class:"col"},ds={type:"button",class:"btn btn-outline-primary w-100 mb-2"},rs={key:1,class:"card"},cs={class:"card-header d-flex justify-content-between align-items-center"},hs=["disabled"],us=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),bs=[us],ms={class:"card-body"},ys={props:["keyboard","selectMode"],data:()=>({is_edited:!1,load:!1,keyboardForm:null}),components:{Vue3JsonEditor:g},computed:{uuid(){return C()}},watch:{keyboardForm:{handler(s){this.is_edited=!0},deep:!0}},mounted(){const s=this.keyboard;this.keyboardForm=s,this.$nextTick(()=>{this.is_edited=!1,Array.isArray(s.menu)||this.updateKeyboard()})},methods:{onJsonChange(s){this.keyboardForm=s},removeKeyboard(){this.$store.dispatch("removeKeyboardTemplate",{templateId:this.keyboardForm.id}).then(s=>{this.load=!0,this.$nextTick(()=>{this.load=!1}),this.$emit("callback",this.keyboardForm.id)}).catch(()=>{this.$emit("callback",this.keyboardForm.id)})},saveKeyboard(s){this.keyboardForm.menu=s},selectCard(){this.$emit("select",this.keyboardForm)},duplicateKeyboard(){let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const o=this.keyboardForm[e]||"";typeof o=="object"?s.append(e,JSON.stringify(o)):s.append(e,o)}),this.$store.dispatch("createKeyboardTemplate",{keyboardForm:s}).then(e=>{this.$notify({title:"Конструктор ботов",text:"Меню успешно продублировано!",type:"success"}),this.load=!0,this.$nextTick(()=>{this.load=!1}),this.$emit("reload")})},updateKeyboard(){let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const o=this.keyboardForm[e]||"";typeof o=="object"?s.append(e,JSON.stringify(o)):s.append(e,o)}),this.$store.dispatch("editKeyboardTemplate",{keyboardForm:s}).then(e=>{this.load=!0,this.$nextTick(()=>{this.load=!1,this.is_edited=!1}),this.$emit("callback")}).catch(()=>{this.is_edited=!1,this.$emit("callback")})}}},ps=Object.assign(ys,{__name:"KeyboardCard",setup(s){return(e,o)=>(i(),n(u,null,[e.is_edited?a("",!0):(i(),n("div",go,[s.selectMode?(i(),n("div",$o,[t("div",null,[t("button",{onClick:o[0]||(o[0]=(...l)=>e.selectCard&&e.selectCard(...l)),disabled:e.load,type:"button",class:"btn btn-outline-success mr-2"},Co,8,vo),t("button",{onClick:o[1]||(o[1]=l=>e.is_edited=!0),disabled:e.load,type:"button",title:"Редактировать клавиатуру",class:"btn btn-outline-success mr-2"},Ro,8,Fo),t("button",{onClick:o[2]||(o[2]=(...l)=>e.updateKeyboard&&e.updateKeyboard(...l)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},Vo,10,Ko)])])):a("",!0),s.selectMode?a("",!0):(i(),n("div",To,[t("div",null,[t("strong",So,"#"+f(s.keyboard.id),1),t("button",{onClick:o[3]||(o[3]=l=>e.is_edited=!0),disabled:e.load,type:"button",title:"Редактировать клавиатуру",class:"btn btn-outline-success mr-2"},qo,8,Bo),t("button",{onClick:o[4]||(o[4]=(...l)=>e.duplicateKeyboard&&e.duplicateKeyboard(...l)),type:"button",title:"Дублировать клавиатуру",disabled:e.load,class:"btn btn-outline-primary mr-2"},Jo,8,Oo),t("button",{onClick:o[5]||(o[5]=(...l)=>e.updateKeyboard&&e.updateKeyboard(...l)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},Ao,10,Io)]),t("button",{onClick:o[6]||(o[6]=(...l)=>e.removeKeyboard&&e.removeKeyboard(...l)),type:"button",disabled:e.load,title:"Удалить клавиатуру",class:"btn btn-outline-danger mr-2"},Eo,8,Lo)])),t("div",zo,[s.selectMode?a("",!0):(i(),n("div",Go,[t("div",Ho,[t("div",Xo,[Yo,r(t("select",{disabled:!0,"onUpdate:modelValue":o[7]||(o[7]=l=>s.keyboard.type=l),class:"form-control"},Zo,512),[[K,s.keyboard.type]])])]),t("div",xo,[t("div",es,[ts,r(t("input",{type:"text",class:"form-control",placeholder:"Мнемоническое имя",disabled:!0,"aria-label":"Мнемоническое имя","onUpdate:modelValue":o[8]||(o[8]=l=>s.keyboard.slug=l),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[h,s.keyboard.slug]])])])])),t("div",os,[t("div",ss,[t("div",ls,[t("div",is,[(i(!0),n(u,null,_(s.keyboard.menu,(l,d)=>(i(),n("div",ns,[(i(!0),n(u,null,_(l,(c,b)=>(i(),n("div",as,[t("button",ds,f(c.text),1)]))),256))]))),256))])])])])])])),e.is_edited?(i(),n("div",rs,[t("div",cs,[t("button",{onClick:o[9]||(o[9]=(...l)=>e.updateKeyboard&&e.updateKeyboard(...l)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},bs,10,hs)]),t("div",ms,[e.keyboardForm?(i(),p(F,{key:0,onSave:e.saveKeyboard,"edited-keyboard":e.keyboardForm},null,8,["onSave","edited-keyboard"])):a("",!0),w(R(g),{mode:"code",modelValue:e.keyboardForm,"onUpdate:modelValue":o[10]||(o[10]=l=>e.keyboardForm=l),"show-btns":!1,expandedOnStart:!0,onJsonChange:e.onJsonChange},null,8,["modelValue","onJsonChange"])])])):a("",!0)],64))}}),_s={key:0,class:"row mb-2 py-3"},fs={class:"col-12"},ks={class:"card"},gs=t("div",{class:"card-header"},[t("h6",null,"Форма создания шаблона клавиатуры")],-1),$s={class:"card-body"},vs={class:"col-md-6 col-12"},ws={class:"mb-3"},Cs=t("label",{class:"form-label",id:"bot-domain"},"Тип",-1),Fs=t("option",{value:"reply"},"Нижняя клавиатура",-1),Ps=t("option",{value:"inline"},"Встроенная клавиатура",-1),Rs=[Fs,Ps],Ks={class:"col-md-6 col-12"},js={class:"mb-3"},Vs={class:"form-label d-flex justify-content-between",id:"bot-domain"},Ts=t("span",null,"Мнемоническое имя",-1),Ss=t("i",{class:"fa-solid fa-arrows-rotate"},null,-1),Bs=[Ss],Ms={class:"col-12"},qs=t("div",{class:"col-12"},[t("button",{class:"btn btn-outline-success w-100 p-3"}," Добавить новый шаблон клавиатуры ")],-1),Os={class:"row"},Us={key:0,class:"col-12"},Js={class:"badge bg-warning"},Is={class:"col-12 mb-3"},Ds={key:1,class:"card"},As={class:"card-body"},Ls={key:2,class:"col-12 mb-3"},Ns=t("div",{class:"alert alert-warning",role:"alert"},[t("p",null,"Список шаблонов клавиатур пуст!")],-1),Es=[Ns],zs={props:["selectMode","type"],data(){return{keyboards:[],load:!1,bot:null,editedKeyboard:null,selectMenuIndex:null,keyboardForm:{type:"reply",bot_id:null,slug:null,menu:[]}}},computed:{...v(["getCurrentBot"]),filteredKeyboard(){return this.type?this.keyboards.filter(s=>s.type==this.type):this.keyboards}},mounted(){this.loadCurrentBot().then(()=>{this.loadMenusByBotTemplate(),this.generateSlug()})},methods:{selectCard(s){this.$emit("select",s)},keyboardCallbackAction(s){let e=this.keyboards.find(o=>o.id===s);e&&(e.deleted_at=new Date),this.loadMenusByBotTemplate()},loadMenusByBotTemplate(){this.$store.dispatch("loadBotKeyboards",{botId:this.bot.id}).then(s=>{this.keyboards=s.data})},loadCurrentBot(s=null){return this.$store.dispatch("updateCurrentBot",{bot:s}).then(()=>{this.bot=this.getCurrentBot})},submitKeyboard(){this.keyboardForm.bot_id=this.bot.id;let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const o=this.keyboardForm[e]||"";typeof o=="object"?s.append(e,JSON.stringify(o)):s.append(e,o)}),this.$store.dispatch("createKeyboardTemplate",{keyboardForm:s}).then(e=>{this.keyboardForm={type:"reply",bot_id:null,slug:null,menu:[]},this.load=!0,this.$nextTick(()=>{this.load=!1}),this.loadMenusByBotTemplate()})},generateSlug(){this.keyboardForm.slug=C()},changeKeyboardFormMenu(s){this.keyboardForm.menu=s}}},Gs=Object.assign(zs,{__name:"KeyboardList",setup(s){return(e,o)=>(i(),n(u,null,[s.selectMode?a("",!0):(i(),n("div",_s,[t("div",fs,[t("div",ks,[gs,t("div",$s,[t("form",{onSubmit:o[3]||(o[3]=j((...l)=>e.submitKeyboard&&e.submitKeyboard(...l),["prevent"])),class:"row"},[t("div",vs,[t("div",ws,[Cs,r(t("select",{"onUpdate:modelValue":o[0]||(o[0]=l=>e.keyboardForm.type=l),class:"form-control"},Rs,512),[[K,e.keyboardForm.type]])])]),t("div",Ks,[t("div",js,[t("label",Vs,[Ts,t("a",{onClick:o[1]||(o[1]=(...l)=>e.generateSlug&&e.generateSlug(...l)),href:"#generate"},Bs)]),r(t("input",{type:"text",class:"form-control",placeholder:"Мнемоническое имя","aria-label":"Мнемоническое имя","onUpdate:modelValue":o[2]||(o[2]=l=>e.keyboardForm.slug=l),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[h,e.keyboardForm.slug]])])]),t("div",Ms,[e.load?a("",!0):(i(),p(F,{key:0,onSave:e.changeKeyboardFormMenu,"edited-keyboard":e.keyboardForm},null,8,["onSave","edited-keyboard"]))]),qs],32)])])])])),t("div",Os,[e.keyboards&&e.bot?(i(),n("div",Us,[t("p",null,[m("В списке клавиатур "),t("span",Js,f(e.filteredKeyboard.length)+" ед.",1)])])):a("",!0),e.keyboards&&e.bot?(i(!0),n(u,{key:1},_(e.filteredKeyboard,(l,d)=>(i(),n("div",Is,[l.deleted_at?(i(),n("div",Ds,[t("div",As,[t("p",null,"Удаленная клавиатура #"+f(l.id),1)])])):(i(),p(ps,{key:0,"select-mode":s.selectMode,onSelect:e.selectCard,onReload:e.loadMenusByBotTemplate,onCallback:e.keyboardCallbackAction,keyboard:l},null,8,["select-mode","onSelect","onReload","onCallback","keyboard"]))]))),256)):a("",!0),e.filteredKeyboard.length===0?(i(),n("div",Ls,Es)):a("",!0)])],64))}}),Hs={class:"col-md-12 col-12"},Xs={class:"mb-3"},Ys=t("i",{class:"fa-solid fa-xmark"},null,-1),Qs=[Ys],Ws={class:"mb-3"},Zs={class:"d-flex justify-content-between flex-wrap al"},xs=t("label",{class:"form-label",id:"bot-main-channel"},"Канал для постов (id,рекламный)",-1),el={class:"d-flex flex-wrap align-items-center"},tl=t("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),ol={class:"col-12 mb-2"},sl=t("label",{class:"form-label d-flex justify-content-between align-items-center mb-2",id:"bot-domain"},[m(" Текстовое содержимое страницы "),t("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно")],-1),ll={class:"form-floating"},il={for:"floatingTextarea2"},nl={key:0},al={class:"col-12 mb-2"},dl={class:"form-check"},rl=t("label",{class:"form-check-label",for:"need-page-images"}," Изображения на странице (максимум 10) ",-1),cl={key:0,class:"col-12 mb-2"},hl={class:"card mb-3"},ul=t("div",{class:"card-header"},[t("h6",null,"Изображения на странице")],-1),bl={class:"card-body d-flex justify-content-start"},ml={for:"photos",style:{"margin-right":"10px"},class:"photo-loader ml-2"},yl={class:"row"},pl={key:0,class:"col-12 d-flex flex-wrap"},_l={class:"mb-2 img-preview",style:{"margin-right":"10px"}},fl={class:"remove"},kl=["onClick"],gl={class:"col-12 mb-2"},$l={class:"form-check"},vl=t("label",{class:"form-check-label",for:"need-inline-menu"}," Меню под текстом страницы ",-1),wl={key:1,class:"col-12 mb-2"},Cl={class:"card"},Fl={class:"card-header d-flex justify-between align-items-center"},Pl=t("h6",null,"Конструктор меню в сообщении",-1),Rl={key:0},Kl={key:1},jl={class:"card-body"},Vl=t("div",{class:"col-12"},[t("button",{type:"submit",class:"btn btn-success w-100 p-3"}," Отправить сообщение в канал ")],-1),Tl={data(){return{load:!1,photos:[],showInlineTemplateSelector:!1,need_page_images:!1,need_inline_menu:!1,bot:null,mailForm:{text:"",inline_keyboard:null,channel:null}}},computed:{...v(["getCurrentBot"])},mounted(){this.loadCurrentBot()},methods:{addTextTo(s={param:null,text:null}){this.mailForm.channel=s.text},loadCurrentBot(s=null){return this.$store.dispatch("updateCurrentBot",{bot:s}).then(()=>{this.bot=this.getCurrentBot})},submitMail(){let s=new FormData;if(Object.keys(this.mailForm).forEach(e=>{const o=this.mailForm[e]||"";typeof o=="object"?s.append(e,JSON.stringify(o)):s.append(e,o)}),this.bot&&s.append("bot_id",this.bot.id),this.photos.length>0)for(let e=0;e<this.photos.length;e++)s.append("photos[]",this.photos[e]);this.$store.dispatch("sendToChannel",{mailForm:s}).then(e=>{this.load=!0,this.photos=[],this.need_inline_menu=!1,this.need_page_images=!1,this.mailForm={text:"",channel:null,inline_keyboard:null},this.$notify({title:"Конструктор ботов",text:"Сообщение успещно отправлено!",type:"success"})}).catch(e=>{})},saveInlineKeyboard(s){this.mailForm.inline_keyboard=s},selectInlineKeyboard(s){this.mailForm.inline_keyboard=s,this.showInlineTemplateSelector=!1},getPhoto(s){return{imageUrl:URL.createObjectURL(s)}},removePhoto(s){this.photos.splice(s,1)},removeImage(s){this.mailForm.images.splice(s,1)},onChangePhotos(s){const e=s.target.files;for(let o=0;o<e.length;o++)this.photos.push(e[o])}}},Ul=Object.assign(Tl,{__name:"Mail",setup(s){return(e,o)=>{const l=V("lazy");return e.bot?(i(),n("form",{key:0,onSubmit:o[9]||(o[9]=j((...d)=>e.submitMail&&e.submitMail(...d),["prevent"]))},[t("div",Hs,[t("div",Xs,[e.mailForm.channel!=null?(i(),n("button",{key:0,type:"button",class:"btn btn-outline-info mr-2",onClick:o[0]||(o[0]=d=>e.mailForm.channel=null)},Qs)):a("",!0),t("button",{type:"button",class:y(["btn mr-2",{"btn-info":e.mailForm.channel===e.bot.main_channel,"btn-outline-info":e.mailForm.channel!==e.bot.main_channel}]),onClick:o[1]||(o[1]=d=>e.mailForm.channel=e.bot.main_channel)},"Главный канал ",2),t("button",{type:"button",class:y(["btn",{"btn-info":e.mailForm.channel===e.bot.order_channel,"btn-outline-info":e.mailForm.channel!==e.bot.order_channel}]),onClick:o[2]||(o[2]=d=>e.mailForm.channel=e.bot.order_channel)},"Канал заказов ",2)]),t("div",Ws,[t("div",Zs,[xs,t("div",el,[w(T,{token:e.bot.bot_token,param:"channel",onCallback:e.addTextTo},null,8,["token","onCallback"]),tl])]),r(t("input",{type:"text",class:"form-control",placeholder:"id канала","aria-label":"id канала","onUpdate:modelValue":o[3]||(o[3]=d=>e.mailForm.channel=d),maxlength:"255","aria-describedby":"bot-main-channel",required:""},null,512),[[h,e.mailForm.channel]])])]),t("div",ol,[sl,t("div",ll,[r(t("textarea",{class:"form-control","onUpdate:modelValue":o[4]||(o[4]=d=>e.mailForm.text=d),maxlength:"4096",placeholder:"Введите текст",id:"floatingTextarea2",style:{"min-height":"100px"},required:""},null,512),[[h,e.mailForm.text]]),t("label",il,[m("Содержимое страницы "),e.mailForm.text?(i(),n("span",nl,f(e.mailForm.text.length)+"/4096 ",1)):a("",!0)])])]),t("div",al,[t("div",dl,[r(t("input",{class:"form-check-input","onUpdate:modelValue":o[5]||(o[5]=d=>e.need_page_images=d),type:"checkbox",id:"need-page-images"},null,512),[[k,e.need_page_images]]),rl])]),e.need_page_images?(i(),n("div",cl,[t("div",hl,[ul,t("div",bl,[t("label",ml,[m(" + "),t("input",{type:"file",id:"photos",multiple:"",accept:"image/*",onChange:o[6]||(o[6]=(...d)=>e.onChangePhotos&&e.onChangePhotos(...d)),style:{display:"none"}},null,32)]),t("div",yl,[e.photos.length>0?(i(),n("div",pl,[(i(!0),n(u,null,_(e.photos,(d,c)=>(i(),n("div",_l,[r(t("img",null,null,512),[[l,e.getPhoto(d).imageUrl]]),t("div",fl,[t("a",{onClick:b=>e.removePhoto(c)},"Удалить",8,kl)])]))),256))])):a("",!0)])])])])):a("",!0),t("div",gl,[t("div",$l,[r(t("input",{class:"form-check-input","onUpdate:modelValue":o[7]||(o[7]=d=>e.need_inline_menu=d),type:"checkbox",id:"need-inline-menu"},null,512),[[k,e.need_inline_menu]]),vl])]),e.need_inline_menu?(i(),n("div",wl,[t("div",Cl,[t("div",Fl,[Pl,t("button",{class:y(["btn",{"btn-outline-primary":!e.showInlineTemplateSelector,"btn-primary":e.showInlineTemplateSelector}]),type:"button",onClick:o[8]||(o[8]=d=>e.showInlineTemplateSelector=!e.showInlineTemplateSelector)},[e.showInlineTemplateSelector?(i(),n("span",Kl," Скрыть шаблоны меню")):(i(),n("span",Rl," Открыть шаблоны меню"))],2)]),t("div",jl,[e.showInlineTemplateSelector?(i(),p(Gs,{key:0,class:"mb-2",type:"inline",onSelect:e.selectInlineKeyboard,"select-mode":!0},null,8,["onSelect"])):(i(),p(F,{key:1,type:"inline",onSave:e.saveInlineKeyboard,"edited-keyboard":e.mailForm.inline_keyboard},null,8,["onSave","edited-keyboard"]))])])])):a("",!0),Vl],32)):a("",!0)}}});export{Ze as _,F as a,Gs as b,Ul as c};
