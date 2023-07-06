import{z as y,o,c as i,n as u,A as c,a as n,y as k,d,F as S,r as C,f as b,t as f,e as _,l as z,u as x,w as m,v as w,j as g}from"./app-c59eddac.js";var p=y({name:"Roulette",emits:["wheelStart","wheelEnd"],props:{items:{type:Object,required:!0,validator:function(t){return t.length>=4}},firstItemIndex:{type:Object,required:!1,default:function(){return{value:0}}},wheelResultIndex:{type:Object,required:!1,default:function(){return{value:null}},validator:function(t){return typeof t.value=="number"}},centeredIndicator:{type:Boolean,required:!1,default:!1},indicatorPosition:{type:String,required:!1,default:"top",validator:function(t){return["top","right","bottom","left"].includes(t)}},size:{type:Number,required:!1,default:300},displayShadow:{type:Boolean,required:!1,default:!1},duration:{type:Number,required:!1,default:4},resultVariation:{type:Number,required:!1,default:0,validator:function(t){return t>=0&&t<=100}},easing:{type:String,required:!1,default:"ease",validator:function(t){return["ease","bounce"].includes(t)}},counterClockwise:{type:Boolean,required:!1,default:!1},horizontalContent:{type:Boolean,required:!1,default:!1},displayBorder:{type:Boolean,required:!1,default:!1},displayIndicator:{type:Boolean,required:!1,default:!0},baseDisplay:{type:Boolean,required:!1,default:!1},baseSize:{type:Number,required:!1,default:100},baseDisplayShadow:{type:Boolean,required:!1,default:!1},baseDisplayIndicator:{type:Boolean,required:!1,default:!1},baseBackground:{type:String,required:!1,default:""}},data:function(){return{randomIdRoulette:0,itemSelected:null,processingLock:!1}},computed:{itemAngle:function(){return 360/this.items.length},startingAngle:function(){return this.centeredIndicator?-1*this.firstItemIndex.value*this.itemAngle-this.itemAngle/2:-1*this.firstItemIndex.value*this.itemAngle},degreesVariation:function(){if(!this.resultVariation)return 0;var t=this.itemAngle/2*this.resultVariation/100*-1,a=this.itemAngle/2*this.resultVariation/100;return Number((Math.random()*(a-t)+t).toFixed(2))},counterClockWiseOperator:function(){return this.counterClockwise?-1:1}},mounted:function(){var t=this;this.randomIdRoulette=Number((Math.random()*(999999-1)+1).toFixed(0)),this.$nextTick(function(){t.reset(),document.querySelector("#wheel-container-".concat(t.randomIdRoulette," .wheel")).addEventListener("transitionend",function(){t.processingLock=!1,t.$emit("wheel-end",t.itemSelected)})})},methods:{reset:function(){this.itemSelected=null,document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel")).style.transform="rotate(".concat(this.startingAngle,"deg)")},launchWheel:function(){if(!(this.processingLock&&this.itemSelected!=null)){this.processingLock=!0;var t;this.wheelResultIndex.value!==null?t=this.wheelResultIndex.value%this.items.length:t=Math.floor(Math.random()*this.items.length+1)-1;var a=document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel"));this.itemSelected=this.items[t],a.style.transform="rotate(".concat(this.counterClockWiseOperator*(360*3)+-t*this.itemAngle-this.itemAngle/2+this.degreesVariation,"deg)"),this.$emit("wheel-start",this.itemSelected)}}}}),F=["id"],q={class:"wheel-base"},A={key:0,class:"wheel-base-indicator"},I=["innerHTML"];function B(e,t,a,s,r,l){return o(),i("div",{id:"wheel-container-".concat(e.randomIdRoulette),class:u(["wheel-container",["indicator-".concat(e.indicatorPosition),{"wheel-container-indicator":e.displayIndicator},{"wheel-container-shadow":e.displayShadow},{"wheel-container-border":e.displayBorder}]])},[e.baseDisplay?(o(),i("div",{key:0,class:u(["wheel-base-container",[{"wheel-base-container-shadow":e.baseDisplayShadow}]]),style:c({width:"".concat(e.baseSize,"px"),height:"".concat(e.baseSize,"px"),background:"".concat(e.baseBackground)})},[n("div",q,[k(e.$slots,"baseContent")]),e.baseDisplayIndicator?(o(),i("div",A)):d("",!0)],6)):d("",!0),n("div",{class:u(["wheel",["easing-".concat(e.easing),{"wheel-border":e.displayBorder}]]),style:c({width:"".concat(e.size,"px"),height:"".concat(e.size,"px"),transitionDuration:"".concat(e.duration,"s"),transform:"rotate(".concat(e.startingAngle,"deg)")})},[(o(!0),i(S,null,C(e.items,function(h,v){return o(),i("div",{key:h.id,class:"wheel-item",style:c({transform:"rotate(".concat(e.itemAngle*v,"deg) skewY(").concat(-(90-e.itemAngle),"deg)"),background:h.background})},[n("div",{class:u(["content",{"horizontal-content":e.horizontalContent}]),style:c({transform:"skewY(".concat(90-e.itemAngle,"deg) rotate(").concat(e.itemAngle/2,"deg)")})},[n("span",{style:c({color:h.textColor}),innerHTML:h.htmlContent},null,12,I)],6)],4)}),128))],6)],10,F)}function W(e,t){t===void 0&&(t={});var a=t.insertAt;if(!(!e||typeof document>"u")){var s=document.head||document.getElementsByTagName("head")[0],r=document.createElement("style");r.type="text/css",a==="top"&&s.firstChild?s.insertBefore(r,s.firstChild):s.appendChild(r),r.styleSheet?r.styleSheet.cssText=e:r.appendChild(document.createTextNode(e))}}var D=`.wheel-container[data-v-2d0cf945],
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
}`;W(D);p.render=B;p.__scopeId="data-v-2d0cf945";const $={key:0,class:"row"},O={class:"col-12 mb-2"},V={class:"card"},N={class:"card-body"},R=["innerHTML"],E={key:0,class:"col-12 d-flex justify-content-center align-items-center",style:{"padding-top":"100px"}},T=n("hr",null,null,-1),j=n("div",null,"Поехали",-1),L={key:1,class:"col-12 p-5"},M={key:0,class:"alert alert-success",role:"alert"},U=n("hr",null,null,-1),P=n("h6",{class:"text-center"},"Укажите своё имя, как к Вам может обращаться менеджер?",-1),H={class:"input-group mb-3"},K={class:"col-12"},Y=n("h6",{class:"text-center"},"Введите свой номер телефона чтобы наш менеджер мог связаться с Вами!",-1),J={class:"input-group mb-3"},X=n("button",{class:"btn btn-outline-primary p-3 w-100"}," Получить выигрышь ",-1),G={class:"col-12 p-5 mt-2"},Q={key:1,class:"row"},Z={class:"col-12"},ee={alt:""},te={name:"App",components:{Roulette:p},data(){return{rouletteKey:0,played:!1,action:null,winForm:{win:null,name:null,phone:null},items:[]}},computed:{tg(){return window.Telegram.WebApp},tgUser(){const e=new URLSearchParams(this.tg.initData);return JSON.parse(e.get("user"))}},mounted(){this.prepare().then(()=>{this.played=this.action.completed_at!=null;let e=1;this.items=[],this.wheels.forEach(t=>{this.items.push({id:e,name:t.value,htmlContent:t.value,textColor:"",background:""}),e++})})},methods:{prepare(){return this.$store.dispatch("wheelOfFortunePrepare",{prepareForm:{tg:this.tgUser},bodDomain:this.bot.bot_domain}).then(e=>{this.action=e})},submit(){let e=new FormData;Object.keys(this.winForm).forEach(t=>{const a=this.winForm[t]||"";typeof a=="object"?e.append(t,JSON.stringify(a)):e.append(t,a)}),e.append("tg",this.tgUser),this.$store.dispatch("wheelOfFortuneWin",{winForm:e,bodDomain:this.bot.bot_domain}).then(t=>{this.winForm={win:null,name:null,phone:null},this.$notify({title:"Колесо фортуны",text:"Вы успешно приняли участие в розыгрыше! Наш менеджер свяжется с вами для дальнейших инструкций.",type:"success"})}).catch(t=>{})},closeWheel(){this.tg.close()},launchWheel(){this.rouletteKey+=1,setTimeout(()=>this.$refs.wheel.launchWheel(),0)},wheelStartedCallback(){console.log("wheelStartedCallback")},wheelEndedCallback(e){console.log(e),this.winForm.win=e}}},ae=Object.assign(te,{props:{bot:Object,wheels:Array,rules:String},setup(e){return(t,a)=>{const s=g("mask"),r=g("lazy");return t.action?(o(),i("div",$,[n("div",O,[n("div",V,[n("div",N,[e.rules?(o(),i("p",{key:0,innerHTML:e.rules},null,8,R)):d("",!0)])])]),t.played?d("",!0):(o(),i("div",E,[n("p",null,[b("Ваши попытки: "),n("strong",null,f(t.action.current_attempts||0),1),b(" из "),n("strong",null,f(t.action.max_attempts||1),1)]),T,(o(),_(x(p),{ref:"wheel",size:"300",key:t.rouletteKey,items:t.items,"centered-indicator":"","indicator-position":"top","display-shadow":"","display-border":"","base-display":"","base-display-indicator":"","base-background":"orange","base-display-shadow":"",easing:"bounce",onWheelStart:t.wheelStartedCallback,onWheelEnd:t.wheelEndedCallback,onClick:t.launchWheel},{baseContent:z(()=>[j]),_:1},8,["items","onWheelStart","onWheelEnd","onClick"]))])),t.played?d("",!0):(o(),i("div",L,[t.winForm.win?(o(),i("div",M,[n("p",null,"Вы выиграли - "+f(t.winForm.win.htmlContent)+".",1),U,n("form",{onSubmit:a[2]||(a[2]=(...l)=>t.submit&&t.submit(...l))},[P,n("div",H,[m(n("input",{type:"text",class:"form-control text-center p-3",placeholder:"Петров Петр Семенович","aria-label":"winForm-name","onUpdate:modelValue":a[0]||(a[0]=l=>t.winForm.name=l),"aria-describedby":"winForm-name",required:""},null,512),[[w,t.winForm.name]])]),n("div",K,[Y,n("div",J,[m(n("input",{type:"text",class:"form-control p-3 text-center","onUpdate:modelValue":a[1]||(a[1]=l=>t.winForm.phone=l),placeholder:"+7(000)000-00-00","aria-label":"winForm-phone","aria-describedby":"vipForm-phone",required:""},null,512),[[s,"+7(###)###-##-##"],[w,t.winForm.phone]])]),X])],32)])):d("",!0)])),n("div",G,[n("button",{onClick:a[3]||(a[3]=(...l)=>t.closeWheel&&t.closeWheel(...l)),type:"button",class:"btn btn-outline-info p-2"}," Вернуться в бота ")])])):(o(),i("div",Q,[n("div",Z,[m(n("img",ee,null,512),[[r,"/images/load.gif"]])])]))}}});export{ae as default};
