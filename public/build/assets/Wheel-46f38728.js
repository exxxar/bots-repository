import{P as v}from"./PlayerForm-3f84eff5.js";import{t as k,o,j as r,$ as f,a7 as h,l as n,p as S,L as c,F as m,J as g,K as l,n as d,G as w,w as b,u as C}from"./index.es-db843bab.js";import"./custom-4bdc3e34.js";import"./_plugin-vue_export-helper-c27b6911.js";var p=k({name:"Roulette",emits:["wheelStart","wheelEnd"],props:{items:{type:Object,required:!0,validator:function(t){return t.length>=4}},firstItemIndex:{type:Object,required:!1,default:function(){return{value:0}}},wheelResultIndex:{type:Object,required:!1,default:function(){return{value:null}},validator:function(t){return typeof t.value=="number"}},centeredIndicator:{type:Boolean,required:!1,default:!1},indicatorPosition:{type:String,required:!1,default:"top",validator:function(t){return["top","right","bottom","left"].includes(t)}},size:{type:Number,required:!1,default:300},displayShadow:{type:Boolean,required:!1,default:!1},duration:{type:Number,required:!1,default:4},resultVariation:{type:Number,required:!1,default:0,validator:function(t){return t>=0&&t<=100}},easing:{type:String,required:!1,default:"ease",validator:function(t){return["ease","bounce"].includes(t)}},counterClockwise:{type:Boolean,required:!1,default:!1},horizontalContent:{type:Boolean,required:!1,default:!1},displayBorder:{type:Boolean,required:!1,default:!1},displayIndicator:{type:Boolean,required:!1,default:!0},baseDisplay:{type:Boolean,required:!1,default:!1},baseSize:{type:Number,required:!1,default:100},baseDisplayShadow:{type:Boolean,required:!1,default:!1},baseDisplayIndicator:{type:Boolean,required:!1,default:!1},baseBackground:{type:String,required:!1,default:""}},data:function(){return{randomIdRoulette:0,itemSelected:null,processingLock:!1}},computed:{itemAngle:function(){return 360/this.items.length},startingAngle:function(){return this.centeredIndicator?-1*this.firstItemIndex.value*this.itemAngle-this.itemAngle/2:-1*this.firstItemIndex.value*this.itemAngle},degreesVariation:function(){if(!this.resultVariation)return 0;var t=this.itemAngle/2*this.resultVariation/100*-1,i=this.itemAngle/2*this.resultVariation/100;return Number((Math.random()*(i-t)+t).toFixed(2))},counterClockWiseOperator:function(){return this.counterClockwise?-1:1}},mounted:function(){var t=this;this.randomIdRoulette=Number((Math.random()*(999999-1)+1).toFixed(0)),this.$nextTick(function(){t.reset(),document.querySelector("#wheel-container-".concat(t.randomIdRoulette," .wheel")).addEventListener("transitionend",function(){t.processingLock=!1,t.$emit("wheel-end",t.itemSelected)})})},methods:{reset:function(){this.itemSelected=null,document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel")).style.transform="rotate(".concat(this.startingAngle,"deg)")},launchWheel:function(){if(!(this.processingLock&&this.itemSelected!=null)){this.processingLock=!0;var t;this.wheelResultIndex.value!==null?t=this.wheelResultIndex.value%this.items.length:t=Math.floor(Math.random()*this.items.length+1)-1;var i=document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel"));this.itemSelected=this.items[t],i.style.transform="rotate(".concat(this.counterClockWiseOperator*(360*3)+-t*this.itemAngle-this.itemAngle/2+this.degreesVariation,"deg)"),this.$emit("wheel-start",this.itemSelected)}}}}),z=["id"],F={class:"wheel-base"},I={key:0,class:"wheel-base-indicator"},q=["innerHTML"];function D(e,t,i,a,s,K){return o(),r("div",{id:"wheel-container-".concat(e.randomIdRoulette),class:f(["wheel-container",["indicator-".concat(e.indicatorPosition),{"wheel-container-indicator":e.displayIndicator},{"wheel-container-shadow":e.displayShadow},{"wheel-container-border":e.displayBorder}]])},[e.baseDisplay?(o(),r("div",{key:0,class:f(["wheel-base-container",[{"wheel-base-container-shadow":e.baseDisplayShadow}]]),style:h({width:"".concat(e.baseSize,"px"),height:"".concat(e.baseSize,"px"),background:"".concat(e.baseBackground)})},[n("div",F,[S(e.$slots,"baseContent")]),e.baseDisplayIndicator?(o(),r("div",I)):c("",!0)],6)):c("",!0),n("div",{class:f(["wheel",["easing-".concat(e.easing),{"wheel-border":e.displayBorder}]]),style:h({width:"".concat(e.size,"px"),height:"".concat(e.size,"px"),transitionDuration:"".concat(e.duration,"s"),transform:"rotate(".concat(e.startingAngle,"deg)")})},[(o(!0),r(m,null,g(e.items,function(u,y){return o(),r("div",{key:u.id,class:"wheel-item",style:h({transform:"rotate(".concat(e.itemAngle*y,"deg) skewY(").concat(-(90-e.itemAngle),"deg)"),background:u.background})},[n("div",{class:f(["content",{"horizontal-content":e.horizontalContent}]),style:h({transform:"skewY(".concat(90-e.itemAngle,"deg) rotate(").concat(e.itemAngle/2,"deg)")})},[n("span",{style:h({color:u.textColor}),innerHTML:u.htmlContent},null,12,q)],6)],4)}),128))],6)],10,z)}function x(e,t){t===void 0&&(t={});var i=t.insertAt;if(!(!e||typeof document>"u")){var a=document.head||document.getElementsByTagName("head")[0],s=document.createElement("style");s.type="text/css",i==="top"&&a.firstChild?a.insertBefore(s,a.firstChild):a.appendChild(s),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(document.createTextNode(e))}}var _=`.wheel-container[data-v-2d0cf945],
.wheel-base[data-v-2d0cf945],
.wheel-base-container[data-v-2d0cf945],
.wheel-base-indicator[data-v-2d0cf945] {
  transition: transform 1s ease-in-out;
}
.wheel-container[data-v-2d0cf945] {
  position: relative;
  display: inline-block;
  overflow: hidden;
  border-radius: 50%;
  cursor: pointer;
}
.wheel-container-indicator[data-v-2d0cf945]:before {
  content: "";
  position: absolute;
  z-index: 4;
  width: 0;
  height: 0;
  border-left: 20px solid transparent;
  border-right: 20px solid transparent;
  border-top: 20px solid black;
  transform: translateX(-50%);
}
.wheel-container.indicator-top[data-v-2d0cf945] {
  transform: rotate(0deg);
}
.wheel-container.indicator-right[data-v-2d0cf945] {
  transform: rotate(90deg);
}
.wheel-container.indicator-right .wheel-base[data-v-2d0cf945] {
  transform: rotate(-90deg);
}
.wheel-container.indicator-bottom[data-v-2d0cf945] {
  transform: rotate(180deg);
}
.wheel-container.indicator-bottom .wheel-base[data-v-2d0cf945] {
  transform: rotate(-180deg);
}
.wheel-container.indicator-left[data-v-2d0cf945] {
  transform: rotate(270deg);
}
.wheel-container.indicator-left .wheel-base[data-v-2d0cf945] {
  transform: rotate(-270deg);
}
.wheel-container-border[data-v-2d0cf945] {
  border: 8px solid black;
}
.wheel-container-shadow[data-v-2d0cf945] {
  box-shadow: 5px 5px 15px -5px #000000;
}
.wheel-base-container[data-v-2d0cf945] {
  position: absolute;
  z-index: 2;
  top: 50%;
  left: 50%;
  border-radius: 50%;
  border: 5px solid black;
  transform: translate(-50%, -50%);
}
.wheel-base-container-shadow[data-v-2d0cf945] {
  box-shadow: 5px 5px 15px -5px #000000;
}
.wheel-base-container .wheel-base[data-v-2d0cf945] {
  position: absolute;
  z-index: 2;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  width: 100%;
  height: 100%;
  border-radius: 50%;
}
.wheel-base-container .wheel-base-indicator[data-v-2d0cf945] {
  position: absolute;
  z-index: 1;
  width: 100%;
  height: 100%;
}
.wheel-base-container .wheel-base-indicator[data-v-2d0cf945]:before {
  content: "";
  position: absolute;
  z-index: 1;
  top: -20px;
  width: 0;
  height: 0;
  border-left: 20px solid transparent;
  border-right: 20px solid transparent;
  border-bottom: 20px solid black;
  transform: translateX(-50%);
}
.wheel[data-v-2d0cf945] {
  background: white;
  border-radius: 50%;
  margin: auto;
  overflow: hidden;
}
.wheel.easing-ease[data-v-2d0cf945] {
  transition: transform cubic-bezier(0.65, 0, 0.35, 1);
}
.wheel.easing-bounce[data-v-2d0cf945] {
  transition: transform cubic-bezier(0.49, 0.02, 0.52, 1.12);
}
.wheel-border[data-v-2d0cf945]:after {
  content: "";
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  z-index: 3;
  border-radius: 50%;
  background-image: linear-gradient(to left, black 33%, rgba(255, 255, 255, 0) 0%);
  background-position: bottom;
  background-size: 3px 1px;
  /* background:linear-gradient(red,purple,orange); */
  -webkit-mask: radial-gradient(transparent 65%, #000 66%);
  mask: radial-gradient(transparent 65%, #000 66%);
}
.wheel-item[data-v-2d0cf945] {
  overflow: hidden;
  position: absolute;
  top: 0;
  right: 0;
  width: 50%;
  height: 50%;
  transform-origin: 0% 100%;
  border: 1px solid black;
}
.wheel-item[data-v-2d0cf945]:nth-child(odd) {
  background-color: skyblue;
}
.wheel-item[data-v-2d0cf945]:nth-child(even) {
  background-color: pink;
}
.wheel .content[data-v-2d0cf945] {
  position: absolute;
  left: -100%;
  width: 200%;
  height: 200%;
  text-align: center;
  transform: skewY(30deg) rotate(0deg);
  padding-top: 20px;
}
.wheel .content.horizontal-content[data-v-2d0cf945] {
  left: initial;
  right: 100%;
  width: 50%;
  height: 250%;
  text-align: right;
}
.wheel .content.horizontal-content span[data-v-2d0cf945] {
  display: block;
  transform: rotate(270deg);
}`;x(_);p.render=D;p.__scopeId="data-v-2d0cf945";const A={key:0,class:"card card-style"},B={class:"content"},$=n("h4",null,"Правила данной игры",-1),P={key:0,class:"mb-2"},N=n("p",{style:{"font-weight":"900",color:"red"},class:"mb-2"},"Вы израсходовали все ваши попытки",-1),R=[N],W={key:2,class:"m-0 p-0"},E={class:"d-flex flex-column mb-2"},O={key:1,class:"card card-style"},V={class:"content d-flex justify-content-center flex-wrap"},j=n("div",null,"Поехали",-1),L=n("h3",null,"Анкета участника акции",-1),T=n("p",null," Для участия в конкурсе и дальнейшего получения приза необходимо заполнить данную анкету! Укажите своё имя и номер телефона чтоб менеджер мог выдать Вам приз по итогу. ",-1),M={name:"App",components:{Roulette:p},data(){return{winResultMessage:"Наш менеджер свяжется с вами для дальнейших инструкций.",rules:null,rouletteKey:0,action:null,hasProfileData:!1,winForm:{win:null},items:[]}},computed:{canPlay(){return this.action?this.action.current_attempts<this.action.max_attempts:!1}},mounted(){this.loadServiceData().then(()=>{this.prepareUserData()})},methods:{lose(){this.$botNotification.warning("Упс!","Вы израсходовали все попытки!")},prepareUserData(){return this.$store.dispatch("wheelOfFortunePrepare").then(e=>{this.action=e.action})},loadServiceData(){return this.$store.dispatch("wheelOfFortuneLoadData").then(e=>{let t=1;this.rules=e.rules,this.winResultMessage=e.callback_message;const i=e.wheels;this.items=[],i.forEach(a=>{this.items.push({id:t,name:a.value,htmlContent:a.value,textColor:"",background:""}),t++})})},submit(){let e=new FormData;Object.keys(this.winForm).forEach(i=>{const a=this.winForm[i]||"";typeof a=="object"?e.append(i,JSON.stringify(a)):e.append(i,a)});const t=this.winForm.win||"Что-то интересное...";this.$store.dispatch("wheelOfFortuneWin",{winForm:e}).then(i=>{this.winForm.win=null,this.winForm.name=null,this.winForm.phone=null,this.prepareUserData()}).catch(i=>{}),this.$botNotification.success("Вы выиграли!",t)},launchWheel(){this.rouletteKey+=1,setTimeout(()=>this.$refs.wheel.launchWheel(),0)},wheelStartedCallback(){},callbackPlayerForm(e){this.winForm={...this.winForm,...e},this.hasProfileData=!0},wheelEndedCallback(e){if(!e)return;const t=e.id;setTimeout(()=>{this.winForm.win=t,this.submit(),this.hasProfileData=!1},2e3),this.$botNotification.success("Победа!","Вы выиграли приз <strong>"+(this.winForm.win||"-")+"</strong>.")}}},X=Object.assign(M,{setup(e){return(t,i)=>(o(),r(m,null,[t.rules?(o(),r("div",A,[n("div",B,[$,n("p",null,l(t.rules),1),t.canPlay?(o(),r("p",P,[d("Ваши попытки: "),n("strong",null,l(t.action.current_attempts||0),1),d(" из "),n("strong",null,l(t.action.max_attempts||1),1)])):(o(),r("div",{key:1,onClick:i[0]||(i[0]=(...a)=>t.lose&&t.lose(...a))},R)),t.action.data?(o(),r("ul",W,[(o(!0),r(m,null,g(t.action.data,a=>(o(),r("li",E,[n("span",null,[d("Приз "),n("strong",null,"№"+l(a.win||"Отсуствует"),1)]),n("span",null,[d("Описание "),n("strong",null,l(a.description||"Отсуствует"),1)]),n("span",null,[d("Победитель "),n("strong",null,l(a.name||"Не указано"),1)]),n("span",null,[d("Телефон "),n("strong",null,l(a.phone||"Не указано"),1)])]))),256))])):c("",!0)])])):c("",!0),t.canPlay&&t.hasProfileData?(o(),r("div",O,[n("div",V,[(o(),w(C(p),{ref:"wheel",size:"300",key:t.rouletteKey,items:t.items,"centered-indicator":"","indicator-position":"top","display-shadow":"","display-border":"","base-display":"","base-display-indicator":"","base-background":"orange","base-display-shadow":"",easing:"bounce",onWheelStart:t.wheelStartedCallback,onWheelEnd:t.wheelEndedCallback,onClick:t.launchWheel},{baseContent:b(()=>[j]),_:1},8,["items","onWheelStart","onWheelEnd","onClick"]))])])):c("",!0),t.canPlay&&!t.hasProfileData?(o(),w(v,{key:2,onCallback:t.callbackPlayerForm},{head:b(()=>[L,T]),_:1},8,["onCallback"])):c("",!0)],64))}});export{X as default};
