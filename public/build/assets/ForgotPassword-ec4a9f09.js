import{a5 as _,q as c,K as u,O as l,v as s,P as p,t as h,y as f,A as g,H as b,u as e,J as v,B as n,x,D as o}from"./index.es-4b6d32ea.js";import{_ as y}from"./GuestLayout-51968f0f.js";import{_ as w}from"./InputError-091f09c7.js";import{P as k}from"./PrimaryButton-ad0e6217.js";import"./_plugin-vue_export-helper-c27b6911.js";const B={class:"gradient-form",style:{"background-color":"#eee",height:"100vh"}},N={class:"container py-5"},P={class:"row d-flex justify-content-center align-items-center h-100"},V={class:"col-xl-10"},S={class:"card rounded-3 text-black"},C={class:"row g-0"},D=s("div",{class:"col-lg-6 d-flex align-items-center gradient-custom-2"},[s("div",{class:"text-white px-3 py-4 p-md-5 mx-md-4"},[s("h4",{class:"mb-4"},"Восстановление доступа к аккаунту"),s("p",{class:"small mb-0"},[o(" Забыли пароль? Не беда! Укажите вашу почту, и мы отправим вам ссылку для сброса пароля. Следуйте инструкциям в письме, чтобы восстановить доступ к вашему аккаунту. "),s("br"),s("br"),o(" Если у вас возникли трудности, свяжитесь с нашей поддержкой — мы всегда готовы помочь! ")])])],-1),T={class:"col-lg-6"},j={class:"card-body p-md-5 mx-md-4"},q=s("div",{class:"text-center"},[s("div",{class:"logo"},"NextIT"),s("h4",{class:"mt-1 mb-5 pb-1"},"Современные решения для бизнеса")],-1),F=["onSubmit"],M=s("p",null,"Восстановление пароля",-1),$={key:0,class:"mb-4 font-medium text-sm text-green-600"},z={class:"form-floating mb-3"},A=s("label",{for:"email"},"Почта",-1),E={class:"text-center pt-1 mb-5 pb-1"},H={class:"d-flex align-items-center justify-content-center pb-4"},I=s("p",{class:"mb-0 me-2"},"Вспомнили пароль?",-1),J=["href"],Q={__name:"ForgotPassword",props:{status:{type:String}},setup(a){const t=_({email:""}),r=()=>{t.post(route("password.email"))};return(d,i)=>(c(),u(y,null,{default:l(()=>[s("section",B,[s("div",N,[s("div",P,[s("div",V,[s("div",S,[s("div",C,[D,s("div",T,[s("div",j,[q,s("form",{onSubmit:p(r,["prevent"])},[M,a.status?(c(),h("div",$,f(a.status),1)):g("",!0),s("div",z,[b(s("input",{id:"email",type:"email","onUpdate:modelValue":i[0]||(i[0]=m=>e(t).email=m),required:"",autofocus:"",class:"form-control",placeholder:"name@example.com"},null,512),[[v,e(t).email]]),A]),n(w,{class:"mt-2",message:e(t).errors.email},null,8,["message"]),s("div",E,[n(k,{class:x(["ml-4 gradient-custom-2",{"opacity-25":e(t).processing}]),disabled:e(t).processing},{default:l(()=>[o(" Отправить ссылку для сброса ")]),_:1},8,["class","disabled"])]),s("div",H,[I,s("a",{href:d.route("login"),class:"btn btn-outline-danger ml-2"},"Войти",8,J)])],40,F)])])])])])])])])]),_:1}))}};export{Q as default};
