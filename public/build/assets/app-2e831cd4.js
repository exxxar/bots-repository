import{a as m,E as g,m as d,r as p,c as _,h as t,V as l,b as c,e as v,I as w,i as P,_ as o,d as h}from"./index.es-ff2198a1.js";import{i as f}from"./vue3-perfect-scrollbar-a450fa7a.js";window.axios=m;window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const x=d();window.eventBus=x;var r;const E=((r=window.document.getElementsByTagName("title")[0])==null?void 0:r.innerText)||"Laravel";g({title:e=>`${e} - ${E}`,resolve:e=>p(`./Pages/${e}.vue`,Object.assign({"./Pages/ChatWindow.vue":()=>o(()=>import("./ChatWindow-3d5dc5e2.js"),["assets/ChatWindow-3d5dc5e2.js","assets/index.es-ff2198a1.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/ChatWindow-dadc2ae3.css"]),"./Pages/LandingPage.vue":()=>o(()=>import("./LandingPage-1bb998b7.js"),["assets/LandingPage-1bb998b7.js","assets/index.es-ff2198a1.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/LandingPage-9e73c227.css"])})),setup({el:e,App:i,props:n,plugin:u}){const s=_({render:()=>h(i,n)});return s.config.globalProperties.$filters={timeAgo(a){return t(a).fromNow()},current(a){return t(a).format("YYYY-MM-DD")}},s.use(u).use(l).use(c).use(v).use(f).use(w,Ziggy).use(P,{loading:"/images/cashman.jpg",error:"/images/error.png"}).mount(e),s},progress:{color:"#4B5563"}});
