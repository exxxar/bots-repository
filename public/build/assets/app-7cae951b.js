import{a as O,E as x,m as E,r as k,c as S,h as j,V as v,b as $,e as F,I,i as A,_ as u,d as T}from"./index.es-49ee4a70.js";import{u as s,c as C}from"./index-7247f9e7.js";window.axios=O;window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const d="/admin/templates";let B={};const D={},f={async loadTemplates(t){let r=`${d}/bots`;return s.makeAxiosFactory(r).then(o=>Promise.resolve(o)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async requestTelegramChannelId(t,r={dataObject:null}){let a=`${d}/telegram-channel-id`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadDescription(t){let r=`${d}/description`;return s.makeAxiosFactory(r).then(o=>Promise.resolve(o.data)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async loadMenuByBotId(t,r={botId:null}){let a=`${d}/image-menu/${r.botId}`;return s.makeAxiosFactory(a,"GET").then(e=>Promise.resolve(e.data.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadLocationsByCompany(t,r={companyId:null}){let a=`${d}/location/${r.companyId}`;return s.makeAxiosFactory(a,"GET").then(e=>Promise.resolve(e.data.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeKeyboardTemplate(t,r={templateId:null}){let a=`${d}/remove-keyboard-template/${r.templateId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async editKeyboardTemplate(t,r={keyboardForm:null}){let a=`${d}/edit-keyboard-template`;return s.makeAxiosFactory(a,"POST",r.keyboardForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createKeyboardTemplate(t,r={keyboardForm:null}){let a=`${d}/keyboard-template`;return s.makeAxiosFactory(a,"POST",r.keyboardForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createLocation(t,r={locationForm:null}){let a=`${d}/location`;return s.makeAxiosFactory(a,"POST",r.locationForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createImageMenu(t,r={menuForm:null}){let a=`${d}/image-menu`;return s.makeAxiosFactory(a,"POST",r.menuForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createBotLazy(t,r={botForm:null}){let a=`${d}/bot-lazy`;return s.makeAxiosFactory(a,"POST",r.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createBot(t,r={botForm:null}){let a=`${d}/bot`;return s.makeAxiosFactory(a,"POST",r.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotKeyboards(t,r={botId:null}){let a=`${d}/keyboards/${r.botId}`;return s.makeAxiosFactory(a).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotPages(t,r={botId:null}){let a=`${d}/pages/${r.botId}`;return s.makeAxiosFactory(a).then(e=>{const c=e.data;return Promise.resolve(c)}).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},L={setTemplates(t,r){t.templates=r||[],localStorage.setItem("cashman_templates",JSON.stringify(r))},setTemplatesPaginateObject(t,r){t.templates_paginate_object=r||[],localStorage.setItem("cashman_templates_paginate_object",JSON.stringify(r))}},G={state:B,mutations:L,actions:f,getters:D},b="/admin/companies";let N={companies:[],companies_paginate_object:null};const z={getCompanies:t=>t.companies||[],getCompanyById:t=>r=>t.companies.find(a=>a.id===r),getCompaniesPaginateObject:t=>t.companies_paginate_object||null},w={async loadCompanies(t,r={dataObject:null,page:0,size:50}){let a=r.page||0,o=r.size||50,e=`${b}?page=${a}&size=${o}`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setCompanies",l.data),delete l.data,t.commit("setCompaniesPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async updateCompany(t,r={companyForm:null}){let a=`${b}/company-update`;return s.makeAxiosFactory(a,"POST",r.companyForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createCompany(t,r={companyForm:null}){let a=`${b}/company`;return s.makeAxiosFactory(a,"POST",r.companyForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeCompany(t,r={companyId:null}){let a=`${b}/${r.companyId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreCompany(t,r={companyId:null}){let a=`${b}/restore/${r.companyId}`;return s.makeAxiosFactory(a,"GET").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},J={setCompanies(t,r){t.companies=r||[],localStorage.setItem("cashman_companies",JSON.stringify(r))},setCompaniesPaginateObject(t,r){t.companies_paginate_object=r||[],localStorage.setItem("cashman_companies_paginate_object",JSON.stringify(r))}},R={state:N,mutations:J,actions:w,getters:z},_="/admin/bots";let M={bots:[],bot_users:[],bots_paginate_object:null,bot_users_paginate_object:null};const V={getBots:t=>t.bots||[],getBotUsers:t=>t.bot_users||[],getBotById:t=>r=>t.bots.find(a=>a.id===r),getBotsPaginateObject:t=>t.bots_paginate_object||null,getBotUsersPaginateObject:t=>t.bot_users_paginate_object||null},U={async loadBots(t,r={dataObject:null,page:0,size:50}){let a=r.page||0,o=r.size||50,e=`${_}?page=${a}&size=${o}`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setBots",l.data),delete l.data,t.commit("setBotsPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async updateBot(t,r={botForm:null}){let a=`${_}/bot-update`;return s.makeAxiosFactory(a,"POST",r.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async testConnectionAmoCRM(t,r={dataObject:null}){let a=`${_}/test-amo`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async saveAmoCRM(t,r={amoForm:null}){let a=`${_}/save-amo`;return s.makeAxiosFactory(a,"POST",r.amoForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateBot(t,r={dataObject:{bot_id:null,company_id:null}}){let a=`${_}/duplicate`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeBot(t,r={botId:null}){let a=`${_}/${r.botId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async forceDeleteBot(t,r={botId:null}){let a=`${_}/force/${r.botId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreBot(t,r={botId:null}){let a=`${_}/restore/${r.botId}`;return s.makeAxiosFactory(a,"GET").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async changeUserStatus(t,r={dataObject:{botUserId:null,status:0}}){let a=`${_}/user-status`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotUsers(t,r={dataObject:{botId:null,search:null},page:0,size:12}){let a=r.page||0,e=`${_}/users?page=${a}&size=12`;return s.makeAxiosFactory(e,"POST",r.dataObject).then(i=>{let m=i.data;return t.commit("setBotUsers",m.data),delete m.data,t.commit("setBotUsersPaginateObject",m),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))}},K={setBots(t,r){t.bots=r||[],localStorage.setItem("cashman_bots",JSON.stringify(r))},setBotUsers(t,r){t.bot_users=r||[],localStorage.setItem("cashman_bot_users",JSON.stringify(r))},setBotUsersPaginateObject(t,r){t.bot_users_paginate_object=r||[],localStorage.setItem("cashman_bot_users_paginate_object",JSON.stringify(r))},setBotsPaginateObject(t,r){t.bots_paginate_object=r||[],localStorage.setItem("cashman_bots_paginate_object",JSON.stringify(r))}},Y={state:M,mutations:K,actions:U,getters:V},h="/admin/pages";let q={pages:[],pages_paginate_object:null};const W={getPages:t=>t.pages||[],getPageById:t=>r=>t.pages.find(a=>a.id===r),getPagesPaginateObject:t=>t.pages_paginate_object||null},X={async loadPages(t,r={dataObject:{botId:null,search:null},page:0,size:12}){let a=r.page||0,e=`${h}?page=${a}&size=12`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setPages",l.data),delete l.data,t.commit("setPagesPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async updatePage(t,r={pageForm:null}){let a=`${h}/page-update`;return s.makeAxiosFactory(a,"POST",r.pageForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicatePage(t,r={dataObject:{pageId:null}}){let a=`${h}/duplicate/${r.dataObject.pageId}`;return s.makeAxiosFactory(a,"POST").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removePage(t,r={dataObject:{pageId:null}}){let a=`${h}/${r.dataObject.pageId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createPage(t,r={pageForm:null}){let a=`${h}/page`;return s.makeAxiosFactory(a,"POST",r.pageForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},H={setPages(t,r){t.pages=r||[],localStorage.setItem("cashman_pages",JSON.stringify(r))},setPagesPaginateObject(t,r){t.pages_paginate_object=r||[],localStorage.setItem("cashman_pages_paginate_object",JSON.stringify(r))}},Z={state:q,mutations:H,actions:X,getters:W},g="/admin/dialog-groups";let Q={dialog_groups:[],dialog_groups_paginate_object:null};const ee={getDialogGroups:t=>t.dialog_groups||[],getDialogGroupById:t=>r=>t.dialog_groups.find(a=>a.id===r),getDialogGroupsPaginateObject:t=>t.dialog_groups_paginate_object||null},te={async loadDialogGroups(t,r={dataObject:{botId:null,search:null},page:0,size:12}){let a=r.page||0,e=`${g}?page=${a}&size=12`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setDialogGroups",l.data),delete l.data,t.commit("setDialogGroupsPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async updateDialogGroup(t,r={dialogGroupForm:null}){let a=`${g}/update-group`;return s.makeAxiosFactory(a,"POST",r.dialogGroupForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async updateDialogCommand(t,r={dialogCommandForm:null}){let a=`${g}/update-dialog`;return s.makeAxiosFactory(a,"POST",r.dialogCommandForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeDialogGroup(t,r={dataObject:{dialogGroupId:null}}){let a=`${g}/remove-group/${r.dataObject.dialogGroupId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeDialogCommand(t,r={dataObject:{dialogCommandId:null}}){let a=`${g}/remove-dialog/${r.dataObject.dialogCommandId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createDialogGroup(t,r={dialogGroupForm:null}){let a=`${g}/add-group`;return s.makeAxiosFactory(a,"POST",r.dialogGroupForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async swapDialogCommand(t,r={swapForm:null}){let a=`${g}/swap-dialog`;return s.makeAxiosFactory(a,"POST",r.swapForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async unlinkDialogCommand(t,r={dataObject:null}){let a=`${g}/unlink-dialog`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async swapDialogGroup(t,r={swapForm:null}){let a=`${g}/swap-group`;return s.makeAxiosFactory(a,"POST",r.swapForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async stopDialogs(t){let r=`${g}/stop-dialogs`;return s.makeAxiosFactory(r,"POST").then(o=>Promise.resolve(o.data)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async createDialogCommand(t,r={dialogCommandForm:null}){let a=`${g}/add-dialog`;return s.makeAxiosFactory(a,"POST",r.dialogCommandForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateDialogCommand(t,r={dataObject:null}){let a=`${g}/duplicate-dialog`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async attachDialogCommandToSlug(t,r={dataObject:null}){let a=`${g}/attach-dialog-to-slug`;return s.makeAxiosFactory(a,"POST",r.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},re={setDialogGroups(t,r){t.dialog_groups=r||[],localStorage.setItem("cashman_dialog_groups",JSON.stringify(r))},setDialogGroupsPaginateObject(t,r){t.dialog_groups_paginate_object=r||[],localStorage.setItem("cashman_dialog_groups_paginate_object",JSON.stringify(r))}},ae={state:Q,mutations:re,actions:te,getters:ee},p="/admin/slugs";let oe={slugs:[],global_slugs:[],slugs_paginate_object:null,global_slugs_paginate_object:null};const se={getSlugs:t=>t.slugs||[],getGlobalSlugs:t=>t.global_slugs||[],getSlugById:t=>r=>t.slugs.find(a=>a.id===r),getSlugsPaginateObject:t=>t.slugs_paginate_object||null,getGlobalSlugsPaginateObject:t=>t.global_slugs_paginate_object||null},ne={async loadGlobalSlugs(t,r={dataObject:{search:null},page:0,size:12}){let a=r.page||0,e=`${p}/global-list?page=${a}&size=12`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setGlobalSlugs",l.data),delete l.data,t.commit("setGlobalSlugsPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async loadSlugs(t,r={dataObject:{botId:null,search:null,needGlobal:!1},page:0,size:12}){let a=r.page||0,e=`${p}?page=${a}&size=12`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setSlugs",l.data),delete l.data,t.commit("setSlugsPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async updateSlug(t,r={slugForm:null}){let a=`${p}/slug-update`;return s.makeAxiosFactory(a,"POST",r.slugForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateSlug(t,r={dataObject:{slugId:null}}){let a=`${p}/duplicate/${r.dataObject.slugId}`;return s.makeAxiosFactory(a,"POST").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeSlug(t,r={dataObject:{slugId:null}}){let a=`${p}/${r.dataObject.slugId}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async refreshSlugParams(t,r={dataObject:{slugId:null}}){let a=`${p}/reload-params/${r.dataObject.slugId}`;return s.makeAxiosFactory(a,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createSlug(t,r={slugForm:null}){let a=`${p}/slug`;return s.makeAxiosFactory(a,"POST",r.slugForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},ie={setSlugs(t,r){t.slugs=r||[],localStorage.setItem("cashman_slugs",JSON.stringify(r))},setSlugsPaginateObject(t,r){t.slugs_paginate_object=r||[],localStorage.setItem("cashman_slugs_paginate_object",JSON.stringify(r))},setGlobalSlugs(t,r){t.global_slugs=r||[],localStorage.setItem("cashman_global_slugs",JSON.stringify(r))},setGlobalSlugsPaginateObject(t,r){t.global_slugs_paginate_object=r||[],localStorage.setItem("cashman_global_slugs_paginate_object",JSON.stringify(r))}},ce={state:oe,mutations:ie,actions:ne,getters:se},P="/admin/shop/products";let le={products:[],product_categories:[],products_paginate_object:null};const me={getProducts:t=>t.products||[],getProductCategories:t=>t.product_categories||[],getProductById:t=>r=>t.products.find(a=>a.id===r),getProductsPaginateObject:t=>t.products_paginate_object||null},ue={async loadProduct(t,r={dataObject:{productId:null}}){let a=`${P}/${r.dataObject.productId}`,o="GET";return s.makeAxiosFactory(a,o).then(c=>Promise.resolve(c.data)).catch(c=>(t.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))},async loadProducts(t,r={dataObject:{bot_id:null},page:0,size:12}){let a=r.page||0,e=`${P}?page=${a}&size=12`,c="POST",i=r.dataObject;return s.makeAxiosFactory(e,c,i).then(n=>{let l=n.data;return t.commit("setProducts",l.data),delete l.data,t.commit("setProductsPaginateObject",l),Promise.resolve()}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async loadProductCategories(t,r={dataObject:{bot_id:null}}){r.page;let a=`${P}/categories`,o="POST",e=r.dataObject;return s.makeAxiosFactory(a,o,e).then(i=>{let m=i.data;return t.commit("setProductCategories",m.data),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async loadRandomProducts(t,r={dataObject:{bot_id:null}}){let a=`${P}`,o="POST",e=r.dataObject;return s.makeAxiosFactory(a,o,e).then(i=>{let m=i.data;return t.commit("setProducts",m.data),delete m.data,t.commit("setProductsPaginateObject",m),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async saveProduct(t,r={productForm:null}){let a=`${P}/save`;return s.makeAxiosFactory(a,"POST",r.productForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeProduct(t,r){let a=`${P}/remove/${r}`;return s.makeAxiosFactory(a,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateProduct(t,r){let a=`${P}/duplicate/${r}`;return s.makeAxiosFactory(a,"POST").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},de={setProducts(t,r){t.products=r||[],localStorage.setItem("cashman_products",JSON.stringify(r))},setProductCategories(t,r){t.product_categories=r||[],localStorage.setItem("cashman_product_categories",JSON.stringify(r))},setProductsPaginateObject(t,r){t.products_paginate_object=r||[],localStorage.setItem("cashman_products_paginate_object",JSON.stringify(r))}},ge={state:le,mutations:de,actions:ue,getters:me},_e=C({state:{current_company:null,current_bot:null,errors:[]},getters:{getErrors:t=>t.errors||[],getCurrentCompany:t=>{let r=localStorage.getItem("store_current_company")?JSON.parse(localStorage.getItem("store_current_company")):null;return t.current_company||r||null},getCurrentBot:t=>{let r=localStorage.getItem("store_current_bot")?JSON.parse(localStorage.getItem("store_current_bot")):null;return t.current_bot||r||null}},actions:{async sendToChannel(t,r={mailForm:null}){let a="/send-to-channel";return s.makeAxiosFactory(a,"POST",r.mailForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeFile(t,r={file_path:null}){let a="/remove-file";return s.makeAxiosFactory(a,"POST",{...r}).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},updateCurrentCompany(t,r={company:null}){let a=localStorage.getItem("store_current_company")?JSON.parse(localStorage.getItem("store_current_company")):null;a=r.company||a||null,t.commit("setCurrentCompany",a)},resetCurrentCompany(t){t.commit("setCurrentCompany",null)},resetCurrentBot(t){t.commit("setCurrentBot",null)},updateCurrentBot(t,r={bot:null}){let a=localStorage.getItem("store_current_bot")?JSON.parse(localStorage.getItem("store_current_bot")):null;a=r.bot||a||null,t.commit("setCurrentBot",a)}},mutations:{setCurrentCompany(t,r){t.current_company=r||null,localStorage.setItem("store_current_company",JSON.stringify(r)),window.dispatchEvent(new CustomEvent("store_current_company-change-event"))},setCurrentBot(t,r){t.current_bot=r||null,localStorage.setItem("store_current_bot",JSON.stringify(r)),window.dispatchEvent(new CustomEvent("store_current_bot-change-event"))},setErrors(t,r){t.errors=r||[]}},modules:{templates:G,companies:R,bots:Y,pages:Z,dialogGroups:ae,slugs:ce,products:ge}}),pe=E();window.eventBus=pe;var y;const Pe=((y=window.document.getElementsByTagName("title")[0])==null?void 0:y.innerText)||"Laravel";x({title:t=>`${t} - ${Pe}`,resolve:t=>k(`./Pages/${t}.vue`,Object.assign({"./Pages/Auth/ConfirmPassword.vue":()=>u(()=>import("./ConfirmPassword-dc21c9d3.js"),["assets/ConfirmPassword-dc21c9d3.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-462fff52.js"]),"./Pages/Auth/ForgotPassword.vue":()=>u(()=>import("./ForgotPassword-5daae92e.js"),["assets/ForgotPassword-5daae92e.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-462fff52.js"]),"./Pages/Auth/Login.vue":()=>u(()=>import("./Login-77a4e849.js"),["assets/Login-77a4e849.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-462fff52.js"]),"./Pages/Auth/Register.vue":()=>u(()=>import("./Register-f0898255.js"),["assets/Register-f0898255.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-462fff52.js"]),"./Pages/Auth/ResetPassword.vue":()=>u(()=>import("./ResetPassword-cf31a50e.js"),["assets/ResetPassword-cf31a50e.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-462fff52.js"]),"./Pages/Auth/VerifyEmail.vue":()=>u(()=>import("./VerifyEmail-300dcc93.js"),["assets/VerifyEmail-300dcc93.js","assets/index.es-49ee4a70.js","assets/PrimaryButton-3eac42ef.js","assets/_plugin-vue_export-helper-c27b6911.js"]),"./Pages/BotPage.vue":()=>u(()=>import("./BotPage-a9dc9c27.js"),["assets/BotPage-a9dc9c27.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/BotList-081461e2.js","assets/Mail-fa88166f.js","assets/vue3-json-editor.esm.prod-55d6cbc3.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/TelegramChannelHelper-32b3aa69.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/Mail-166a059d.css","assets/SlugForm-c6a7a723.js","assets/Pagination-47410efa.js","assets/Pagination-4757d200.css","assets/BotList-ed550ed8.css","assets/CompanyList-8ae213fc.js"]),"./Pages/BotVisitCardConstructorPage.vue":()=>u(()=>import("./BotVisitCardConstructorPage-4326ca35.js"),["assets/BotVisitCardConstructorPage-4326ca35.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/_plugin-vue_export-helper-c27b6911.js","assets/BotVisitCardConstructorPage-8a909fea.css"]),"./Pages/ChatWindow.vue":()=>u(()=>import("./ChatWindow-9e5a49ff.js"),["assets/ChatWindow-9e5a49ff.js","assets/index.es-49ee4a70.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/ChatWindow-dadc2ae3.css"]),"./Pages/CompanyPage.vue":()=>u(()=>import("./CompanyPage-0589eeba.js"),["assets/CompanyPage-0589eeba.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/CompanyForm-44ecfeec.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/CompanyForm-28f51984.css","assets/CompanyList-8ae213fc.js","assets/Pagination-47410efa.js","assets/Pagination-4757d200.css"]),"./Pages/Dashboard.vue":()=>u(()=>import("./Dashboard-f19f9765.js"),["assets/Dashboard-f19f9765.js","assets/CompanyList-8ae213fc.js","assets/index.es-49ee4a70.js","assets/Pagination-47410efa.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/index-7247f9e7.js","assets/BotList-081461e2.js","assets/Mail-fa88166f.js","assets/vue3-json-editor.esm.prod-55d6cbc3.js","assets/v4-a960c1f4.js","assets/TelegramChannelHelper-32b3aa69.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/Mail-166a059d.css","assets/SlugForm-c6a7a723.js","assets/BotList-ed550ed8.css","assets/CompanyForm-44ecfeec.js","assets/CompanyForm-28f51984.css","assets/Dashboard-db3e581c.css"]),"./Pages/MailPage.vue":()=>u(()=>import("./MailPage-cdc3b126.js"),["assets/MailPage-cdc3b126.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/CompanyList-8ae213fc.js","assets/Pagination-47410efa.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/Mail-fa88166f.js","assets/vue3-json-editor.esm.prod-55d6cbc3.js","assets/v4-a960c1f4.js","assets/TelegramChannelHelper-32b3aa69.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/Mail-166a059d.css","assets/CompanyForm-28f51984.css"]),"./Pages/MainPage.vue":()=>u(()=>import("./MainPage-33f4f393.js"),["assets/MainPage-33f4f393.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/CompanyList-8ae213fc.js","assets/Pagination-47410efa.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/CompanyForm-28f51984.css"]),"./Pages/MediaPage.vue":()=>u(()=>import("./MediaPage-4da0555d.js"),["assets/MediaPage-4da0555d.js","assets/index.es-49ee4a70.js","assets/MainAdminLayout-46cdc4b2.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css"]),"./Pages/ScriptPage.vue":()=>u(()=>import("./ScriptPage-8d7507d1.js"),["assets/ScriptPage-8d7507d1.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/SlugForm-c6a7a723.js","assets/Pagination-47410efa.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/TelegramChannelHelper-32b3aa69.js","assets/TelegramChannelHelper-5ad24a6a.css"]),"./Pages/UserPage.vue":()=>u(()=>import("./UserPage-2a603066.js"),["assets/UserPage-2a603066.js","assets/MainAdminLayout-46cdc4b2.js","assets/index.es-49ee4a70.js","assets/index-7247f9e7.js","assets/MainAdminLayout-5ac8cdc0.css","assets/CompanyList-8ae213fc.js","assets/Pagination-47410efa.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/CompanyForm-28f51984.css"])})),setup({el:t,App:r,props:a,plugin:o}){const e=S({render:()=>T(r,a)});return e.config.globalProperties.$filters={timeAgo(c){return j(c).fromNow()},current(c){return j(c).format("YYYY-MM-DD")}},e.use(o).use(_e).use(v).use($).use(F).use(I,Ziggy).use(A,{loading:"/images/cashman.jpg",error:"/images/error.png"}).mount(t),e},progress:{color:"#4B5563"}});
