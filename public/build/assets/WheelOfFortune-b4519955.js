import{z as b,o as r,c as d,n as u,A as s,a as i,y as g,d as m,F as p,r as w,e as v,l as y,B as k}from"./app-04a61ca5.js";import{_ as C}from"./_plugin-vue_export-helper-c27b6911.js";var f=b({name:"Roulette",emits:["wheelStart","wheelEnd"],props:{items:{type:Object,required:!0,validator:function(t){return t.length>=4}},firstItemIndex:{type:Object,required:!1,default:function(){return{value:0}}},wheelResultIndex:{type:Object,required:!1,default:function(){return{value:null}},validator:function(t){return typeof t.value=="number"}},centeredIndicator:{type:Boolean,required:!1,default:!1},indicatorPosition:{type:String,required:!1,default:"top",validator:function(t){return["top","right","bottom","left"].includes(t)}},size:{type:Number,required:!1,default:300},displayShadow:{type:Boolean,required:!1,default:!1},duration:{type:Number,required:!1,default:4},resultVariation:{type:Number,required:!1,default:0,validator:function(t){return t>=0&&t<=100}},easing:{type:String,required:!1,default:"ease",validator:function(t){return["ease","bounce"].includes(t)}},counterClockwise:{type:Boolean,required:!1,default:!1},horizontalContent:{type:Boolean,required:!1,default:!1},displayBorder:{type:Boolean,required:!1,default:!1},displayIndicator:{type:Boolean,required:!1,default:!0},baseDisplay:{type:Boolean,required:!1,default:!1},baseSize:{type:Number,required:!1,default:100},baseDisplayShadow:{type:Boolean,required:!1,default:!1},baseDisplayIndicator:{type:Boolean,required:!1,default:!1},baseBackground:{type:String,required:!1,default:""}},data:function(){return{randomIdRoulette:0,itemSelected:null,processingLock:!1}},computed:{itemAngle:function(){return 360/this.items.length},startingAngle:function(){return this.centeredIndicator?-1*this.firstItemIndex.value*this.itemAngle-this.itemAngle/2:-1*this.firstItemIndex.value*this.itemAngle},degreesVariation:function(){if(!this.resultVariation)return 0;var t=this.itemAngle/2*this.resultVariation/100*-1,a=this.itemAngle/2*this.resultVariation/100;return Number((Math.random()*(a-t)+t).toFixed(2))},counterClockWiseOperator:function(){return this.counterClockwise?-1:1}},mounted:function(){var t=this;this.randomIdRoulette=Number((Math.random()*(999999-1)+1).toFixed(0)),this.$nextTick(function(){t.reset(),document.querySelector("#wheel-container-".concat(t.randomIdRoulette," .wheel")).addEventListener("transitionend",function(){t.processingLock=!1,t.$emit("wheel-end",t.itemSelected)})})},methods:{reset:function(){this.itemSelected=null,document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel")).style.transform="rotate(".concat(this.startingAngle,"deg)")},launchWheel:function(){if(!(this.processingLock&&this.itemSelected!=null)){this.processingLock=!0;var t;this.wheelResultIndex.value!==null?t=this.wheelResultIndex.value%this.items.length:t=Math.floor(Math.random()*this.items.length+1)-1;var a=document.querySelector("#wheel-container-".concat(this.randomIdRoulette," .wheel"));this.itemSelected=this.items[t],a.style.transform="rotate(".concat(this.counterClockWiseOperator*(360*3)+-t*this.itemAngle-this.itemAngle/2+this.degreesVariation,"deg)"),this.$emit("wheel-start",this.itemSelected)}}}}),x=["id"],S={class:"wheel-base"},z={key:0,class:"wheel-base-indicator"},I=["innerHTML"];function q(e,t,a,o,n,c){return r(),d("div",{id:"wheel-container-".concat(e.randomIdRoulette),class:u(["wheel-container",["indicator-".concat(e.indicatorPosition),{"wheel-container-indicator":e.displayIndicator},{"wheel-container-shadow":e.displayShadow},{"wheel-container-border":e.displayBorder}]])},[e.baseDisplay?(r(),d("div",{key:0,class:u(["wheel-base-container",[{"wheel-base-container-shadow":e.baseDisplayShadow}]]),style:s({width:"".concat(e.baseSize,"px"),height:"".concat(e.baseSize,"px"),background:"".concat(e.baseBackground)})},[i("div",S,[g(e.$slots,"baseContent")]),e.baseDisplayIndicator?(r(),d("div",z)):m("",!0)],6)):m("",!0),i("div",{class:u(["wheel",["easing-".concat(e.easing),{"wheel-border":e.displayBorder}]]),style:s({width:"".concat(e.size,"px"),height:"".concat(e.size,"px"),transitionDuration:"".concat(e.duration,"s"),transform:"rotate(".concat(e.startingAngle,"deg)")})},[(r(!0),d(p,null,w(e.items,function(l,h){return r(),d("div",{key:l.id,class:"wheel-item",style:s({transform:"rotate(".concat(e.itemAngle*h,"deg) skewY(").concat(-(90-e.itemAngle),"deg)"),background:l.background})},[i("div",{class:u(["content",{"horizontal-content":e.horizontalContent}]),style:s({transform:"skewY(".concat(90-e.itemAngle,"deg) rotate(").concat(e.itemAngle/2,"deg)")})},[i("span",{style:s({color:l.textColor}),innerHTML:l.htmlContent},null,12,I)],6)],4)}),128))],6)],10,x)}function B(e,t){t===void 0&&(t={});var a=t.insertAt;if(!(!e||typeof document>"u")){var o=document.head||document.getElementsByTagName("head")[0],n=document.createElement("style");n.type="text/css",a==="top"&&o.firstChild?o.insertBefore(n,o.firstChild):o.appendChild(n),n.styleSheet?n.styleSheet.cssText=e:n.appendChild(document.createTextNode(e))}}var A=`.wheel-container[data-v-2d0cf945],
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
}`;B(A);f.render=q;f.__scopeId="data-v-2d0cf945";const R={name:"App",components:{Roulette:f},data(){return{rouletteKey:0,items:[{id:1,name:"1",htmlContent:"жопа бобра<br>с кусочками<br>лайма",textColor:"",background:""},{id:2,name:"10",htmlContent:"<i class='fa-brands fa-instagram'></i>",textColor:"",background:""},{id:3,name:"Banana",htmlContent:"<img src='https://prv3.lori-images.net/koleso-ruletki-na-belom-fone-0004118371-preview.jpg' style='width:50px;'>",textColor:"",background:"white"},{id:4,name:"1000",htmlContent:"1000",textColor:"",background:""},{id:7,name:"1000",htmlContent:"1000",textColor:"",background:""},{id:4,name:"1000",htmlContent:"1000",textColor:"",background:""},{id:4,name:"1000",htmlContent:"1000",textColor:"",background:""},{id:41,name:"10000",htmlContent:"10000"},{id:5,name:"1000000",htmlContent:"1000000"},{id:6,name:"10000000",htmlContent:"10 000 000"},{id:7,name:"0",htmlContent:"0"}]}},methods:{launchWheel(){this.rouletteKey+=1,setTimeout(()=>this.$refs.wheel.launchWheel(),0)},wheelStartedCallback(){console.log("wheelStartedCallback")},wheelEndedCallback(e){console.log(e)}}},W=i("div",null,"Поехали",-1);function V(e,t,a,o,n,c){const l=k("Roulette");return r(),d(p,null,[(r(),v(l,{ref:"wheel",size:"300",key:n.rouletteKey,items:n.items,"centered-indicator":"","indicator-position":"top","display-shadow":"","display-border":"","base-display":"","base-display-indicator":"","base-background":"orange","base-display-shadow":"",easing:"bounce",onWheelStart:c.wheelStartedCallback,onWheelEnd:c.wheelEndedCallback,onClick:c.launchWheel},{baseContent:y(()=>[W]),_:1},8,["items","onWheelStart","onWheelEnd","onClick"])),i("button",{onClick:t[0]||(t[0]=(...h)=>e.$refs.wheel.reset&&e.$refs.wheel.reset(...h))},"reset"),i("button",{onClick:t[1]||(t[1]=h=>n.rouletteKey+=1)},"hard reset")],64)}const N=C(R,[["render",V]]);export{N as default};
