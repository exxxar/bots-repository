import{Y as j,q as i,t as d,v as t,A as c,H as h,Q as F,F as m,C as p,J as u,x as y,K as _,y as v,X as T,B as S,u as q,G as B,P as V,D as g,L as M}from"./index.es-a464651f.js";import{V as R,T as U}from"./TelegramChannelHelper-68ec4b8e.js";import{_ as J}from"./_plugin-vue_export-helper-c27b6911.js";import{v as K}from"./v4-a960c1f4.js";const O={props:["editedKeyboard","type"],components:{Vue3JsonEditor:R},computed:{uuid(){return K()}},watch:{keyboard:{handler:function(s){this.save()},deep:!0}},data(){return{mode:0,editor:!1,showCode:!1,showAssign:!1,selectedRow:null,load:!1,rowCount:1,keyboard:[],select:{row:-1,col:-1,type:this.type||"reply"}}},mounted(){this.editedKeyboard&&this.$nextTick(()=>{this.keyboard=this.editedKeyboard.menu})},methods:{reset(){this.selectedRow=null,this.select={row:-1,col:-1,type:this.type||"reply"}},needRemoveField(s,e,l){Object.keys(this.keyboard[e][l]).forEach(n=>{n!=="text"&&n!==s&&delete this.keyboard[e][l][n]})},save(){this.$emit("save",this.keyboard)},onJsonChange(s){this.keyboard=s,this.save()},removeColFromRow(s){this.keyboard[s].length>1?this.keyboard[s].splice(this.keyboard[s].length-1,1):this.keyboard.splice(s,1),this.keyboard.length===0&&(this.selectedRow=null),this.save()},addRowAbove(){this.addRow(!0)},moveCol(s,e=0){s!==this.select.row&&(this.select.row=s,this.select.col=0,this.select.text=this.keyboard[this.select.row][this.select.col].text);let l=this.select.row,n=this.select.col,o=this.keyboard[l].length,a=e===0?n-1>=0?n-1:o-1:n<o-1?n+1:0,k=this.keyboard[l][n];this.keyboard[l][n]=this.keyboard[l][a],this.keyboard[l][a]=k,this.select.row=l,this.select.col=a,this.select.text=this.keyboard[l][a].text},moveRow(s=0){if(this.selectedRow==null)return;let e=this.keyboard.length,l=s===0?this.selectedRow-1>=0?this.selectedRow-1:e-1:this.selectedRow<e-1?this.selectedRow+1:0,n=this.keyboard[this.selectedRow];this.keyboard[this.selectedRow]=this.keyboard[l],this.keyboard[l]=n,this.select.row=l,this.select.col=0,this.select.text=this.keyboard[l][0].text,this.selectedRow=l},addRowBelow(){this.addRow(!1)},addRow(s=!1){if(this.selectedRow==null)this.keyboard?this.keyboard.push([{text:"No Text"}]):this.keyboard=[[{text:"No Text"}]],this.selectedRow=null;else{let e=s?this.selectedRow:this.selectedRow+1;this.keyboard.splice(e,0,[{text:"No Text"}])}this.save()},addColToRow(s){this.keyboard[s].push({text:"No Text"}),this.save()},selectIndex(s,e){this.selectedRow=s,this.select.row=s,this.select.col=e,this.select.text=this.keyboard[s][e].text||"",this.load=!0,this.$nextTick(()=>{this.load=!1})},removeCol(s,e){this.keyboard[s].length>1?this.keyboard[s].splice(e,1):this.keyboard.splice(1,1),this.save()}}},N={key:0},A={class:"row"},D={class:"col-12 d-flex justify-content-between"},E=t("i",{class:"fa-solid fa-arrows-up-to-line"},null,-1),P=[E],L=t("i",{class:"fa-solid fa-arrows-down-to-line"},null,-1),I=[L],z=t("i",{class:"fa-solid fa-xmark"},null,-1),G=[z],H={class:"d-flex flex-column"},Y={class:"form-check"},Q=["id"],X=["for"],W={key:0,class:"col-12"},Z={class:"row"},x={class:"col-2 d-flex justify-content-around p-2"},ee=["onClick"],te=t("i",{class:"fa-solid fa-plus"},null,-1),oe=[te],se=["onClick"],le=t("i",{class:"fa-solid fa-minus"},null,-1),ne=[le],ie=["onClick"],de=t("i",{class:"fa-solid fa-caret-left"},null,-1),re=[de],ae=["onClick"],ce=t("i",{class:"fa-solid fa-caret-right"},null,-1),he=[ce],ue={class:"col-10 d-flex justify-content-center p-1"},be=["onClick"],me=["onUpdate:modelValue"],ye=t("i",{class:"fa-solid fa-bars"},null,-1),pe=[ye],_e={key:0,class:"row"},ke={class:"col-12"},fe=t("label",{class:"form-label",id:"bot-domain"},"JSON-код клавиатуры",-1),ve={key:1},we={class:"row"},ge={class:"col-12 mb-2"},Ce={class:"col-12"},Fe=t("div",{class:"alert alert-danger",role:"alert"}," Возможно выбрать только 1 тип действия ",-1),Re={class:"mb-3"},Ke=["for"],$e=["id"],Te=t("hr",null,null,-1),Se={class:"mb-3"},Be=["for"],Ve=["id"],je={class:"mb-3"},qe=["for"],Me=["id"],Ue={class:"mb-3"},Je=["for"],Oe=["id"],Ne={class:"mb-3"},Ae=["for"],De=["id"],Ee={class:"form-check"},Pe=["id"],Le=["for"],Ie={class:"form-check"},ze=["id"],Ge=["for"],He={class:"form-check"},Ye=["id"],Qe=["for"];function Xe(s,e,l,n,o,a){const k=j("Vue3JsonEditor");return i(),d(m,null,[!o.editor&&o.mode===0?(i(),d("div",N,[t("div",A,[t("div",D,[t("div",null,[o.selectedRow==null?(i(),d("button",{key:0,type:"button",class:"btn btn-primary mb-2",onClick:e[0]||(e[0]=(...r)=>a.addRow&&a.addRow(...r))},"Добавить строку ")):c("",!0),o.selectedRow!=null?(i(),d("button",{key:1,type:"button",class:"btn btn-primary mb-2",onClick:e[1]||(e[1]=(...r)=>a.addRowAbove&&a.addRowAbove(...r))},"Добавить строку выше ")):c("",!0),o.selectedRow!=null?(i(),d("button",{key:2,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:e[2]||(e[2]=(...r)=>a.addRowBelow&&a.addRowBelow(...r))},"Добавить строку ниже ")):c("",!0),o.selectedRow!=null?(i(),d("button",{key:3,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:e[3]||(e[3]=r=>a.moveRow(0))},P)):c("",!0),o.selectedRow!=null?(i(),d("button",{key:4,type:"button",class:"btn btn-primary mb-2 ml-2",onClick:e[4]||(e[4]=r=>a.moveRow(1))},I)):c("",!0),o.selectedRow!=null?(i(),d("button",{key:5,type:"button",class:"btn btn-outline-danger mb-2 ml-2",onClick:e[5]||(e[5]=(...r)=>a.reset&&a.reset(...r))},G)):c("",!0)]),t("div",H,[t("div",Y,[h(t("input",{class:"form-check-input",type:"checkbox","onUpdate:modelValue":e[6]||(e[6]=r=>o.showCode=r),id:"showCode"+a.uuid},null,8,Q),[[F,o.showCode]]),t("label",{class:"form-check-label",for:"showCode"+a.uuid}," Отобразить код ",8,X)])])]),o.keyboard.length>0?(i(),d("div",W,[(i(!0),d(m,null,p(o.keyboard,(r,b)=>(i(),d("div",Z,[t("div",x,[t("button",{type:"button",class:"btn btn-link w-100",onClick:w=>a.addColToRow(b)},oe,8,ee),t("button",{type:"button",class:"btn btn-link w-100",onClick:w=>a.removeColFromRow(b)},ne,8,se),t("button",{type:"button",class:"btn btn-link w-100",onClick:w=>a.moveCol(b,0)},re,8,ie),t("button",{type:"button",class:"btn btn-link w-100",onClick:w=>a.moveCol(b,1)},he,8,ae)]),t("div",ue,[(i(!0),d(m,null,p(r,(w,f)=>(i(),d("div",{class:"btn-group dropdown-center w-100 m-1",onClick:C=>a.selectIndex(b,f)},[h(t("input",{type:"text",class:y([{"btn-outline-primary":o.select.row!=b||o.select.col!=f,"btn-primary":o.select.row==b&&o.select.col==f},"btn w-100"]),"onUpdate:modelValue":C=>o.keyboard[b][f].text=C},null,10,me),[[u,o.keyboard[b][f].text]]),t("button",{type:"button",onClick:e[7]||(e[7]=C=>o.mode=1),class:"btn btn-outline-primary","aria-expanded":"false"},pe)],8,be))),256))])]))),256))])):c("",!0)]),o.showCode?(i(),d("div",_e,[t("div",ke,[fe,o.load?c("",!0):(i(),_(k,{key:0,mode:"code",modelValue:o.keyboard,"onUpdate:modelValue":e[8]||(e[8]=r=>o.keyboard=r),"show-btns":!1,expandedOnStart:!0,onJsonChange:a.onJsonChange},null,8,["modelValue","onJsonChange"]))])])):c("",!0)])):c("",!0),o.mode===1?(i(),d("div",ve,[t("div",we,[t("div",ge,[t("button",{type:"button",onClick:e[9]||(e[9]=r=>o.mode=0),class:"btn btn-outline-primary"},"Назад ")]),t("form",Ce,[Fe,t("div",Re,[t("label",{for:"command-title-"+o.select.row+"-col-"+o.select.сol,class:"form-label"},"Название кнопки",8,Ke),h(t("input",{type:"text",id:"command-title-"+o.select.row+"-col-"+o.select.col,class:"form-control w-100","onUpdate:modelValue":e[10]||(e[10]=r=>o.keyboard[o.select.row][o.select.col].text=r)},null,8,$e),[[u,o.keyboard[o.select.row][o.select.col].text]])]),Te,t("div",Se,[t("label",{for:"command-row-"+o.select.row+"-col-"+o.select.col,class:"form-label"},"Команда (для меню в сообщении)",8,Be),h(t("input",{type:"text",onChange:e[11]||(e[11]=r=>a.needRemoveField("callback_data",o.select.row,o.select.col)),"onUpdate:modelValue":e[12]||(e[12]=r=>o.keyboard[o.select.row][o.select.col].callback_data=r),class:"form-control",id:"command-row-"+o.select.row+"-col-"+o.select.col,placeholder:"/start"},null,40,Ve),[[u,o.keyboard[o.select.row][o.select.col].callback_data]])]),t("div",je,[t("label",{for:"switch-inline-query-row-"+o.select.row+"-col-"+o.select.col,class:"form-label"},"Ссылка на аккаунт в ТЕЛЕГРАММ",8,qe),h(t("input",{type:"text",class:"form-control",onChange:e[13]||(e[13]=r=>a.needRemoveField("switch_inline_query",o.select.row,o.select.col)),"onUpdate:modelValue":e[14]||(e[14]=r=>o.keyboard[o.select.row][o.select.col].switch_inline_query=r),id:"switch-inline-query-row-"+o.select.row+"-col-"+o.select.col,placeholder:"@YourAccountLink"},null,40,Me),[[u,o.keyboard[o.select.row][o.select.col].switch_inline_query]])]),t("div",Ue,[t("label",{for:"url-row-"+o.select.row+"-col-"+o.select.col,class:"form-label"},"Внешняя URL-ссылка",8,Je),h(t("input",{type:"text",class:"form-control",onChange:e[15]||(e[15]=r=>a.needRemoveField("url",o.select.row,o.select.col)),"onUpdate:modelValue":e[16]||(e[16]=r=>o.keyboard[o.select.row][o.select.col].url=r),id:"url-row-"+o.select.row+"-col-"+s.colIndex,placeholder:"https://t.me/example"},null,40,Oe),[[u,o.keyboard[o.select.row][o.select.col].url]])]),t("div",Ne,[t("label",{for:"switch-inline-query-current-chat-row-"+o.select.row+"-col-"+o.select.col,class:"form-label"},"Команда всплывающего меню бота",8,Ae),h(t("input",{type:"text",class:"form-control",onChange:e[17]||(e[17]=r=>a.needRemoveField("switch_inline_query_current_chat",o.select.row,o.select.col)),"onUpdate:modelValue":e[18]||(e[18]=r=>o.keyboard[o.select.row][o.select.col].switch_inline_query_current_chat=r),id:"witch-inline-query-current-chat-row-"+o.select.row+"-col-"+o.select.col,placeholder:"команда"},null,40,De),[[u,o.keyboard[o.select.row][o.select.col].switch_inline_query_current_chat]])]),t("div",Ee,[t("input",{type:"radio",onChange:e[19]||(e[19]=r=>a.needRemoveField(null,o.select.row,o.select.col)),name:"request-radio",class:"form-check-input",id:"no-action-row-"+o.select.row+"-col-"+o.select.col},null,40,Pe),t("label",{class:"form-check-label",for:"no-action-row-"+o.select.row+"-col-"+o.select.col}," Без действий ",8,Le)]),t("div",Ie,[t("input",{type:"radio",onChange:e[20]||(e[20]=r=>a.needRemoveField("request_contact",o.select.row,o.select.col)),onClick:e[21]||(e[21]=r=>o.keyboard[o.select.row][o.select.col].request_contact=!0),name:"request-radio",class:"form-check-input",id:"phone-row-"+o.select.row+"-col-"+o.select.col},null,40,ze),t("label",{class:"form-check-label",for:"phone-row-"+o.select.row+"-col-"+o.select.col}," Запросить телефон (для нижнего меню) ",8,Ge)]),t("div",He,[t("input",{type:"radio",name:"request-radio",onChange:e[22]||(e[22]=r=>a.needRemoveField("request_location",o.select.row,o.select.col)),onClick:e[23]||(e[23]=r=>o.keyboard[o.select.row][o.select.col].request_location=!0),class:"form-check-input",id:"location-row-"+o.select.row+"-col-"+o.select.col},null,40,Ye),t("label",{class:"form-check-label",for:"location-row-"+o.select.row+"-col-"+o.select.col}," Запросить локацию (для нижнего меню) ",8,Qe)])])])])):c("",!0)],64)}const $=J(O,[["render",Xe]]);const We={key:0,class:"card"},Ze={key:0,class:"card-header d-flex justify-content-between align-items-center"},xe=["disabled"],et=t("i",{class:"fa-solid fa-arrow-left"},null,-1),tt=[et],ot=["disabled"],st=t("i",{class:"fa-regular fa-pen-to-square"},null,-1),lt=[st],nt=["disabled"],it=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),dt=[it],rt={key:1,class:"card-header d-flex justify-content-between align-items-center"},at={class:"mr-2"},ct=["disabled"],ht=t("i",{class:"fa-regular fa-pen-to-square"},null,-1),ut=[ht],bt=["disabled"],mt=t("i",{class:"fa-regular fa-clone"},null,-1),yt=[mt],pt=["disabled"],_t=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),kt=[_t],ft=["disabled"],vt=t("i",{class:"fa-solid fa-trash-can"},null,-1),wt=[vt],gt={class:"card-body"},Ct={key:0,class:"row"},Ft={class:"col-md-6 col-12"},Rt={class:"mb-3"},Kt=t("label",{class:"form-label",id:"bot-domain"},"Тип",-1),$t=t("option",{value:"reply"},"Нижняя клавиатура",-1),Tt=t("option",{value:"inline"},"Встроенная клавиатура",-1),St=[$t,Tt],Bt={class:"col-md-6 col-12"},Vt={class:"mb-3"},jt=t("label",{class:"form-label",id:"bot-domain"},"Мнемоническое имя",-1),qt={class:"row"},Mt={class:"col-12"},Ut={class:"row"},Jt={class:"col-12"},Ot={class:"row"},Nt={class:"col"},At={type:"button",class:"btn btn-outline-primary w-100 mb-2"},Dt={key:1,class:"card"},Et={class:"card-header d-flex justify-content-between align-items-center"},Pt=["disabled"],Lt=t("i",{class:"fa-regular fa-floppy-disk"},null,-1),It=[Lt],zt={class:"card-body"},Gt={props:["keyboard","selectMode"],data:()=>({is_edited:!1,load:!1,keyboardForm:null}),components:{Vue3JsonEditor:R},computed:{uuid(){return K()}},watch:{keyboardForm:{handler(s){this.is_edited=!0},deep:!0}},mounted(){const s=this.keyboard;this.keyboardForm=s,this.$nextTick(()=>{this.is_edited=!1,Array.isArray(s.menu)||this.updateKeyboard()})},methods:{onJsonChange(s){this.keyboardForm=s},removeKeyboard(){this.$store.dispatch("removeKeyboardTemplate",{templateId:this.keyboardForm.id}).then(s=>{this.load=!0,this.$nextTick(()=>{this.load=!1}),this.$emit("callback",this.keyboardForm.id)}).catch(()=>{this.$emit("callback",this.keyboardForm.id)})},saveKeyboard(s){this.keyboardForm.menu=s},selectCard(){this.$emit("select",this.keyboardForm)},duplicateKeyboard(){let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const l=this.keyboardForm[e]||"";typeof l=="object"?s.append(e,JSON.stringify(l)):s.append(e,l)}),this.$store.dispatch("createKeyboardTemplate",{keyboardForm:s}).then(e=>{this.$notify({title:"Конструктор ботов",text:"Меню успешно продублировано!",type:"success"}),this.load=!0,this.$nextTick(()=>{this.load=!1}),this.$emit("reload")})},updateKeyboard(){let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const l=this.keyboardForm[e]||"";typeof l=="object"?s.append(e,JSON.stringify(l)):s.append(e,l)}),this.$store.dispatch("editKeyboardTemplate",{keyboardForm:s}).then(e=>{this.load=!0,this.$nextTick(()=>{this.load=!1,this.is_edited=!1}),this.$emit("callback")}).catch(()=>{this.is_edited=!1,this.$emit("callback")})}}},Ht=Object.assign(Gt,{__name:"KeyboardCard",setup(s){return(e,l)=>(i(),d(m,null,[e.is_edited?c("",!0):(i(),d("div",We,[s.selectMode?(i(),d("div",Ze,[t("div",null,[t("button",{onClick:l[0]||(l[0]=(...n)=>e.selectCard&&e.selectCard(...n)),disabled:e.load,type:"button",class:"btn btn-outline-success mr-2"},tt,8,xe),t("button",{onClick:l[1]||(l[1]=n=>e.is_edited=!0),disabled:e.load,type:"button",title:"Редактировать клавиатуру",class:"btn btn-outline-success mr-2"},lt,8,ot),t("button",{onClick:l[2]||(l[2]=(...n)=>e.updateKeyboard&&e.updateKeyboard(...n)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},dt,10,nt)])])):c("",!0),s.selectMode?c("",!0):(i(),d("div",rt,[t("div",null,[t("strong",at,"#"+v(s.keyboard.id),1),t("button",{onClick:l[3]||(l[3]=n=>e.is_edited=!0),disabled:e.load,type:"button",title:"Редактировать клавиатуру",class:"btn btn-outline-success mr-2"},ut,8,ct),t("button",{onClick:l[4]||(l[4]=(...n)=>e.duplicateKeyboard&&e.duplicateKeyboard(...n)),type:"button",title:"Дублировать клавиатуру",disabled:e.load,class:"btn btn-outline-primary mr-2"},yt,8,bt),t("button",{onClick:l[5]||(l[5]=(...n)=>e.updateKeyboard&&e.updateKeyboard(...n)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},kt,10,pt)]),t("button",{onClick:l[6]||(l[6]=(...n)=>e.removeKeyboard&&e.removeKeyboard(...n)),type:"button",disabled:e.load,title:"Удалить клавиатуру",class:"btn btn-outline-danger mr-2"},wt,8,ft)])),t("div",gt,[s.selectMode?c("",!0):(i(),d("div",Ct,[t("div",Ft,[t("div",Rt,[Kt,h(t("select",{disabled:!0,"onUpdate:modelValue":l[7]||(l[7]=n=>s.keyboard.type=n),class:"form-control"},St,512),[[T,s.keyboard.type]])])]),t("div",Bt,[t("div",Vt,[jt,h(t("input",{type:"text",class:"form-control",placeholder:"Мнемоническое имя",disabled:!0,"aria-label":"Мнемоническое имя","onUpdate:modelValue":l[8]||(l[8]=n=>s.keyboard.slug=n),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[u,s.keyboard.slug]])])])])),t("div",qt,[t("div",Mt,[t("div",Ut,[t("div",Jt,[(i(!0),d(m,null,p(s.keyboard.menu,(n,o)=>(i(),d("div",Ot,[(i(!0),d(m,null,p(n,(a,k)=>(i(),d("div",Nt,[t("button",At,v(a.text),1)]))),256))]))),256))])])])])])])),e.is_edited?(i(),d("div",Dt,[t("div",Et,[t("button",{onClick:l[9]||(l[9]=(...n)=>e.updateKeyboard&&e.updateKeyboard(...n)),type:"button",title:"Обновить клавиатуру",disabled:e.load||!e.is_edited,class:y(["btn btn-outline-primary mr-2",{"have-change":e.is_edited}])},It,10,Pt)]),t("div",zt,[e.keyboardForm?(i(),_($,{key:0,onSave:e.saveKeyboard,"edited-keyboard":e.keyboardForm},null,8,["onSave","edited-keyboard"])):c("",!0),S(q(R),{mode:"code",modelValue:e.keyboardForm,"onUpdate:modelValue":l[10]||(l[10]=n=>e.keyboardForm=n),"show-btns":!1,expandedOnStart:!0,onJsonChange:e.onJsonChange},null,8,["modelValue","onJsonChange"])])])):c("",!0)],64))}}),Yt={key:0,class:"row mb-2 py-3"},Qt={class:"col-12"},Xt={class:"card"},Wt=t("div",{class:"card-header"},[t("h6",null,"Форма создания шаблона клавиатуры")],-1),Zt={class:"card-body"},xt={class:"col-md-6 col-12"},eo={class:"mb-3"},to=t("label",{class:"form-label",id:"bot-domain"},"Тип",-1),oo=t("option",{value:"reply"},"Нижняя клавиатура",-1),so=t("option",{value:"inline"},"Встроенная клавиатура",-1),lo=[oo,so],no={class:"col-md-6 col-12"},io={class:"mb-3"},ro={class:"form-label d-flex justify-content-between",id:"bot-domain"},ao=t("span",null,"Мнемоническое имя",-1),co=t("i",{class:"fa-solid fa-arrows-rotate"},null,-1),ho=[co],uo={class:"col-12"},bo=t("div",{class:"col-12"},[t("button",{class:"btn btn-outline-success w-100 p-3"}," Добавить новый шаблон клавиатуры ")],-1),mo={class:"row"},yo={key:0,class:"col-12"},po={class:"badge bg-warning"},_o={class:"col-12 mb-3"},ko={key:1,class:"card"},fo={class:"card-body"},vo={key:2,class:"col-12 mb-3"},wo=t("div",{class:"alert alert-warning",role:"alert"},[t("p",null,"Список шаблонов клавиатур пуст!")],-1),go=[wo],Co={props:["selectMode","type"],data(){return{keyboards:[],load:!1,bot:null,editedKeyboard:null,selectMenuIndex:null,keyboardForm:{type:"reply",bot_id:null,slug:null,menu:[]}}},computed:{...B(["getCurrentBot"]),filteredKeyboard(){return this.type?this.keyboards.filter(s=>s.type==this.type):this.keyboards}},mounted(){this.loadCurrentBot().then(()=>{this.loadMenusByBotTemplate(),this.generateSlug()})},methods:{selectCard(s){this.$emit("select",s)},keyboardCallbackAction(s){let e=this.keyboards.find(l=>l.id===s);e&&(e.deleted_at=new Date),this.loadMenusByBotTemplate()},loadMenusByBotTemplate(){this.$store.dispatch("loadBotKeyboards",{botId:this.bot.id}).then(s=>{this.keyboards=s.data})},loadCurrentBot(s=null){return this.$store.dispatch("updateCurrentBot",{bot:s}).then(()=>{this.bot=this.getCurrentBot})},submitKeyboard(){this.keyboardForm.bot_id=this.bot.id;let s=new FormData;Object.keys(this.keyboardForm).forEach(e=>{const l=this.keyboardForm[e]||"";typeof l=="object"?s.append(e,JSON.stringify(l)):s.append(e,l)}),this.$store.dispatch("createKeyboardTemplate",{keyboardForm:s}).then(e=>{this.keyboardForm={type:"reply",bot_id:null,slug:null,menu:[]},this.load=!0,this.$nextTick(()=>{this.load=!1}),this.loadMenusByBotTemplate()})},generateSlug(){this.keyboardForm.slug=K()},changeKeyboardFormMenu(s){this.keyboardForm.menu=s}}},Fo=Object.assign(Co,{__name:"KeyboardList",setup(s){return(e,l)=>(i(),d(m,null,[s.selectMode?c("",!0):(i(),d("div",Yt,[t("div",Qt,[t("div",Xt,[Wt,t("div",Zt,[t("form",{onSubmit:l[3]||(l[3]=V((...n)=>e.submitKeyboard&&e.submitKeyboard(...n),["prevent"])),class:"row"},[t("div",xt,[t("div",eo,[to,h(t("select",{"onUpdate:modelValue":l[0]||(l[0]=n=>e.keyboardForm.type=n),class:"form-control"},lo,512),[[T,e.keyboardForm.type]])])]),t("div",no,[t("div",io,[t("label",ro,[ao,t("a",{onClick:l[1]||(l[1]=(...n)=>e.generateSlug&&e.generateSlug(...n)),href:"#generate"},ho)]),h(t("input",{type:"text",class:"form-control",placeholder:"Мнемоническое имя","aria-label":"Мнемоническое имя","onUpdate:modelValue":l[2]||(l[2]=n=>e.keyboardForm.slug=n),maxlength:"255","aria-describedby":"bot-domain",required:""},null,512),[[u,e.keyboardForm.slug]])])]),t("div",uo,[e.load?c("",!0):(i(),_($,{key:0,onSave:e.changeKeyboardFormMenu,"edited-keyboard":e.keyboardForm},null,8,["onSave","edited-keyboard"]))]),bo],32)])])])])),t("div",mo,[e.keyboards&&e.bot?(i(),d("div",yo,[t("p",null,[g("В списке клавиатур "),t("span",po,v(e.filteredKeyboard.length)+" ед.",1)])])):c("",!0),e.keyboards&&e.bot?(i(!0),d(m,{key:1},p(e.filteredKeyboard,(n,o)=>(i(),d("div",_o,[n.deleted_at?(i(),d("div",ko,[t("div",fo,[t("p",null,"Удаленная клавиатура #"+v(n.id),1)])])):(i(),_(Ht,{key:0,"select-mode":s.selectMode,onSelect:e.selectCard,onReload:e.loadMenusByBotTemplate,onCallback:e.keyboardCallbackAction,keyboard:n},null,8,["select-mode","onSelect","onReload","onCallback","keyboard"]))]))),256)):c("",!0),e.filteredKeyboard.length===0?(i(),d("div",vo,go)):c("",!0)])],64))}}),Ro={class:"col-md-12 col-12"},Ko={class:"mb-3"},$o=t("i",{class:"fa-solid fa-xmark"},null,-1),To=[$o],So={class:"mb-3"},Bo={class:"d-flex justify-content-between flex-wrap al"},Vo=t("label",{class:"form-label",id:"bot-main-channel"},"Канал для постов (id,рекламный)",-1),jo={class:"d-flex flex-wrap align-items-center"},qo=t("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),Mo={class:"col-12 mb-2"},Uo=t("label",{class:"form-label d-flex justify-content-between align-items-center mb-2",id:"bot-domain"},[g(" Текстовое содержимое страницы "),t("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно")],-1),Jo={class:"form-floating"},Oo={for:"floatingTextarea2"},No={key:0},Ao={class:"col-12 mb-2"},Do={class:"form-check"},Eo=t("label",{class:"form-check-label",for:"need-page-images"}," Изображения на странице (максимум 10) ",-1),Po={key:0,class:"col-12 mb-2"},Lo={class:"card mb-3"},Io=t("div",{class:"card-header"},[t("h6",null,"Изображения на странице")],-1),zo={class:"card-body d-flex justify-content-start"},Go={for:"photos",style:{"margin-right":"10px"},class:"photo-loader ml-2"},Ho={class:"row"},Yo={key:0,class:"col-12 d-flex flex-wrap"},Qo={class:"mb-2 img-preview",style:{"margin-right":"10px"}},Xo={class:"remove"},Wo=["onClick"],Zo={class:"col-12 mb-2"},xo={class:"form-check"},es=t("label",{class:"form-check-label",for:"need-inline-menu"}," Меню под текстом страницы ",-1),ts={key:1,class:"col-12 mb-2"},os={class:"card"},ss={class:"card-header d-flex justify-between align-items-center"},ls=t("h6",null,"Конструктор меню в сообщении",-1),ns={key:0},is={key:1},ds={class:"card-body"},rs=t("div",{class:"col-12"},[t("button",{type:"submit",class:"btn btn-success w-100 p-3"}," Отправить сообщение в канал ")],-1),as={data(){return{load:!1,photos:[],showInlineTemplateSelector:!1,need_page_images:!1,need_inline_menu:!1,bot:null,mailForm:{text:"",inline_keyboard:null,channel:null}}},computed:{...B(["getCurrentBot"])},mounted(){this.loadCurrentBot()},methods:{addTextTo(s={param:null,text:null}){this.mailForm.channel=s.text},loadCurrentBot(s=null){return this.$store.dispatch("updateCurrentBot",{bot:s}).then(()=>{this.bot=this.getCurrentBot})},submitMail(){let s=new FormData;if(Object.keys(this.mailForm).forEach(e=>{const l=this.mailForm[e]||"";typeof l=="object"?s.append(e,JSON.stringify(l)):s.append(e,l)}),this.bot&&s.append("bot_id",this.bot.id),this.photos.length>0)for(let e=0;e<this.photos.length;e++)s.append("photos[]",this.photos[e]);this.$store.dispatch("sendToChannel",{mailForm:s}).then(e=>{this.load=!0,this.photos=[],this.need_inline_menu=!1,this.need_page_images=!1,this.mailForm={text:"",channel:null,inline_keyboard:null},this.$notify({title:"Конструктор ботов",text:"Сообщение успещно отправлено!",type:"success"})}).catch(e=>{})},saveInlineKeyboard(s){this.mailForm.inline_keyboard=s},selectInlineKeyboard(s){this.mailForm.inline_keyboard=s,this.showInlineTemplateSelector=!1},getPhoto(s){return{imageUrl:URL.createObjectURL(s)}},removePhoto(s){this.photos.splice(s,1)},removeImage(s){this.mailForm.images.splice(s,1)},onChangePhotos(s){const e=s.target.files;for(let l=0;l<e.length;l++)this.photos.push(e[l])}}},ms=Object.assign(as,{__name:"Mail",setup(s){return(e,l)=>{const n=M("lazy");return e.bot?(i(),d("form",{key:0,onSubmit:l[9]||(l[9]=V((...o)=>e.submitMail&&e.submitMail(...o),["prevent"]))},[t("div",Ro,[t("div",Ko,[e.mailForm.channel!=null?(i(),d("button",{key:0,type:"button",class:"btn btn-outline-info mr-2",onClick:l[0]||(l[0]=o=>e.mailForm.channel=null)},To)):c("",!0),t("button",{type:"button",class:y(["btn mr-2",{"btn-info":e.mailForm.channel===e.bot.main_channel,"btn-outline-info":e.mailForm.channel!==e.bot.main_channel}]),onClick:l[1]||(l[1]=o=>e.mailForm.channel=e.bot.main_channel)},"Главный канал ",2),t("button",{type:"button",class:y(["btn",{"btn-info":e.mailForm.channel===e.bot.order_channel,"btn-outline-info":e.mailForm.channel!==e.bot.order_channel}]),onClick:l[2]||(l[2]=o=>e.mailForm.channel=e.bot.order_channel)},"Канал заказов ",2)]),t("div",So,[t("div",Bo,[Vo,t("div",jo,[S(U,{token:e.bot.bot_token,param:"channel",onCallback:e.addTextTo},null,8,["token","onCallback"]),qo])]),h(t("input",{type:"text",class:"form-control",placeholder:"id канала","aria-label":"id канала","onUpdate:modelValue":l[3]||(l[3]=o=>e.mailForm.channel=o),maxlength:"255","aria-describedby":"bot-main-channel",required:""},null,512),[[u,e.mailForm.channel]])])]),t("div",Mo,[Uo,t("div",Jo,[h(t("textarea",{class:"form-control","onUpdate:modelValue":l[4]||(l[4]=o=>e.mailForm.text=o),maxlength:"4096",placeholder:"Введите текст",id:"floatingTextarea2",style:{"min-height":"100px"},required:""},null,512),[[u,e.mailForm.text]]),t("label",Oo,[g("Содержимое страницы "),e.mailForm.text?(i(),d("span",No,v(e.mailForm.text.length)+"/4096 ",1)):c("",!0)])])]),t("div",Ao,[t("div",Do,[h(t("input",{class:"form-check-input","onUpdate:modelValue":l[5]||(l[5]=o=>e.need_page_images=o),type:"checkbox",id:"need-page-images"},null,512),[[F,e.need_page_images]]),Eo])]),e.need_page_images?(i(),d("div",Po,[t("div",Lo,[Io,t("div",zo,[t("label",Go,[g(" + "),t("input",{type:"file",id:"photos",multiple:"",accept:"image/*",onChange:l[6]||(l[6]=(...o)=>e.onChangePhotos&&e.onChangePhotos(...o)),style:{display:"none"}},null,32)]),t("div",Ho,[e.photos.length>0?(i(),d("div",Yo,[(i(!0),d(m,null,p(e.photos,(o,a)=>(i(),d("div",Qo,[h(t("img",null,null,512),[[n,e.getPhoto(o).imageUrl]]),t("div",Xo,[t("a",{onClick:k=>e.removePhoto(a)},"Удалить",8,Wo)])]))),256))])):c("",!0)])])])])):c("",!0),t("div",Zo,[t("div",xo,[h(t("input",{class:"form-check-input","onUpdate:modelValue":l[7]||(l[7]=o=>e.need_inline_menu=o),type:"checkbox",id:"need-inline-menu"},null,512),[[F,e.need_inline_menu]]),es])]),e.need_inline_menu?(i(),d("div",ts,[t("div",os,[t("div",ss,[ls,t("button",{class:y(["btn",{"btn-outline-primary":!e.showInlineTemplateSelector,"btn-primary":e.showInlineTemplateSelector}]),type:"button",onClick:l[8]||(l[8]=o=>e.showInlineTemplateSelector=!e.showInlineTemplateSelector)},[e.showInlineTemplateSelector?(i(),d("span",is," Скрыть шаблоны меню")):(i(),d("span",ns," Открыть шаблоны меню"))],2)]),t("div",ds,[e.showInlineTemplateSelector?(i(),_(Fo,{key:0,class:"mb-2",type:"inline",onSelect:e.selectInlineKeyboard,"select-mode":!0},null,8,["onSelect"])):(i(),_($,{key:1,type:"inline",onSave:e.saveInlineKeyboard,"edited-keyboard":e.mailForm.inline_keyboard},null,8,["onSave","edited-keyboard"]))])])])):c("",!0),rs],32)):c("",!0)}}});export{$ as B,Fo as _,ms as a};
