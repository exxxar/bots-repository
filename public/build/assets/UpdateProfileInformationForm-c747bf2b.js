import{Q as x,k as v,o as l,c as n,a as s,b as a,u as e,e as m,l as d,w as _,O as k,d as f,T as h,g as V,x as b}from"./app-96ea2287.js";import{_ as p,a as g,b as y}from"./TextInput-fa8527cc.js";import{P as w}from"./PrimaryButton-3d62b28a.js";const S=s("header",null,[s("h2",{class:"text-lg font-medium text-gray-900 dark:text-gray-100"},"Profile Information"),s("p",{class:"mt-1 text-sm text-gray-600 dark:text-gray-400"}," Update your account's profile information and email address. ")],-1),B={key:0},N={class:"text-sm mt-2 text-gray-800 dark:text-gray-200"},E={class:"mt-2 font-medium text-sm text-green-600 dark:text-green-400"},P={class:"flex items-center gap-4"},U={key:0,class:"text-sm text-gray-600 dark:text-gray-400"},q={__name:"UpdateProfileInformationForm",props:{mustVerifyEmail:{type:Boolean},status:{type:String}},setup(u){const i=x().props.auth.user,t=v({name:i.name,email:i.email});return(c,o)=>(l(),n("section",null,[S,s("form",{onSubmit:o[2]||(o[2]=V(r=>e(t).patch(c.route("profile.update")),["prevent"])),class:"mt-6 space-y-6"},[s("div",null,[a(p,{for:"name",value:"Name"}),a(g,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(t).name,"onUpdate:modelValue":o[0]||(o[0]=r=>e(t).name=r),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),a(y,{class:"mt-2",message:e(t).errors.name},null,8,["message"])]),s("div",null,[a(p,{for:"email",value:"Email"}),a(g,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(t).email,"onUpdate:modelValue":o[1]||(o[1]=r=>e(t).email=r),required:"",autocomplete:"username"},null,8,["modelValue"]),a(y,{class:"mt-2",message:e(t).errors.email},null,8,["message"])]),u.mustVerifyEmail&&e(i).email_verified_at===null?(l(),n("div",B,[s("p",N,[m(" Your email address is unverified. "),a(e(b),{href:c.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"},{default:d(()=>[m(" Click here to re-send the verification email. ")]),_:1},8,["href"])]),_(s("div",E," A new verification link has been sent to your email address. ",512),[[k,u.status==="verification-link-sent"]])])):f("",!0),s("div",P,[a(w,{disabled:e(t).processing},{default:d(()=>[m("Save")]),_:1},8,["disabled"]),a(h,{"enter-from-class":"opacity-0","leave-to-class":"opacity-0",class:"transition ease-in-out"},{default:d(()=>[e(t).recentlySuccessful?(l(),n("p",U,"Saved.")):f("",!0)]),_:1})])],32)]))}};export{q as default};