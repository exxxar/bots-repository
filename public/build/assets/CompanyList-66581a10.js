import{L as $,q as l,t as n,v as e,P as w,y as b,H as h,R as P,J as _,D as p,A as r,F as y,C as g,Y as I,x as C,B as k,O as v,X as V,K as L,G as j}from"./index.es-71d37bd0.js";import{P as q}from"./Pagination-c0ff4992.js";import{_ as O}from"./_plugin-vue_export-helper-c27b6911.js";const M={props:["company"],data(){return{locations:[],deletedLocations:[],locationForm:{id:null,lat:null,lon:null,address:null,description:null,location_channel:null,can_booking:!1,photos:[],company_id:null}}},mounted(){this.loadLocationsByCompany()},methods:{loadLocationsByCompany(){this.$store.dispatch("loadLocationsByCompany",{companyId:this.company.id}).then(o=>{this.locations=o||[]}).catch(()=>{})},getPhoto(o){return{imageUrl:URL.createObjectURL(o)}},removePhoto(o){this.locationForm.photos.splice(o,1)},removeItem(o){this.locations[o].id!=null&&this.deletedLocations.push(this.locations[o].id),this.locations.splice(o,1)},submitLocations(){this.locations.forEach(o=>{let s=new FormData;if(Object.keys(o).forEach(t=>{const d=o[t]||"";typeof d=="object"?s.append(t,JSON.stringify(d)):s.append(t,d)}),o.photos){for(let t=0;t<o.photos.length;t++)s.append("files[]",o.photos[t]);s.delete("photos")}this.deletedLocations.length>0&&s.append("deleted_locations",JSON.stringify(this.deletedLocations)),this.$store.dispatch("createLocation",{locationForm:s}).then(t=>{this.$emit("callback"),this.$notify("Локация успешно созадана и сохранена")}).catch(t=>{})})},selectLocation(o){this.locationForm=o},submitLocation(){this.locationForm.company_id=this.company.id,this.locations.push(this.locationForm),this.$notify("Локация успешно добавлена в список. Не забудьте сохранить"),this.locationForm={id:null,lat:null,lon:null,address:null,description:null,location_channel:null,can_booking:!1,photos:[]}},onChangePhotos(o){const s=o.target.files;for(let t=0;t<s.length;t++)this.locationForm.photos.push(s[t])}}},B={class:"card"},z={class:"card-body"},S={class:"row"},D={class:"col-md-8"},E={class:"row"},A={class:"col-12"},N={class:"form-check"},T=e("label",{class:"form-check-label",for:"flexCheckDefault"}," Можно бронировать столик ",-1),x={class:"col-12"},R={class:"mb-3"},J=e("label",{class:"form-label",id:"location-address"},[p(" Адрес заведения "),e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно")],-1),G={class:"row"},Z={class:"col-md-6 col-12"},H={class:"mb-3"},K=e("label",{class:"form-label",id:"location-lat"},[p(" Широта "),e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно")],-1),X={class:"col-md-6 col-12"},Y={class:"mb-3"},Q=e("label",{class:"form-label",id:"location-lon"},[p(" Долгота "),e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно")],-1),W={class:"row"},ee={class:"col-12"},se={class:"mb-3"},te={class:"form-label",id:"location-description"},oe=e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),le={key:0,class:"text-gray-400 ml-3",style:{"font-size":"10px"}},ne={class:"row"},ie={class:"col-12 mb-3"},ae=e("h6",null,"Фотографии локаций",-1),de={class:"photo-preview d-flex justify-content-start flex-wrap w-100"},ce={for:"location-photos",style:{"margin-right":"10px"},class:"photo-loader ml-2 text-primary"},re=e("span",null,"+",-1),me={class:"mb-2 img-preview",style:{"margin-right":"10px"}},he={class:"remove"},pe=["onClick"],ue={class:"row"},_e={class:"col-12"},be={class:"btn btn-outline-success w-100",type:"submit"},ye={key:0},ge={key:1},ve={class:"col-md-4 mt-3"},fe=["onClick"],ke={class:"card-header d-flex justify-content-between"},Ce={key:0,class:"badge bg-success"},$e=["onClick"],Fe={class:"card-body"},we={key:0},Le={key:1},Ue={key:2,class:"w-100 d-flex"},Pe={class:"mb-2 img-preview",style:{"margin-right":"10px"}},Ie={key:3,class:"w-100 d-flex"},Ve={class:"mb-2 img-preview",style:{"margin-right":"10px"}},je={class:"row mt-2"},qe={class:"col-12"},Oe=["disabled"];function Me(o,s,t,d,c,u){const a=$("mask"),m=$("lazy");return l(),n("div",B,[e("div",z,[e("div",S,[e("div",D,[e("form",{onSubmit:s[6]||(s[6]=w((...i)=>u.submitLocation&&u.submitLocation(...i),["prevent"]))},[e("h6",null,"Локации к компании #"+b(t.company.title||"Не установлен"),1),e("div",E,[e("div",A,[e("div",N,[h(e("input",{class:"form-check-input","onUpdate:modelValue":s[0]||(s[0]=i=>c.locationForm.can_booking=i),type:"checkbox",value:"false",id:"flexCheckDefault"},null,512),[[P,c.locationForm.can_booking]]),T])]),e("div",x,[e("div",R,[J,h(e("input",{type:"text",class:"form-control",placeholder:"Адрес","aria-label":"Адрес",maxlength:"255","onUpdate:modelValue":s[1]||(s[1]=i=>c.locationForm.address=i),"aria-describedby":"location-address",required:""},null,512),[[_,c.locationForm.address]])])])]),e("div",G,[e("div",Z,[e("div",H,[K,h(e("input",{type:"text",class:"form-control",placeholder:"##.######","aria-label":"Широта",maxlength:"255","onUpdate:modelValue":s[2]||(s[2]=i=>c.locationForm.lat=i),"aria-describedby":"location-lat",required:""},null,512),[[a,"##.######"],[_,c.locationForm.lat]])])]),e("div",X,[e("div",Y,[Q,h(e("input",{type:"text",class:"form-control",placeholder:"##.######","aria-label":"Долгота",maxlength:"255","onUpdate:modelValue":s[3]||(s[3]=i=>c.locationForm.lon=i),"aria-describedby":"location-lon",required:""},null,512),[[a,"##.######"],[_,c.locationForm.lon]])])])]),e("div",W,[e("div",ee,[e("div",se,[e("label",te,[p(" Описание локации "),oe,c.locationForm.description?(l(),n("small",le," Длина текста "+b(c.locationForm.description.length),1)):r("",!0)]),h(e("textarea",{type:"text",class:"form-control",placeholder:"Описание локации","aria-label":"Описание локации",maxlength:"255","onUpdate:modelValue":s[4]||(s[4]=i=>c.locationForm.description=i),"aria-describedby":"location-description",required:""},`
                    `,512),[[_,c.locationForm.description]])])])]),e("div",ne,[e("div",ie,[ae,e("div",de,[e("label",ce,[re,e("input",{type:"file",id:"location-photos",multiple:"",accept:"image/*",onChange:s[5]||(s[5]=(...i)=>u.onChangePhotos&&u.onChangePhotos(...i)),style:{display:"none"}},null,32)]),c.locationForm.photos?(l(!0),n(y,{key:0},g(c.locationForm.photos,(i,F)=>(l(),n("div",me,[h(e("img",null,null,512),[[m,u.getPhoto(i).imageUrl]]),e("div",he,[e("a",{onClick:f=>u.removePhoto(F)},"Удалить",8,pe)])]))),256)):r("",!0)])])]),e("div",ue,[e("div",_e,[e("button",be,[c.locationForm.id?(l(),n("span",ye,"Обновить расположение")):(l(),n("span",ge,"Добавить расположение"))])])])],32)]),c.locations?(l(!0),n(y,{key:0},g(c.locations,(i,F)=>(l(),n("div",ve,[e("div",{class:"card",onClick:f=>u.selectLocation(i)},[e("div",ke,[e("h6",null,[p("Адрес локации "),e("strong",null,b(i.address||"Не указано"),1),p(" (ш:"+b(i.lat)+",д:"+b(i.lon)+") ",1),i.can_booking?(l(),n("span",Ce,"Можно забронировать столик")):r("",!0)]),e("a",{class:"cursor-pointer",onClick:f=>u.removeItem(F)},"Удалить",8,$e)]),e("div",Fe,[i.location_channel?(l(),n("p",we,[p("Канал заведения "),e("strong",null,b(i.location_channel),1)])):r("",!0),e("p",null,b(i.description||"Не указано"),1),i.photos?(l(),n("h6",Le,"Фотографии локаций")):r("",!0),i.photos?(l(),n("div",Ue,[i.photos.length>0?(l(!0),n(y,{key:0},g(i.photos,(f,U)=>(l(),n("div",Pe,[h(e("img",null,null,512),[[m,u.getPhoto(f).imageUrl]])]))),256)):r("",!0)])):r("",!0),i.images?(l(),n("div",Ie,[i.images.length>0?(l(!0),n(y,{key:0},g(i.images,(f,U)=>(l(),n("div",Ve,[h(e("img",null,null,512),[[m,"/images-by-company-id/"+t.company.id+"/"+f]])]))),256)):r("",!0)])):r("",!0)])],8,fe)]))),256)):r("",!0)]),e("div",je,[e("div",qe,[e("button",{onClick:s[7]||(s[7]=(...i)=>u.submitLocations&&u.submitLocations(...i)),disabled:c.locations.length===0&&c.deletedLocations.length===0,class:"btn btn-success p-3 w-100"}," Сохранить локации клиента ",8,Oe)])])])])}const Be=O(M,[["render",Me]]);const ze={key:0,class:"row mb-2"},Se={class:"col-12"},De={key:1,class:"row mb-2"},Ee={class:"col-12"},Ae={class:"btn-group w-100",role:"group","aria-label":"Basic outlined example"},Ne={class:"row"},Te={class:"col-6"},xe={class:"row"},Re={class:"col-12 mb-3"},Je={class:"form-label",id:"company-title"},Ge=e("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),Ze=e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),He={class:"col-12 mb-3"},Ke={class:"form-label",id:"company-title"},Xe=e("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),Ye=e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),Qe=["value"],We={class:"col-12"},es={class:"mb-3"},ss={class:"form-label",id:"company-slug"},ts=e("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),os=e("div",null,[p("Название компании на АНГЛИЙСКОМ"),e("br"),p(" без пробелов! можно использовать _"),e("br"),p(" Должно быть уникальным! Не отображается пользователю. ")],-1),ls=e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),ns={class:"col-12"},is={class:"mb-3"},as={class:"form-label",id:"company-description"},ds=e("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),cs=e("div",null,'Добавится в раздел "О Нас"',-1),rs=e("span",{class:"badge rounded-pill text-bg-danger m-0"},"Нужно",-1),ms={key:0,class:"text-gray-400 ml-3",style:{"font-size":"10px"}},hs={class:"col-12"},ps={class:"mb-3"},us={class:"form-label",id:"company-address"},_s=e("i",{class:"fa-regular fa-circle-question mr-1"},null,-1),bs=e("div",null,[p("Где находится главное заведение компании!"),e("br"),p('Можно не указывать, т.к. есть еще "Локации" ')],-1),ys={class:"col-12"},gs={class:"mb-3"},vs=e("label",{class:"form-label",id:"company-email"},"Основная почта компании",-1),fs={class:"col-12"},ks={class:"mb-3"},Cs=e("label",{class:"form-label",id:"company-manager"},"Менеджер компании",-1),$s={class:"col-12"},Fs={class:"card mb-3"},ws=e("div",{class:"card-header"},[e("h6",null,[p("Логотип компании "),e("span",{class:"badge rounded-pill text-bg-warning m-0"},"Желательно")])],-1),Ls={class:"card-body d-flex justify-content-start"},Us={for:"photos",style:{"margin-right":"10px"},class:"photo-loader text-primary ml-2"},Ps={key:0,class:"mb-2 img-preview",style:{"margin-right":"10px"}},Is={class:"remove"},Vs=e("i",{class:"fa-regular fa-trash-can"},null,-1),js=[Vs],qs={key:1,class:"mb-2 img-preview",style:{"margin-right":"10px"}},Os={class:"remove"},Ms=e("i",{class:"fa-regular fa-trash-can"},null,-1),Bs=[Ms],zs={class:"col-6"},Ss={class:"row"},Ds={class:"col-12"},Es={class:"card mb-3"},As=e("div",{class:"card-header"},[e("h6",null,"Телефонные номера")],-1),Ns={class:"card-body"},Ts=e("div",{class:"row"},[e("div",{class:"col-12"},[e("h6",null,"Телефонный номер")])],-1),xs={class:"col-10"},Rs={class:"mb-3"},Js=["onUpdate:modelValue","aria-describedby"],Gs={class:"col-2"},Zs=["onClick"],Hs=e("i",{class:"fa-regular fa-trash-can"},null,-1),Ks=[Hs],Xs={class:"row"},Ys={class:"col-12"},Qs={class:"col-12"},Ws={class:"card mb-3"},et=e("div",{class:"card-header"},[e("h6",null,"Ссылки на соц. сети")],-1),st={class:"card-body"},tt=e("div",{class:"row"},[e("div",{class:"col-12"},[e("h6",null,"Ссылка")])],-1),ot={class:"col-10"},lt={class:"mb-3"},nt=["onUpdate:modelValue","aria-describedby"],it={class:"col-2"},at=["onClick"],dt=e("i",{class:"fa-regular fa-trash-can"},null,-1),ct=[dt],rt={class:"row"},mt={class:"col-12"},ht={class:"col-12"},pt={class:"card mb-3"},ut=e("div",{class:"card-header"},[e("h6",null,"График работы")],-1),_t={class:"card-body"},bt={class:"row"},yt={class:"col-12 d-flex justify-content-between align-items-center"},gt=e("h6",null,"День недели ",-1),vt={class:"col-10"},ft={class:"mb-3"},kt=["onUpdate:modelValue","aria-describedby"],Ct={class:"col-2"},$t=["onClick"],Ft=e("i",{class:"fa-regular fa-trash-can"},null,-1),wt=[Ft],Lt={class:"row"},Ut={class:"col-12"},Pt=["disabled"],It={class:"row"},Vt={class:"col-12"},jt={type:"submit",class:"btn btn-outline-success w-100 p-3"},qt={key:0},Ot={key:1},Mt={key:3},Bt={props:["company","editor"],data(){return{step:0,load:!1,photo:null,removedImage:null,need_reset:!1,vat_codes:[{id:1,title:"Общая система налогообложения"},{id:2,title:"Упрощенная (УСН, доходы)"},{id:3,title:"Упрощенная (УСН, доходы минус расходы)"},{id:4,title:"Единый налог на вмененный доход (ЕНВД)"},{id:5,title:"Единый сельскохозяйственный налог (ЕСН)"},{id:6,title:"Патентная система налогообложения"}],companyForm:{id:null,title:null,slug:null,description:null,address:null,phones:[""],links:[""],email:null,vat_code:1,schedule:[],manager:null}}},watch:{companyForm:{handler(o){this.need_reset=!0},deep:!0}},mounted(){this.company&&this.$nextTick(()=>{this.companyForm={id:this.company.id||null,title:this.company.title||null,slug:this.company.slug||null,image:this.company.image||null,description:this.company.description||null,address:this.company.address||null,phones:this.company.phones||[""],links:this.company.links||[""],email:this.company.email||null,vat_code:this.company.vat_code||1,schedule:this.company.schedule||[],manager:this.company.manager||null}})},methods:{resetForm(){this.photo=null,this.removedImage=null,this.companyForm={id:null,title:null,slug:null,description:null,address:null,phones:[""],links:[""],email:null,schedule:[],manager:null,vat_code:1},this.$nextTick(()=>{this.need_reset=!1})},getPhoto(){return{imageUrl:URL.createObjectURL(this.photo)}},onChangePhotos(o){const s=o.target.files;this.photo=s[0],this.companyForm.image=null},schedulePlaceholder(){this.companyForm.schedule.length>0?this.companyForm.schedule=[]:this.companyForm.schedule=["Понедельник - с 8:00 до 20:00","Вторник - с 8:00 до 20:00","Среда - с 8:00 до 20:00","Четверг - с 8:00 до 20:00","Пятница - с 8:00 до 20:00","Суббота - с 8:00 до 20:00","Воскресенье - выходной"]},removeCompanyImage(){this.removedImage=this.companyForm.image,this.companyForm.image=null},addItem(o){this.companyForm[o].push("")},removeItem(o,s){this.companyForm[o].splice(s,1)},submitForm(){let o=new FormData;Object.keys(this.companyForm).forEach(s=>{const t=this.companyForm[s]||"";typeof t=="object"?o.append(s,JSON.stringify(t)):o.append(s,t)}),o.append("company_logo",this.photo),this.removedImage!=null&&o.append("removed_image",this.removedImage),this.$store.dispatch(this.companyForm.id===null?"createCompany":"updateCompany",{companyForm:o}).then(s=>{this.$emit("callback",s.data),this.$notify("Компания успешно создана")}).catch(s=>{})}}},zt=Object.assign(Bt,{__name:"CompanyForm",setup(o){return(s,t)=>{const d=I("Popper"),c=$("lazy"),u=$("mask");return l(),n(y,null,[s.need_reset&&!o.editor?(l(),n("div",ze,[e("div",Se,[e("button",{type:"button",onClick:t[0]||(t[0]=(...a)=>s.resetForm&&s.resetForm(...a)),class:"btn btn-outline-danger"},"Новый клиент \\ очистка формы")])])):r("",!0),o.editor?(l(),n("div",De,[e("div",Ee,[e("div",Ae,[e("button",{type:"button",class:C([{"btn-primary border-white text-white":s.step===0},"btn btn-outline-primary"]),onClick:t[1]||(t[1]=a=>s.step=0)},"Информация о компании ",2),e("button",{type:"button",class:C([{"btn-primary border-white text-white":s.step===1},"btn btn-outline-primary"]),onClick:t[2]||(t[2]=a=>s.step=1)},"Информация о расположении ",2)])])])):r("",!0),s.step===0?(l(),n("form",{key:2,onSubmit:t[17]||(t[17]=w((...a)=>s.submitForm&&s.submitForm(...a),["prevent"]))},[e("div",Ne,[e("div",Te,[e("div",xe,[e("div",Re,[e("label",Je,[k(d,{content:"Название вашей компании"},{default:v(()=>[Ge]),_:1}),p(" Название компании "),Ze]),h(e("input",{type:"text",class:"form-control",placeholder:"Название","aria-label":"Название","onUpdate:modelValue":t[3]||(t[3]=a=>s.companyForm.title=a),maxlength:"255","aria-describedby":"company-title",required:""},null,512),[[_,s.companyForm.title]])]),e("div",He,[e("label",Ke,[k(d,{content:"Тип налогооблажения вашей компании"},{default:v(()=>[Xe]),_:1}),p(" Тип налогооблажения "),Ye]),h(e("select",{type:"text",class:"form-control","aria-label":"Выберите налогооблажение","onUpdate:modelValue":t[4]||(t[4]=a=>s.companyForm.vat_code=a),"aria-describedby":"company-vat-code",required:""},[(l(!0),n(y,null,g(s.vat_codes,(a,m)=>(l(),n("option",{value:a.id},b(a.title||"Не указано"),9,Qe))),256))],512),[[V,s.companyForm.vat_code]])]),e("div",We,[e("div",es,[e("label",ss,[k(d,null,{content:v(()=>[os]),default:v(()=>[ts]),_:1}),p(" Название компании латиницей (домен компании) "),ls]),h(e("input",{type:"text",class:"form-control",placeholder:"Мнемоническое имя","aria-label":"Мнемоническое имя",pattern:"^[a-zA-Z][a-zA-Z0-9-_]{1,40}$","onUpdate:modelValue":t[5]||(t[5]=a=>s.companyForm.slug=a),maxlength:"255","aria-describedby":"company-slug",required:""},null,512),[[_,s.companyForm.slug]])])]),e("div",ns,[e("div",is,[e("label",as,[k(d,null,{content:v(()=>[cs]),default:v(()=>[ds]),_:1}),p(" Описание компании "),rs,s.companyForm.description?(l(),n("small",ms," Длина текста "+b(s.companyForm.description.length),1)):r("",!0)]),h(e("textarea",{type:"text",class:"form-control",placeholder:"Описание компании","aria-label":"Описание компании","onUpdate:modelValue":t[6]||(t[6]=a=>s.companyForm.description=a),"aria-describedby":"company-description",required:""},`
                    `,512),[[_,s.companyForm.description]])])]),e("div",hs,[e("div",ps,[e("label",us,[k(d,null,{content:v(()=>[bs]),default:v(()=>[_s]),_:1}),p(" Основной адрес компании")]),h(e("input",{type:"text",class:"form-control",placeholder:"Адрес","aria-label":"Адрес",maxlength:"255","onUpdate:modelValue":t[7]||(t[7]=a=>s.companyForm.address=a),"aria-describedby":"company-address"},null,512),[[_,s.companyForm.address]])])]),e("div",ys,[e("div",gs,[vs,h(e("input",{type:"email",class:"form-control",placeholder:"Почтовый адрес","aria-label":"Почтовый адрес",maxlength:"255","onUpdate:modelValue":t[8]||(t[8]=a=>s.companyForm.email=a),"aria-describedby":"company-email"},null,512),[[_,s.companyForm.email]])])]),e("div",fs,[e("div",ks,[Cs,h(e("input",{type:"text",class:"form-control",placeholder:"Имя менеджера","aria-label":"Имя менеджера","onUpdate:modelValue":t[9]||(t[9]=a=>s.companyForm.manager=a),maxlength:"255","aria-describedby":"company-manager"},null,512),[[_,s.companyForm.manager]])])]),e("div",$s,[e("div",Fs,[ws,e("div",Ls,[e("label",Us,[p(" + "),e("input",{type:"file",id:"photos",accept:"image/*",onChange:t[10]||(t[10]=(...a)=>s.onChangePhotos&&s.onChangePhotos(...a)),style:{display:"none"}},null,32)]),s.photo?(l(),n("div",Ps,[h(e("img",null,null,512),[[c,s.getPhoto().imageUrl]]),e("div",Is,[e("a",{onClick:t[11]||(t[11]=a=>s.photo=null)},js)])])):r("",!0),s.companyForm.image?(l(),n("div",qs,[h(e("img",null,null,512),[[c,"/images/"+s.companyForm.slug+"/"+s.companyForm.image]]),e("div",Os,[e("a",{onClick:t[12]||(t[12]=(...a)=>s.removeCompanyImage&&s.removeCompanyImage(...a))},Bs)])])):r("",!0)])])])])]),e("div",zs,[e("div",Ss,[e("div",Ds,[e("div",Es,[As,e("div",Ns,[Ts,(l(!0),n(y,null,g(s.companyForm.phones,(a,m)=>(l(),n("div",{class:"row",key:"phone"+m},[e("div",xs,[e("div",Rs,[h(e("input",{type:"text",class:"form-control",placeholder:"+7(000)000-00-00","aria-label":"Номер телефона",maxlength:"255","onUpdate:modelValue":i=>s.companyForm.phones[m]=i,"aria-describedby":"company-phone-"+m},null,8,Js),[[u,"+7(###)###-##-##"],[_,s.companyForm.phones[m]]])])]),e("div",Gs,[e("button",{type:"button",onClick:i=>s.removeItem("phones",m),class:"btn btn-outline-danger w-100"},Ks,8,Zs)])]))),128)),e("div",Xs,[e("div",Ys,[e("button",{type:"button",onClick:t[13]||(t[13]=a=>s.addItem("phones")),class:"btn btn-outline-success w-100"},"Добавить еще номер ")])])])])]),e("div",Qs,[e("div",Ws,[et,e("div",st,[tt,(l(!0),n(y,null,g(s.companyForm.links,(a,m)=>(l(),n("div",{class:"row",key:"link"+m},[e("div",ot,[e("div",lt,[h(e("input",{type:"text",class:"form-control",placeholder:"Ссылка на соц.сеть","aria-label":"Ссылка на соц.сеть",maxlength:"255","onUpdate:modelValue":i=>s.companyForm.links[m]=i,"aria-describedby":"company-link-"+m},null,8,nt),[[_,s.companyForm.links[m]]])])]),e("div",it,[e("button",{type:"button",onClick:i=>s.removeItem("links",m),class:"btn btn-outline-danger w-100"},ct,8,at)])]))),128)),e("div",rt,[e("div",mt,[e("button",{type:"button",onClick:t[14]||(t[14]=a=>s.addItem("links")),class:"btn btn-outline-success w-100"},"Добавить еще ссылку ")])])])])]),e("div",ht,[e("div",pt,[ut,e("div",_t,[e("div",bt,[e("div",yt,[gt,e("a",{class:"btn btn-link",onClick:t[15]||(t[15]=(...a)=>s.schedulePlaceholder&&s.schedulePlaceholder(...a))},"Заполнить\\очистить")])]),(l(!0),n(y,null,g(s.companyForm.schedule,(a,m)=>(l(),n("div",{class:"row",key:"link"+m},[e("div",vt,[e("div",ft,[h(e("input",{type:"text",class:"form-control",placeholder:"День недели и время работы","aria-label":"День недели и время работы",maxlength:"255","onUpdate:modelValue":i=>s.companyForm.schedule[m]=i,"aria-describedby":"company-schedule-"+m},null,8,kt),[[_,s.companyForm.schedule[m]]])])]),e("div",Ct,[e("button",{type:"button",onClick:i=>s.removeItem("schedule",m),class:"btn btn-outline-danger w-100"},wt,8,$t)])]))),128)),e("div",Lt,[e("div",Ut,[e("button",{type:"button",disabled:s.companyForm.schedule.length===7,onClick:t[16]||(t[16]=a=>s.addItem("schedule")),class:"btn btn-outline-success w-100"},"Добавить еще время работы ",8,Pt)])])])])])])])]),e("div",It,[e("div",Vt,[e("button",jt,[s.companyForm.id===null?(l(),n("span",qt,"Создать компанию")):(l(),n("span",Ot,"Обновить компанию"))])])])],32)):r("",!0),s.step===1?(l(),n("div",Mt,[o.company&&!s.load?(l(),L(Be,{key:0,company:o.company},null,8,["company"])):r("",!0)])):r("",!0)],64)}}}),St={key:0},Dt={class:"row"},Et={class:"d-flex"},At={class:"dropdown mr-2"},Nt=e("button",{class:"btn btn-outline-primary dropdown-toggle",type:"button",id:"dropdownMenuButton1","data-bs-toggle":"dropdown","aria-expanded":"false"}," Фильтры ",-1),Tt={class:"dropdown-menu","aria-labelledby":"dropdownMenuButton1"},xt=["onClick"],Rt={class:"input-group mb-3"},Jt={key:0,class:"mt-2"},Gt={class:"badge bg-info mr-1"},Zt=["onClick"],Ht=e("i",{class:"fa-solid fa-xmark"},null,-1),Kt=[Ht],Xt={key:0,class:"row"},Yt={class:"col-12 mb-3"},Qt={class:"list-group w-100"},Wt=["onClick"],eo=["onClick"],so=e("i",{class:"fa-solid fa-pen-to-square"},null,-1),to=[so],oo=["onClick"],lo=e("i",{class:"fa-solid fa-boxes-packing"},null,-1),no=[lo],io=["onClick"],ao=e("i",{class:"fa-solid fa-box-open"},null,-1),co=[ao],ro={class:"col-12"},mo={class:"modal fade",id:"edit-company-modal",tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},ho={class:"modal-dialog modal-lg"},po={class:"modal-content"},uo=e("div",{class:"modal-header"},[e("h5",{class:"modal-title",id:"exampleModalLabel"},"Сохранение клиента"),e("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"})],-1),_o={class:"modal-body"},bo={key:0,class:"row"},yo={class:"col-12 mb-2"},go=e("div",{class:"modal-footer"},[e("button",{type:"button",class:"btn btn-secondary","data-bs-dismiss":"modal"},"Не сохранять")],-1),vo={props:["selected"],data(){return{show:!0,loading:!0,companies:[],search:null,editCompanyModal:null,filters:[{name:"Активные",icon:"fa-brands fa-telegram",slug:"active"},{name:"Архивные",icon:"fa-solid fa-box-archive",slug:"archive"}],selectedFilters:[],companies_paginate_object:null,selectedCompany:null}},computed:{...j(["getCompanies","getCompaniesPaginateObject"]),filteredCompanies(){if(!this.companies)return[];if(this.selectedFilters.length===0&&this.search==null)return this.companies;if(this.selectedFilters.length===0&&this.search!=null)return this.companies.filter(s=>(s.title||"").trim().toLowerCase().indexOf(this.search.trim().toLowerCase())!==-1);let o=[];return this.selectedFilters.forEach(s=>{switch(s.slug){case"active":this.companies.filter(t=>t.deleted_at==null).forEach(t=>{o.push(t)});break;case"archive":this.companies.filter(t=>t.deleted_at!=null).forEach(t=>{o.push(t)});break}}),this.search==null?o:o.filter(s=>(s.title||"").trim().toLowerCase().indexOf(this.search.trim().toLowerCase())!==-1)}},mounted(){this.loadCompanies(),this.selectFilter("active"),this.editCompanyModal=new bootstrap.Modal(document.getElementById("edit-company-modal"),{})},methods:{companyCallback(o){this.selectedCompany=null,this.editCompanyModal.hide()},editClient(o){this.selectedCompany=o,this.editCompanyModal.show()},addToArchive(o){this.$store.dispatch("removeCompany",{companyId:o}).then(s=>{let t=this.companies_paginate_object.meta.current_page||0;this.loadCompanies(t),this.$notify("Указанный клиент успешно перемещен в архив")})},extractFromArchive(o){this.$store.dispatch("restoreCompany",{companyId:o}).then(s=>{let t=this.companies_paginate_object.meta.current_page||0;this.loadCompanies(t),this.$notify("Указанный клиент успешно перемещен из архива")})},selectFilter(o){let s=this.filters.find(t=>t.slug===o);s&&this.selectedFilters.filter(t=>t.slug===o).length===0&&this.selectedFilters.push(s)},removeSelectedFilter(o){let s=this.selectedFilters.findIndex(t=>t.slug===o);this.selectedFilters.splice(s,1)},selectCompany(o){this.$store.dispatch("updateCurrentCompany",{company:o}),this.$emit("callback",o),this.show=!1,this.$notify("Вы выбрали клиента из списка! Все остальные действия будут производится для этой компании.")},nextCompanies(o){this.loadCompanies(o)},loadCompanies(o=0){this.loading=!0,this.$store.dispatch("loadCompanies",{dataObject:{search:this.search},page:o}).then(s=>{this.loading=!1,this.companies=this.getCompanies,this.companies_paginate_object=this.getCompaniesPaginateObject}).catch(()=>{this.loading=!1})}}},$o=Object.assign(vo,{__name:"CompanyList",setup(o){return(s,t)=>(l(),n(y,null,[s.show?(l(),n("div",St,[e("div",Dt,[e("div",Et,[e("div",At,[Nt,e("ul",Tt,[(l(!0),n(y,null,g(s.filters,d=>(l(),n("li",null,[e("a",{class:"dropdown-item",onClick:c=>s.selectFilter(d.slug),href:"#filter"},[e("i",{class:C([d.icon,"mr-2"])},null,2),p(" "+b(d.name||"Не указано"),1)],8,xt)]))),256))])]),e("div",Rt,[h(e("input",{type:"search",class:"form-control",placeholder:"Поиск компании","aria-label":"Поиск компании","onUpdate:modelValue":t[0]||(t[0]=d=>s.search=d),"aria-describedby":"button-addon2"},null,512),[[_,s.search]]),e("button",{class:"btn btn-outline-secondary",onClick:t[1]||(t[1]=(...d)=>s.loadCompanies&&s.loadCompanies(...d)),type:"button",id:"button-addon2"},"Найти ")])]),s.selectedFilters.length>0?(l(),n("p",Jt,[(l(!0),n(y,null,g(s.selectedFilters,d=>(l(),n("span",Gt,[p(b(d.name||"не указан")+" ",1),e("a",{onClick:c=>s.removeSelectedFilter(d.slug),class:"ml-1 text-white",href:"#filter"},Kt,8,Zt)]))),256))])):r("",!0)]),s.companies.length>0?(l(),n("div",Xt,[e("div",Yt,[e("ul",Qt,[(l(!0),n(y,null,g(s.filteredCompanies,(d,c)=>(l(),n("li",{class:C(["list-group-item btn mb-1 d-flex justify-between",{"btn-outline-info":d.deleted_at==null,"btn-outline-danger border-danger":d.deleted_at!=null,"bg-success":o.selected==d.id}])},[e("span",{onClick:u=>s.selectCompany(d),class:C({"text-danger":d.deleted_at!=null})}," #"+b(d.id||"-")+" "+b(d.title||"Не указано")+" ("+b(d.slug||"Не указано")+") ",11,Wt),e("div",null,[e("button",{class:"btn btn-info mr-1",type:"button",onClick:u=>s.editClient(d),title:"В архив"},to,8,eo),d.deleted_at==null?(l(),n("button",{key:0,class:"btn btn-outline-info",type:"button",onClick:u=>s.addToArchive(d.id),title:"В архив"},no,8,oo)):r("",!0),d.deleted_at!=null?(l(),n("button",{key:1,class:"btn btn-outline-info",type:"button",onClick:u=>s.extractFromArchive(d.id),title:"Из архива"},co,8,io)):r("",!0)])],2))),256))])]),e("div",ro,[s.companies_paginate_object?(l(),L(q,{key:0,onPagination_page:s.nextCompanies,pagination:s.companies_paginate_object},null,8,["onPagination_page","pagination"])):r("",!0)])])):r("",!0)])):r("",!0),e("div",mo,[e("div",ho,[e("div",po,[uo,e("div",_o,[s.selectedCompany?(l(),n("div",bo,[e("div",yo,[k(zt,{company:s.selectedCompany,editor:!1,onCallback:s.companyCallback},null,8,["company","onCallback"])])])):r("",!0)]),go])])])],64))}});export{Be as L,zt as _,$o as a};