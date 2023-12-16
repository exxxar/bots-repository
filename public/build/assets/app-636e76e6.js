import{a as l,c as P,E as b,m as h,r as p,b as x,h as d,V as f,d as O,e as S,I as E,i as j,_ as g,f as k}from"./index.es-77235f88.js";import{i as v}from"./vue3-perfect-scrollbar-334a543e.js";window.axios=l;window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const n={async makeAxiosFactory(e,t="GET",r=null,a=null){let s;switch(t.toUpperCase()){default:case"GET":s=await l.get(e);break;case"POST":s=await l.post(e,r,a);break;case"PUT":s=await l.put(e,r);break;case"DELETE":s=await l.delete(e);break}return s}},i="/landing";let T={self:null};const y={getSelf:e=>e.self||null},$={async loadSelf(e){let t=`${i}/self`,r="POST";return n.makeAxiosFactory(t,r).then(s=>{let o=s.data;return e.commit("setSelf",o.data),Promise.resolve(o)}).catch(s=>(e.commit("setErrors",s.response.data.errors||[]),Promise.reject(s)))},async loadManagerData(e){let t=`${i}/manager/load-data`;return n.makeAxiosFactory(t,"POST").then(a=>Promise.resolve(a.data)).catch(a=>(e.commit("setErrors",a.response.data.errors||[]),Promise.reject(a)))},async loadFriendsWeb(e){let t=`${i}/manager/friends-web`;return n.makeAxiosFactory(t,"POST").then(a=>Promise.resolve(a.data)).catch(a=>(e.commit("setErrors",a.response.data.errors||[]),Promise.reject(a)))},async saveManager(e,t){({...t});let r=`${i}/manager/register`;return n.makeAxiosFactory(r,"POST",t).then(s=>Promise.resolve(s.data)).catch(s=>(e.commit("setErrors",s.response.data.errors||[]),Promise.reject(s)))},async loadBotManagerConfig(e,t={botId:null}){console.log("payload",t);let r=`${i}/manage-bot`,a="POST";return n.makeAxiosFactory(r,a,t).then(o=>{let m=o.data;return Promise.resolve(m)}).catch(o=>(e.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async loadBotAdminConfig(e){let t=`${i}/bot`,r="POST";return n.makeAxiosFactory(t,r).then(s=>{let o=s.data;return Promise.resolve(o)}).catch(s=>(e.commit("setErrors",s.response.data.errors||[]),Promise.reject(s)))}},w={setSelf(e,t){e.self=t||[],localStorage.setItem("cashman_self",JSON.stringify(t))}},A={state:T,mutations:w,actions:$,getters:y},B="/landing/bots";let F={bots:[],bots_paginate_object:null};const I={getBots:e=>e.bots||[],getBotsPaginateObject:e=>e.bots_paginate_object||null},L={async loadSimpleBots(e,t={dataObject:null,page:0,size:50}){let r=t.page||0,a=t.size||50,s=`${B}/simple-bot-list?page=${r}&size=${a}`,o="POST",m=t.dataObject;return n.makeAxiosFactory(s,o,m).then(c=>{let u=c.data;return e.commit("setBots",u.data),delete u.data,e.commit("setBotsPaginateObject",u),Promise.resolve()}).catch(c=>(e.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))}},M={setBots(e,t){e.bots=t||[],localStorage.setItem("cashman_landing_bots",JSON.stringify(t))},setBotsPaginateObject(e,t){e.bots_paginate_object=t||[],localStorage.setItem("cashman_landing_bots_paginate_object",JSON.stringify(t))}},N={state:F,mutations:M,actions:L,getters:I},C=P({state:{errors:[]},getters:{getErrors:e=>e.errors||[]},actions:{async sendToChannel(e,t={mailForm:null}){let r="/landing/send-to-channel";return n.makeAxiosFactory(r,"POST",t.mailForm).then(s=>Promise.resolve(s.data)).catch(s=>(e.commit("setErrors",s.response.data.errors||[]),Promise.reject(s)))}},mutations:{setErrors(e,t){e.errors=t||[]}},modules:{self:A,bots:N}}),D=h();window.eventBus=D;var _;const z=((_=window.document.getElementsByTagName("title")[0])==null?void 0:_.innerText)||"Laravel";b({title:e=>`${e} - ${z}`,resolve:e=>p(`./Pages/${e}.vue`,Object.assign({"./Pages/ChatWindow.vue":()=>g(()=>import("./ChatWindow-f141075b.js"),["assets/ChatWindow-f141075b.js","assets/index.es-77235f88.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/ChatWindow-32e0cc68.css"]),"./Pages/LandingPage.vue":()=>g(()=>import("./LandingPage-59be2d28.js"),["assets/LandingPage-59be2d28.js","assets/index.es-77235f88.js","assets/LandingPage-0cce3258.css"])})),setup({el:e,App:t,props:r,plugin:a}){const s=x({render:()=>k(t,r)});return s.config.globalProperties.$filters={timeAgo(o){return d(o).fromNow()},current(o){return d(o).format("YYYY-MM-DD")}},s.use(a).use(f).use(O).use(S).use(C).use(v).use(E,Ziggy).use(j,{loading:"/images/cashman.jpg",error:"/images/error.png"}).mount(e),s},progress:{color:"#4B5563"}});
