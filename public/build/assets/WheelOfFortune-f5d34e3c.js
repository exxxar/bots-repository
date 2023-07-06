import{z as g,o as i,c as r,n as h,A as d,a as n,y as v,d as l,F as y,r as k,f,t as p,e as S,l as C,u as x,w as m,v as b,j as z}from"./app-86ea7a9c.js";var u=g({name:"Roulette",emits:["wheelStart","wheelEnd"],props:{items:{type:Object,required:!0,validator:function(t){return t.length>=4}},firstItemIndex:{type:Object,required:!1,default:function(){return{value:0}}},wheelResultIndex:{type:Object,required:!1,default:function(){return{value:null}},validator:function(t){return typeof t.value=="number"}},centeredIndicator:{type:Boolean,required:!1,default:!1},indicatorPosition:{type:String,required:!1,default:"top",validator:function(t){return["top","right","bottom","left"].includes(t)}},size:{type:Number,required:!1,default:300},displayShadow:{type:Boolean,required:!1,default:!1},duration:{type:Number,required:!1,default:4},resultVariation:{type:Number,required:!1,default:0,validator:function(t){return t>=0&&t<=100}},easing:{type:String,required:!1,default:"ease",validator:function(t){return["ease","bounce"].includes(t)}},counterClockwise:{type:Boolean,required:!1,default:!1},horizontalContent:{type:Boolean,required:!1,default:!1},displayBorder:{type:Boolean,required:!1,default:!1},displayIndicator:{type:Boolean,required:!1,default:!0},baseDisplay:{type:Boolean,required:!1,default:!1},baseSize:{type:Number,required:!1,default:100},baseDisplayShadow:{type:Boolean,required:!1,default:!1},baseDisplayIndicator:{type:Boolean,required:!1,default:!1},baseBackground:{type:String,required:!1,default:""}},data:function(){return{randomIdRoulette:0,itemSelected:null,processingLock:!1}},computed:{itemAngle:function(){return 360/this.items.length},startingAngle:function(){return this.centeredIndicator?-1*this.firstItemIndex.value*this.itemAngle-this.itemAngle/2:-1*this.firstItemIndex.value*this.itemAngle},degreesVariation:function(){if(!this.resultVariation)return 0;var t=this.itemAngle/2*this.resultVariation/100*-1,a=this.itemAngle/2*this.resultVariation/100;return Number((Math.random()*(a-t)+t).toFixed(2))},counterClockWiseOperator:function(){return this.counterClockwise?-1:1}},mounted:function(){var t=this;this.randomIdRoulette=Number((Math.random()*(999999-1)+1).toFixed(0)),this.$nextTick(function(){t.reset(),document.querySelector("#wheel-container-".concat(t.randomIdRoulette," .wheel")).addEventListener("transitionend",function(){t.processingLock=!1,t.$emit("wheel-end",t.itemSelected)})})},methods:{reset:function(){this.itemSelected=null,document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel")).style.transform="rotate(".concat(this.startingAngle,"deg)")},launchWheel:function(){if(!(this.processingLock&&this.itemSelected!=null)){this.processingLock=!0;var t;this.wheelResultIndex.value!==null?t=this.wheelResultIndex.value%this.items.length:t=Math.floor(Math.random()*this.items.length+1)-1;var a=document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel"));this.itemSelected=this.items[t],a.style.transform="rotate(".concat(this.counterClockWiseOperator*(360*3)+-t*this.itemAngle-this.itemAngle/2+this.degreesVariation,"deg)"),this.$emit("wheel-start",this.itemSelected)}}}}),q=["id"],A={class:"wheel-base"},F={key:0,class:"wheel-base-indicator"},I=["innerHTML"];function B(e,t,a,s,o,Q){return i(),r("div",{id:"wheel-container-".concat(e.randomIdRoulette),class:h(["wheel-container",["indicator-".concat(e.indicatorPosition),{"wheel-container-indicator":e.displayIndicator},{"wheel-container-shadow":e.displayShadow},{"wheel-container-border":e.displayBorder}]])},[e.baseDisplay?(i(),r("div",{key:0,class:h(["wheel-base-container",[{"wheel-base-container-shadow":e.baseDisplayShadow}]]),style:d({width:"".concat(e.baseSize,"px"),height:"".concat(e.baseSize,"px"),background:"".concat(e.baseBackground)})},[n("div",A,[v(e.$slots,"baseContent")]),e.baseDisplayIndicator?(i(),r("div",F)):l("",!0)],6)):l("",!0),n("div",{class:h(["wheel",["easing-".concat(e.easing),{"wheel-border":e.displayBorder}]]),style:d({width:"".concat(e.size,"px"),height:"".concat(e.size,"px"),transitionDuration:"".concat(e.duration,"s"),transform:"rotate(".concat(e.startingAngle,"deg)")})},[(i(!0),r(y,null,k(e.items,function(c,w){return i(),r("div",{key:c.id,class:"wheel-item",style:d({transform:"rotate(".concat(e.itemAngle*w,"deg) skewY(").concat(-(90-e.itemAngle),"deg)"),background:c.background})},[n("div",{class:h(["content",{"horizontal-content":e.horizontalContent}]),style:d({transform:"skewY(".concat(90-e.itemAngle,"deg) rotate(").concat(e.itemAngle/2,"deg)")})},[n("span",{style:d({color:c.textColor}),innerHTML:c.htmlContent},null,12,I)],6)],4)}),128))],6)],10,q)}function _(e,t){t===void 0&&(t={});var a=t.insertAt;if(!(!e||typeof document>"u")){var s=document.head||document.getElementsByTagName("head")[0],o=document.createElement("style");o.type="text/css",a==="top"&&s.firstChild?s.insertBefore(o,s.firstChild):s.appendChild(o),o.styleSheet?o.styleSheet.cssText=e:o.appendChild(document.createTextNode(e))}}var W=`.wheel-container[data-v-2d0cf945],
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
}`;_(W);u.render=B;u.__scopeId="data-v-2d0cf945";const D={class:"row"},$={class:"col-12 mb-2"},O={class:"card"},V={class:"card-body"},N=["innerHTML"],R={key:0,class:"col-12 d-flex justify-content-center align-items-center",style:{"padding-top":"100px"}},j=n("hr",null,null,-1),E=n("div",null,"Поехали",-1),T={key:1,class:"col-12 p-5"},L={key:0,class:"alert alert-success",role:"alert"},M=n("hr",null,null,-1),U=n("h6",{class:"text-center"},"Укажите своё имя, как к Вам может обращаться менеджер?",-1),H={class:"input-group mb-3"},P={class:"col-12"},K=n("h6",{class:"text-center"},"Введите свой номер телефона чтобы наш менеджер мог связаться с Вами!",-1),Y={class:"input-group mb-3"},J=n("button",{class:"btn btn-outline-primary p-3 w-100"}," Получить выигрышь ",-1),X={class:"col-12 p-5 mt-2"},G={name:"App",components:{Roulette:u},data(){return{rouletteKey:0,played:!1,winForm:{win:null,name:null,phone:null},items:[]}},computed:{tg(){return window.Telegram.WebApp},tgUser(){const e=new URLSearchParams(this.tg.initData);return JSON.parse(e.get("user"))}},mounted(){this.played=this.action.completed_at!=null;let e=1;this.items=[],this.wheels.forEach(t=>{this.items.push({id:e,name:t.value,htmlContent:t.value,textColor:"",background:""}),e++})},methods:{submit(){let e=new FormData;Object.keys(this.winForm).forEach(t=>{const a=this.winForm[t]||"";typeof a=="object"?e.append(t,JSON.stringify(a)):e.append(t,a)}),e.append("tg_user",this.tgUser),this.$store.dispatch("wheelOfFortuneWin",{winForm:e,bodDomain:this.bot.bot_domain}).then(t=>{this.winForm={win:null,name:null,phone:null},this.$notify({title:"Колесо фортуны",text:"Вы успешно приняли участие в розыгрыше! Наш менеджер свяжется с вами для дальнейших инструкций.",type:"success"})}).catch(t=>{})},closeWheel(){this.tg.close()},launchWheel(){this.rouletteKey+=1,setTimeout(()=>this.$refs.wheel.launchWheel(),0)},wheelStartedCallback(){console.log("wheelStartedCallback")},wheelEndedCallback(e){console.log(e),this.winForm.win=e}}},ee=Object.assign(G,{props:{bot:Object,wheels:Array,rules:String,action:Object},setup(e){return(t,a)=>{const s=z("mask");return i(),r("div",D,[n("div",$,[n("div",O,[n("div",V,[e.rules?(i(),r("p",{key:0,innerHTML:e.rules},null,8,N)):l("",!0)])])]),t.played?l("",!0):(i(),r("div",R,[n("p",null,[f("Ваши попытки: "),n("strong",null,p(e.action.current_attempts||0),1),f(" из "),n("strong",null,p(e.action.max_attempts||1),1)]),j,(i(),S(x(u),{ref:"wheel",size:"300",key:t.rouletteKey,items:t.items,"centered-indicator":"","indicator-position":"top","display-shadow":"","display-border":"","base-display":"","base-display-indicator":"","base-background":"orange","base-display-shadow":"",easing:"bounce",onWheelStart:t.wheelStartedCallback,onWheelEnd:t.wheelEndedCallback,onClick:t.launchWheel},{baseContent:C(()=>[E]),_:1},8,["items","onWheelStart","onWheelEnd","onClick"]))])),t.played?l("",!0):(i(),r("div",T,[t.winForm.win?(i(),r("div",L,[n("p",null,"Вы выиграли - "+p(t.winForm.win.htmlContent)+".",1),M,n("form",{onSubmit:a[2]||(a[2]=(...o)=>t.submit&&t.submit(...o))},[U,n("div",H,[m(n("input",{type:"text",class:"form-control text-center p-3",placeholder:"Петров Петр Семенович","aria-label":"winForm-name","onUpdate:modelValue":a[0]||(a[0]=o=>t.winForm.name=o),"aria-describedby":"winForm-name",required:""},null,512),[[b,t.winForm.name]])]),n("div",P,[K,n("div",Y,[m(n("input",{type:"text",class:"form-control p-3 text-center","onUpdate:modelValue":a[1]||(a[1]=o=>t.winForm.phone=o),placeholder:"+7(000)000-00-00","aria-label":"winForm-phone","aria-describedby":"vipForm-phone",required:""},null,512),[[s,"+7(###)###-##-##"],[b,t.winForm.phone]])]),J])],32)])):l("",!0)])),n("div",X,[n("button",{onClick:a[3]||(a[3]=(...o)=>t.closeWheel&&t.closeWheel(...o)),type:"button",class:"btn btn-outline-info p-2"}," Вернуться в бота ")])])}}});export{ee as default};
