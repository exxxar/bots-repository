import"./V1Layout-67f9a43f.js";import{q as n,t as e,v as t,y as a,D as r,F as i,C as c,A as d}from"./index.es-c683997f.js";import"./app-2e52a54d.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./BotMediaList-f7e39d32.js";import"./Pagination-8255954b.js";import"./base64-c0c57375.js";import"./main-b0bb0ab8.js";import"./TelegramChannelHelper-6442872b.js";import"./v4-4a60fe23.js";import"./carousel.es-a4bf7a5e.js";import"./GlobalSlugList-a08468c6.js";import"./vue3-perfect-scrollbar-af0aac35.js";import"./index-369b1042.js";const u={class:"container"},p={class:"row"},l={class:"col-12"},m=t("h2",{class:"text-primary fw-bold text-center mb-2"},"Результат",-1),f={class:"text-primary mb-3"},h={class:"text-primary d-flex flex-column"},g=t("h6",null,"Данные товары не совпадают с frontPad",-1),y={class:"list-group"},b={class:"list-group-item"},v={data(){return{statistic:{total_product_count:0,created_product_count:0,total_frontpad_count:0,updated_product_count:0,front_pad_not_found_items:[]}}},computed:{tg(){return window.Telegram.WebApp}},mounted(){if(this.data){let o=JSON.parse(this.data);this.statistic.total_frontpad_count=o.total_product_count||0,this.statistic.created_product_count=o.created_product_count||0,this.statistic.total_frontpad_count=o.total_frontpad_count||0,this.statistic.updated_product_count=o.updated_product_count||0,this.statistic.front_pad_not_found_items=o.front_pad_not_found_items||[]}},methods:{}},q=Object.assign(v,{__name:"Result",props:{message:{type:String},data:{type:String}},setup(o){return(s,w)=>(n(),e("div",u,[t("div",p,[t("div",l,[m,t("p",f,a(o.message),1),t("p",h,[t("span",null,[r("Всего товаров затронуто "),t("strong",null,a(s.statistic.total_product_count||0),1)]),t("span",null,[r("Совпадений товара с FrontPad "),t("strong",null,a(s.statistic.total_frontpad_count||0),1)]),t("span",null,[r("Создано новых товаров "),t("strong",null,a(s.statistic.created_product_count||0),1)]),t("span",null,[r("Обновлено товаров "),t("strong",null,a(s.statistic.updated_product_count||0),1)])]),s.statistic.front_pad_not_found_items.length>0?(n(),e(i,{key:0},[g,t("ul",y,[(n(!0),e(i,null,c(s.statistic.front_pad_not_found_items,_=>(n(),e("li",b,a(_),1))),256))])],64)):d("",!0)])])]))}});export{q as default};
