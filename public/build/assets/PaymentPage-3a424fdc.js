import{_ as u}from"./MainAdminLayout-f31768c0.js";import"./BotForm-63fc0cac.js";import"./ManagerPrfileForm.vue_vue_type_style_index_0_scoped_5b8b41af_lang-e5c56385.js";import{G as f,q as s,t as y,H as r,J as a,v as o,P as d,K as c,O as k,B as _}from"./index.es-87f808e1.js";import{_ as F}from"./_plugin-vue_export-helper-c27b6911.js";/* empty css                                                 */import"./CompanyList-003f3c1c.js";import"./Pagination-221154ef.js";import"./TelegramChannelHelper-d590d406.js";const v={data(){return{bot:null,paymentForm:{terminalkey:"TinkoffBankTest",frame:!1,language:"ru",description:null,amount:0,order:null,receipt:null,name:null,email:null,phone:null}}},computed:{...f(["getCurrentBot"])},mounted(){this.loadCurrentBot(),this.paymentForm.terminalkey="1708340156876DEMO";let m=document.createElement("script");m.setAttribute("src","https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"),document.head.appendChild(m)},methods:{submit(){const m=document.getElementById("payform-tinkoff"),{description:e,amount:l,email:i,phone:t,receipt:p}=m;if(p){if(!i.value&&!t.value)return alert("Поле E-mail или Phone не должно быть пустым");m.receipt.value=JSON.stringify({EmailCompany:"cashman.2024@mail.ru",Taxation:"patent",Items:[{Name:e.value||"Оплата",Price:l.value+"00",Quantity:1,Amount:l.value+"00",PaymentMethod:"full_prepayment",PaymentObject:"service",Tax:"none"}]})}pay(m)},loadCurrentBot(m=null){this.$store.dispatch("updateCurrentBot",{bot:m}).then(()=>{this.bot=this.getCurrentBot})}}},h=o("input",{class:"payform-tinkoff-row payform-tinkoff-btn",type:"submit",value:"Оплатить"},null,-1);function g(m,e,l,i,t,p){return s(),y("form",{class:"payform-tinkoff",onSubmit:e[10]||(e[10]=d((...n)=>p.submit&&p.submit(...n),["prevent"])),name:"payform-tinkoff",id:"payform-tinkoff"},[r(o("input",{class:"payform-tinkoff-row",type:"hidden","onUpdate:modelValue":e[0]||(e[0]=n=>t.paymentForm.terminalkey=n),name:"terminalkey"},null,512),[[a,t.paymentForm.terminalkey]]),r(o("input",{class:"payform-tinkoff-row",type:"hidden","onUpdate:modelValue":e[1]||(e[1]=n=>t.paymentForm.frame=n),name:"frame"},null,512),[[a,t.paymentForm.frame]]),r(o("input",{class:"payform-tinkoff-row",type:"hidden","onUpdate:modelValue":e[2]||(e[2]=n=>t.paymentForm.language=n),name:"language"},null,512),[[a,t.paymentForm.language]]),r(o("input",{class:"payform-tinkoff-row",type:"hidden","onUpdate:modelValue":e[3]||(e[3]=n=>t.paymentForm.receipt=n),name:"receipt"},null,512),[[a,t.paymentForm.receipt]]),r(o("input",{class:"payform-tinkoff-row",type:"text","onUpdate:modelValue":e[4]||(e[4]=n=>t.paymentForm.amount=n),placeholder:"Сумма заказа",name:"amount",required:""},null,512),[[a,t.paymentForm.amount]]),r(o("input",{class:"payform-tinkoff-row",type:"hidden","onUpdate:modelValue":e[5]||(e[5]=n=>t.paymentForm.order=n),placeholder:"Номер заказа",name:"order"},null,512),[[a,t.paymentForm.order]]),r(o("input",{class:"payform-tinkoff-row",type:"text","onUpdate:modelValue":e[6]||(e[6]=n=>t.paymentForm.description=n),placeholder:"Описание заказа",name:"description"},null,512),[[a,t.paymentForm.description]]),r(o("input",{class:"payform-tinkoff-row",type:"text","onUpdate:modelValue":e[7]||(e[7]=n=>t.paymentForm.name=n),placeholder:"ФИО плательщика",name:"name"},null,512),[[a,t.paymentForm.name]]),r(o("input",{class:"payform-tinkoff-row",type:"email","onUpdate:modelValue":e[8]||(e[8]=n=>t.paymentForm.email=n),placeholder:"E-mail",name:"email"},null,512),[[a,t.paymentForm.email]]),r(o("input",{class:"payform-tinkoff-row",type:"tel","onUpdate:modelValue":e[9]||(e[9]=n=>t.paymentForm.phone=n),placeholder:"Контактный телефон",name:"phone"},null,512),[[a,t.paymentForm.phone]]),h],32)}const w=F(v,[["render",g]]);const b={class:"container"},x={class:"row mb-2"},B={class:"col-12"},P={data(){return{}},computed:{},mounted(){},methods:{}},S=Object.assign(P,{__name:"PaymentPage",setup(m){return(e,l)=>(s(),c(u,{active:7,"need-menu":!1},{default:k(()=>[o("div",b,[o("div",x,[o("div",B,[_(w)])])])]),_:1}))}});export{S as default};
