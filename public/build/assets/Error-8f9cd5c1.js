import{_ as a}from"./_plugin-vue_export-helper-c27b6911.js";import{L as c,q as n,t as i,v as t,H as l}from"./index.es-71d37bd0.js";const d={data(){return{}},computed:{tg(){return window.Telegram.WebApp},tgUser(){const s=new URLSearchParams(this.tg.initData);return JSON.parse(s.get("user"))}},mounted(){},methods:{}},_={class:"container py-5 px-3"},m=t("div",{class:"row mb-3"},[t("div",{class:"col-12"},[t("div",{class:"alert alert-info",role:"alert"}," В данный момент этот раздел находится на техническом обслуживании! В ближайшее время всё заработает:) Спасибо за ожидание! ")])],-1),p={class:"row"},u={class:"col-12"},v={class:"w-100",style:{"object-fit":"cover","mix-blend-mode":"darken"},alt:""},h={class:"row"},f={class:"col-12"};function g(s,e,w,b,x,o){const r=c("lazy");return n(),i("div",_,[m,t("div",p,[t("div",u,[l(t("img",v,null,512),[[r,"/images/cashman.jpg"]])])]),t("div",h,[t("div",f,[t("button",{type:"button",onClick:e[0]||(e[0]=y=>o.tg.close()),class:"btn btn-outline-success w-100 p-3"},"Вернуться в бота ")])])])}const B=a(d,[["render",g]]);export{B as default};
