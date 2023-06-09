import{A as S}from"./ApplicationLogo-c4390a13.js";import{H as B,J as E,p as y,K as L,o as l,c as h,a as e,y as g,w,L as $,b as s,l as a,n as c,u as n,T as D,e as _,x as v,f as u,t as b,d as M,F as N,X as V}from"./app-201843e4.js";import j from"./DeleteUserForm-1cc939e8.js";import z from"./UpdatePasswordForm-50dd211c.js";import P from"./UpdateProfileInformationForm-7fd786d7.js";import"./TextInput-f7930074.js";import"./PrimaryButton-7ffbd7cf.js";const q={class:"relative"},A={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"48"},contentClasses:{type:String,default:"py-1 bg-white dark:bg-gray-700"}},setup(o){const r=o,t=p=>{d.value&&p.key==="Escape"&&(d.value=!1)};B(()=>document.addEventListener("keydown",t)),E(()=>document.removeEventListener("keydown",t));const i=y(()=>({48:"w-48"})[r.width.toString()]),m=y(()=>r.align==="left"?"origin-top-left left-0":r.align==="right"?"origin-top-right right-0":"origin-top"),d=L(!1);return(p,f)=>(l(),h("div",q,[e("div",{onClick:f[0]||(f[0]=k=>d.value=!d.value)},[g(p.$slots,"trigger")]),w(e("div",{class:"fixed inset-0 z-40",onClick:f[1]||(f[1]=k=>d.value=!1)},null,512),[[$,d.value]]),s(D,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:a(()=>[w(e("div",{class:c(["absolute z-50 mt-2 rounded-md shadow-lg",[n(i),n(m)]]),style:{display:"none"},onClick:f[2]||(f[2]=k=>d.value=!1)},[e("div",{class:c(["rounded-md ring-1 ring-black ring-opacity-5",o.contentClasses])},[g(p.$slots,"content")],2)],2),[[$,d.value]])]),_:3})]))}},C={__name:"DropdownLink",props:{href:{type:String,required:!0}},setup(o){return(r,t)=>(l(),_(n(v),{href:o.href,class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"},{default:a(()=>[g(r.$slots,"default")]),_:3},8,["href"]))}},O={__name:"NavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(o){const r=o,t=y(()=>r.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out");return(i,m)=>(l(),_(n(v),{href:o.href,class:c(n(t))},{default:a(()=>[g(i.$slots,"default")]),_:3},8,["href","class"]))}},x={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(o){const r=o,t=y(()=>r.active?"block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-left text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out":"block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out");return(i,m)=>(l(),_(n(v),{href:o.href,class:c(n(t))},{default:a(()=>[g(i.$slots,"default")]),_:3},8,["href","class"]))}},T={class:"min-h-screen bg-gray-100 dark:bg-gray-900"},F={class:"bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"},H={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},J={class:"flex justify-between h-16"},K={class:"flex"},R={class:"shrink-0 flex items-center"},U={class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},X={class:"hidden sm:flex sm:items-center sm:ml-6"},G={class:"ml-3 relative"},I={class:"inline-flex rounded-md"},Q={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"},W=e("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),Y={class:"-mr-2 flex items-center sm:hidden"},Z={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},ee={class:"pt-2 pb-3 space-y-1"},te={class:"pt-4 pb-1 border-t border-gray-200 dark:border-gray-600"},re={class:"px-4"},se={class:"font-medium text-base text-gray-800 dark:text-gray-200"},ae={class:"font-medium text-sm text-gray-500"},oe={class:"mt-3 space-y-1"},ne={key:0,class:"bg-white dark:bg-gray-800 shadow"},ie={class:"max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"},de={__name:"AuthenticatedLayout",setup(o){const r=L(!1);return(t,i)=>(l(),h("div",null,[e("div",T,[e("nav",F,[e("div",H,[e("div",J,[e("div",K,[e("div",R,[s(n(v),{href:t.route("dashboard")},{default:a(()=>[s(S,{class:"block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"})]),_:1},8,["href"])]),e("div",U,[s(O,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:a(()=>[u(" Dashboard ")]),_:1},8,["href","active"])])]),e("div",X,[e("div",G,[s(A,{align:"right",width:"48"},{trigger:a(()=>[e("span",I,[e("button",Q,[u(b(t.$page.props.auth.user.name)+" ",1),W])])]),content:a(()=>[s(C,{href:t.route("profile.edit")},{default:a(()=>[u(" Profile ")]),_:1},8,["href"]),s(C,{href:t.route("logout"),method:"post",as:"button"},{default:a(()=>[u(" Log Out ")]),_:1},8,["href"])]),_:1})])]),e("div",Y,[e("button",{onClick:i[0]||(i[0]=m=>r.value=!r.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"},[(l(),h("svg",Z,[e("path",{class:c({hidden:r.value,"inline-flex":!r.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:c({hidden:!r.value,"inline-flex":r.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:c([{block:r.value,hidden:!r.value},"sm:hidden"])},[e("div",ee,[s(x,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:a(()=>[u(" Dashboard ")]),_:1},8,["href","active"])]),e("div",te,[e("div",re,[e("div",se,b(t.$page.props.auth.user.name),1),e("div",ae,b(t.$page.props.auth.user.email),1)]),e("div",oe,[s(x,{href:t.route("profile.edit")},{default:a(()=>[u(" Profile ")]),_:1},8,["href"]),s(x,{href:t.route("logout"),method:"post",as:"button"},{default:a(()=>[u(" Log Out ")]),_:1},8,["href"])])])],2)]),t.$slots.header?(l(),h("header",ne,[e("div",ie,[g(t.$slots,"header")])])):M("",!0),e("main",null,[g(t.$slots,"default")])])]))}},le=e("h2",{class:"font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"},"Profile",-1),ue={class:"py-12"},ce={class:"max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"},ge={class:"p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"},fe={class:"p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"},he={class:"p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"},ke={__name:"Edit",props:{mustVerifyEmail:{type:Boolean},status:{type:String}},setup(o){return(r,t)=>(l(),h(N,null,[s(n(V),{title:"Profile"}),s(de,null,{header:a(()=>[le]),default:a(()=>[e("div",ue,[e("div",ce,[e("div",ge,[s(P,{"must-verify-email":o.mustVerifyEmail,status:o.status,class:"max-w-xl"},null,8,["must-verify-email","status"])]),e("div",fe,[s(z,{class:"max-w-xl"})]),e("div",he,[s(j,{class:"max-w-xl"})])])])]),_:1})],64))}};export{ke as default};
