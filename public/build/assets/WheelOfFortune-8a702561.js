import{z as y,o,c as i,n as u,A as d,a as n,y as k,d as c,F as S,r as _,t as p,f as b,e as C,l as z,u as x,w as f,v as w,j as g}from"./app-3593569c.js";var m=y({name:"Roulette",emits:["wheelStart","wheelEnd"],props:{items:{type:Object,required:!0,validator:function(e){return e.length>=4}},firstItemIndex:{type:Object,required:!1,default:function(){return{value:0}}},wheelResultIndex:{type:Object,required:!1,default:function(){return{value:null}},validator:function(e){return typeof e.value=="number"}},centeredIndicator:{type:Boolean,required:!1,default:!1},indicatorPosition:{type:String,required:!1,default:"top",validator:function(e){return["top","right","bottom","left"].includes(e)}},size:{type:Number,required:!1,default:300},displayShadow:{type:Boolean,required:!1,default:!1},duration:{type:Number,required:!1,default:4},resultVariation:{type:Number,required:!1,default:0,validator:function(e){return e>=0&&e<=100}},easing:{type:String,required:!1,default:"ease",validator:function(e){return["ease","bounce"].includes(e)}},counterClockwise:{type:Boolean,required:!1,default:!1},horizontalContent:{type:Boolean,required:!1,default:!1},displayBorder:{type:Boolean,required:!1,default:!1},displayIndicator:{type:Boolean,required:!1,default:!0},baseDisplay:{type:Boolean,required:!1,default:!1},baseSize:{type:Number,required:!1,default:100},baseDisplayShadow:{type:Boolean,required:!1,default:!1},baseDisplayIndicator:{type:Boolean,required:!1,default:!1},baseBackground:{type:String,required:!1,default:""}},data:function(){return{randomIdRoulette:0,itemSelected:null,processingLock:!1}},computed:{itemAngle:function(){return 360/this.items.length},startingAngle:function(){return this.centeredIndicator?-1*this.firstItemIndex.value*this.itemAngle-this.itemAngle/2:-1*this.firstItemIndex.value*this.itemAngle},degreesVariation:function(){if(!this.resultVariation)return 0;var e=this.itemAngle/2*this.resultVariation/100*-1,a=this.itemAngle/2*this.resultVariation/100;return Number((Math.random()*(a-e)+e).toFixed(2))},counterClockWiseOperator:function(){return this.counterClockwise?-1:1}},mounted:function(){var e=this;this.randomIdRoulette=Number((Math.random()*(999999-1)+1).toFixed(0)),this.$nextTick(function(){e.reset(),document.querySelector("#wheel-container-".concat(e.randomIdRoulette," .wheel")).addEventListener("transitionend",function(){e.processingLock=!1,e.$emit("wheel-end",e.itemSelected)})})},methods:{reset:function(){this.itemSelected=null,document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel")).style.transform="rotate(".concat(this.startingAngle,"deg)")},launchWheel:function(){if(!(this.processingLock&&this.itemSelected!=null)){this.processingLock=!0;var e;this.wheelResultIndex.value!==null?e=this.wheelResultIndex.value%this.items.length:e=Math.floor(Math.random()*this.items.length+1)-1;var a=document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel"));this.itemSelected=this.items[e],a.style.transform="rotate(".concat(this.counterClockWiseOperator*(360*3)+-e*this.itemAngle-this.itemAngle/2+this.degreesVariation,"deg)"),this.$emit("wheel-start",this.itemSelected)}}}}),I=["id"],F={class:"wheel-base"},q={key:0,class:"wheel-base-indicator"},A=["innerHTML"];function B(t,e,a,s,r,l){return o(),i("div",{id:"wheel-container-".concat(t.randomIdRoulette),class:u(["wheel-container",["indicator-".concat(t.indicatorPosition),{"wheel-container-indicator":t.displayIndicator},{"wheel-container-shadow":t.displayShadow},{"wheel-container-border":t.displayBorder}]])},[t.baseDisplay?(o(),i("div",{key:0,class:u(["wheel-base-container",[{"wheel-base-container-shadow":t.baseDisplayShadow}]]),style:d({width:"".concat(t.baseSize,"px"),height:"".concat(t.baseSize,"px"),background:"".concat(t.baseBackground)})},[n("div",F,[k(t.$slots,"baseContent")]),t.baseDisplayIndicator?(o(),i("div",q)):c("",!0)],6)):c("",!0),n("div",{class:u(["wheel",["easing-".concat(t.easing),{"wheel-border":t.displayBorder}]]),style:d({width:"".concat(t.size,"px"),height:"".concat(t.size,"px"),transitionDuration:"".concat(t.duration,"s"),transform:"rotate(".concat(t.startingAngle,"deg)")})},[(o(!0),i(S,null,_(t.items,function(h,v){return o(),i("div",{key:h.id,class:"wheel-item",style:d({transform:"rotate(".concat(t.itemAngle*v,"deg) skewY(").concat(-(90-t.itemAngle),"deg)"),background:h.background})},[n("div",{class:u(["content",{"horizontal-content":t.horizontalContent}]),style:d({transform:"skewY(".concat(90-t.itemAngle,"deg) rotate(").concat(t.itemAngle/2,"deg)")})},[n("span",{style:d({color:h.textColor}),innerHTML:h.htmlContent},null,12,A)],6)],4)}),128))],6)],10,I)}function W(t,e){e===void 0&&(e={});var a=e.insertAt;if(!(!t||typeof document>"u")){var s=document.head||document.getElementsByTagName("head")[0],r=document.createElement("style");r.type="text/css",a==="top"&&s.firstChild?s.insertBefore(r,s.firstChild):s.appendChild(r),r.styleSheet?r.styleSheet.cssText=t:r.appendChild(document.createTextNode(t))}}var D=`.wheel-container[data-v-2d0cf945],
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
}`;W(D);m.render=B;m.__scopeId="data-v-2d0cf945";const $={key:0,class:"row p-2"},O={key:0,class:"col-12 mb-2 mt-2"},V={class:"card"},N={class:"card-body"},R={class:"col-12 mb-2 mt-2"},j={key:0,style:{"text-align":"center","font-size":"larger"}},E={key:1,style:{"text-align":"center","font-size":"larger"}},T={key:1,class:"col-12 d-flex justify-content-center align-items-center"},L=n("div",null,"Поехали",-1),P={key:2,class:"col-12 p-2"},M={class:"alert alert-success mb-2",role:"alert"},U=n("h6",{class:"text-center"},"Укажите своё имя, как к Вам может обращаться менеджер?",-1),K={class:"input-group mb-3"},Y={class:"col-12"},H=n("h6",{class:"text-center"},"Введите свой номер телефона чтобы наш менеджер мог связаться с Вами!",-1),J={class:"input-group mb-3"},X=n("button",{class:"btn btn-outline-primary p-3 w-100"}," Получить выигрышь ",-1),G={class:"col-12 p-2 mt-2"},Q={key:1,class:"row"},Z={class:"col-12"},ee={class:"w-100",style:{"object-fit":"cover"},alt:""},te={name:"App",components:{Roulette:m},data(){return{rouletteKey:0,action:null,winForm:{win:null,name:null,phone:null},items:[]}},computed:{canPlay(){return this.action.current_attempts<this.action.max_attempts},tg(){return window.Telegram.WebApp},tgUserId(){return JSON.parse(new URLSearchParams(window.Telegram.WebApp.initData).get("user")).id||null}},mounted(){this.prepare().then(()=>{let t=1;this.items=[],this.wheels.forEach(e=>{this.items.push({id:t,name:e.value,htmlContent:e.value,textColor:"",background:""}),t++})})},methods:{prepare(){return this.$store.dispatch("wheelOfFortunePrepare",{prepareForm:{telegram_chat_id:this.tgUserId},bodDomain:this.bot.bot_domain}).then(t=>{this.action=t})},submit(){let t=new FormData;Object.keys(this.winForm).forEach(e=>{const a=this.winForm[e]||"";typeof a=="object"?t.append(e,JSON.stringify(a)):t.append(e,a)}),t.append("telegram_chat_id",this.tgUserId),this.$store.dispatch("wheelOfFortuneWin",{winForm:t,bodDomain:this.bot.bot_domain}).then(e=>{this.winForm={win:null,name:null,phone:null},this.$notify({title:"Колесо фортуны",text:"Вы успешно приняли участие в розыгрыше! Наш менеджер свяжется с вами для дальнейших инструкций.",type:"success"}),this.prepare()}).catch(e=>{})},closeWheel(){this.tg.close()},launchWheel(){this.rouletteKey+=1,setTimeout(()=>this.$refs.wheel.launchWheel(),0)},wheelStartedCallback(){console.log("wheelStartedCallback")},wheelEndedCallback(t){this.winForm.win=t.id}}},ae=Object.assign(te,{props:{bot:Object,wheels:Array,rules:String},setup(t){return(e,a)=>{const s=g("mask"),r=g("lazy");return e.action?(o(),i("div",$,[t.rules?(o(),i("div",O,[n("div",V,[n("div",N,[n("p",null,p(t.rules),1)])])])):c("",!0),n("div",R,[e.canPlay?(o(),i("p",j,[b("Ваши попытки: "),n("strong",null,p(e.action.current_attempts||0),1),b(" из "),n("strong",null,p(e.action.max_attempts||1),1)])):(o(),i("p",E,"Вы израсходовали все ваши попытки"))]),e.canPlay?(o(),i("div",T,[(o(),C(x(m),{ref:"wheel",size:"300",key:e.rouletteKey,items:e.items,"centered-indicator":"","indicator-position":"top","display-shadow":"","display-border":"","base-display":"","base-display-indicator":"","base-background":"orange","base-display-shadow":"",easing:"bounce",onWheelStart:e.wheelStartedCallback,onWheelEnd:e.wheelEndedCallback,onClick:e.launchWheel},{baseContent:z(()=>[L]),_:1},8,["items","onWheelStart","onWheelEnd","onClick"]))])):c("",!0),e.canPlay&&e.winForm.win?(o(),i("div",P,[n("div",M,[n("p",null,"Вы выиграли - "+p(e.winForm.win.htmlContent)+".",1)]),n("form",{onSubmit:a[2]||(a[2]=(...l)=>e.submit&&e.submit(...l))},[U,n("div",K,[f(n("input",{type:"text",class:"form-control text-center p-3",placeholder:"Петров Петр Семенович","aria-label":"winForm-name","onUpdate:modelValue":a[0]||(a[0]=l=>e.winForm.name=l),"aria-describedby":"winForm-name",required:""},null,512),[[w,e.winForm.name]])]),n("div",Y,[H,n("div",J,[f(n("input",{type:"text",class:"form-control p-3 text-center","onUpdate:modelValue":a[1]||(a[1]=l=>e.winForm.phone=l),placeholder:"+7(000)000-00-00","aria-label":"winForm-phone","aria-describedby":"vipForm-phone",required:""},null,512),[[s,"+7(###)###-##-##"],[w,e.winForm.phone]])]),X])],32)])):c("",!0),n("div",G,[n("button",{onClick:a[3]||(a[3]=(...l)=>e.closeWheel&&e.closeWheel(...l)),type:"button",class:"btn btn-outline-primary p-3 w-100"}," Вернуться в бота ")])])):(o(),i("div",Q,[n("div",Z,[f(n("img",ee,null,512),[[r,"/images/load.gif"]])])]))}}});export{ae as default};
