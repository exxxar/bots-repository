import{bH as f,x as m,aF as i,y as g,bw as s,P as o,bc as e,bI as y,A as p,z as _,D as a,bB as h,ap as b,O as r,bJ as x}from"./index.es-cc022547.js";import{P as k,_ as v}from"./PrimaryButton-bbab1ec3.js";import"./_plugin-vue_export-helper-c27b6911.js";const w=a("div",{class:"mb-4 text-sm text-gray-600 dark:text-gray-400"}," Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1),B={key:0,class:"mb-4 font-medium text-sm text-green-600 dark:text-green-400"},V=["onSubmit"],E={class:"mt-4 flex items-center justify-between"},j={__name:"VerifyEmail",props:{status:{type:String}},setup(n){const c=n,t=f({}),d=()=>{t.post(route("verification.send"))},u=m(()=>c.status==="verification-link-sent");return(l,N)=>(i(),g(v,null,{default:s(()=>[o(e(y),{title:"Email Verification"}),w,e(u)?(i(),p("div",B," A new verification link has been sent to the email address you provided during registration. ")):_("",!0),a("form",{onSubmit:h(d,["prevent"])},[a("div",E,[o(k,{class:b({"opacity-25":e(t).processing}),disabled:e(t).processing},{default:s(()=>[r(" Resend Verification Email ")]),_:1},8,["class","disabled"]),o(e(x),{href:l.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"},{default:s(()=>[r("Log Out")]),_:1},8,["href"])])],40,V)]),_:1}))}};export{j as default};
