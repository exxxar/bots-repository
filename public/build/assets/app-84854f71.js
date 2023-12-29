import{a as E,c as v,E as k,m as S,r as $,b as A,h as x,V as F,d as I,e as T,I as C,i as D,_ as d,f}from"./index.es-77235f88.js";import{u as s}from"./index-9c1573ba.js";window.axios=E;window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const g="/admin/templates";let B={};const N={},L={async loadTemplates(t){let a=`${g}/bots`;return s.makeAxiosFactory(a).then(o=>Promise.resolve(o)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async requestTelegramChannelId(t,a={dataObject:null}){let r=`${g}/telegram-channel-id`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadDescription(t){let a=`${g}/description`;return s.makeAxiosFactory(a).then(o=>Promise.resolve(o.data)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async loadMenuByBotId(t,a={botId:null}){let r=`${g}/image-menu/${a.botId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e.data.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadLocationsByCompany(t,a={companyId:null}){let r=`${g}/location/${a.companyId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e.data.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeKeyboardTemplate(t,a={templateId:null}){let r=`${g}/remove-keyboard-template/${a.templateId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async editKeyboardTemplate(t,a={keyboardForm:null}){let r=`${g}/edit-keyboard-template`;return s.makeAxiosFactory(r,"POST",a.keyboardForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createKeyboardTemplate(t,a={keyboardForm:null}){let r=`${g}/keyboard-template`;return s.makeAxiosFactory(r,"POST",a.keyboardForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createLocation(t,a={locationForm:null}){let r=`${g}/location`;return s.makeAxiosFactory(r,"POST",a.locationForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createImageMenu(t,a={menuForm:null}){let r=`${g}/image-menu`;return s.makeAxiosFactory(r,"POST",a.menuForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createBotLazy(t,a={botForm:null}){let r=`${g}/bot-lazy`;return s.makeAxiosFactory(r,"POST",a.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createBot(t,a={botForm:null}){let r=`${g}/bot`;return s.makeAxiosFactory(r,"POST",a.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotKeyboards(t,a={botId:null}){let r=`${g}/keyboards/${a.botId}`;return s.makeAxiosFactory(r).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotPages(t,a={botId:null}){let r=`${g}/pages/${a.botId}`;return s.makeAxiosFactory(r).then(e=>{const n=e.data;return Promise.resolve(n)}).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},z={setTemplates(t,a){t.templates=a||[],localStorage.setItem("cashman_templates",JSON.stringify(a))},setTemplatesPaginateObject(t,a){t.templates_paginate_object=a||[],localStorage.setItem("cashman_templates_paginate_object",JSON.stringify(a))}},G={state:B,mutations:z,actions:L,getters:N},j="/admin/companies";let M={companies:[],companies_paginate_object:null};const J={getCompanies:t=>t.companies||[],getCompanyById:t=>a=>t.companies.find(r=>r.id===a),getCompaniesPaginateObject:t=>t.companies_paginate_object||null},w={async loadCompanies(t,a={dataObject:null,page:0,size:50}){let r=a.page||0,o=a.size||50,e=`${j}?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setCompanies",l.data),delete l.data,t.commit("setCompaniesPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async updateCompany(t,a={companyForm:null}){let r=`${j}/company-update`;return s.makeAxiosFactory(r,"POST",a.companyForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createCompany(t,a={companyForm:null}){let r=`${j}/company`;return s.makeAxiosFactory(r,"POST",a.companyForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeCompany(t,a={companyId:null}){let r=`${j}/${a.companyId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreCompany(t,a={companyId:null}){let r=`${j}/restore/${a.companyId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},R={setCompanies(t,a){t.companies=a||[],localStorage.setItem("cashman_companies",JSON.stringify(a))},setCompaniesPaginateObject(t,a){t.companies_paginate_object=a||[],localStorage.setItem("cashman_companies_paginate_object",JSON.stringify(a))}},V={state:M,mutations:R,actions:w,getters:J},u="/admin/bots";let K={bots:[],bot_users:[],bots_paginate_object:null,bot_users_paginate_object:null};const U={getBots:t=>t.bots||[],getBotUsers:t=>t.bot_users||[],getBotById:t=>a=>t.bots.find(r=>r.id===a),getBotsPaginateObject:t=>t.bots_paginate_object||null,getBotUsersPaginateObject:t=>t.bot_users_paginate_object||null},Y={async saveYClients(t,a={yClientsForm:null}){let r=`${u}/save-y-clients`;return s.makeAxiosFactory(r,"POST",a.yClientsForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async getMe(t,a={bot_token:null}){let r=`${u}/get-me`,o="POST";return s.makeAxiosFactory(r,o,{bot_token:a.bot_token}).then(n=>Promise.resolve(n.data)).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async loadCurrentBotFields(t,a={bot_id:null}){let r=`${u}/load-fields/${a.bot_id}`,o="GET";return s.makeAxiosFactory(r,o).then(n=>Promise.resolve(n.data)).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async storeBotFields(t,a={dataObject:null}){let r=`${u}/store-fields`,o="POST",e=a.dataObject;return s.makeAxiosFactory(r,o,e).then(c=>Promise.resolve(c.data)).catch(c=>(t.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))},async loadChatInfo(t,a={dataObject:{chat_id:null,bot_id:null}}){let r=`${u}/load-chat-info`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createBotTopics(t,a={dataObject:{bot_id:null,topics:null}}){let r=`${u}/create-bot-topics`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async updateShopLink(t,a={botForm:null}){let r=`${u}/update-shop-link`;return s.makeAxiosFactory(r,"POST",a.botForm).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBots(t,a={dataObject:null,page:0,size:50}){let r=a.page||0,o=a.size||50,e=`${u}?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setBots",l.data),delete l.data,t.commit("setBotsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async updateBot(t,a={botForm:null}){let r=`${u}/bot-update`;return s.makeAxiosFactory(r,"POST",a.botForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async updateBotWebhook(t,a={dataObject:{bot_id:null}}){let r=`${u}/bot-webhook-update`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async testConnectionAmoCRM(t,a={dataObject:null}){let r=`${u}/test-amo`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async saveAmoCRM(t,a={amoForm:null}){let r=`${u}/save-amo`;return s.makeAxiosFactory(r,"POST",a.amoForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateBot(t,a={dataObject:{bot_id:null,company_id:null}}){let r=`${u}/duplicate`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeBot(t,a={botId:null}){let r=`${u}/${a.botId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async forceDeleteBot(t,a={botId:null}){let r=`${u}/force/${a.botId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreBot(t,a={botId:null}){let r=`${u}/restore/${a.botId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async changeUserStatus(t,a={dataObject:{botUserId:null,status:0}}){let r=`${u}/user-status`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadBotUsers(t,a={dataObject:{botId:null,search:null},page:0,size:100}){let r=a.page||0,o=a.size||12,e=`${u}/users?page=${r}&size=${o}`;return s.makeAxiosFactory(e,"POST",a.dataObject).then(c=>{let m=c.data;return t.commit("setBotUsers",m.data),delete m.data,t.commit("setBotUsersPaginateObject",m),Promise.resolve()}).catch(c=>(t.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))}},q={setBots(t,a){t.bots=a||[],localStorage.setItem("cashman_bots",JSON.stringify(a))},setBotUsers(t,a){t.bot_users=a||[],localStorage.setItem("cashman_bot_users",JSON.stringify(a))},setBotUsersPaginateObject(t,a){t.bot_users_paginate_object=a||[],localStorage.setItem("cashman_bot_users_paginate_object",JSON.stringify(a))},setBotsPaginateObject(t,a){t.bots_paginate_object=a||[],localStorage.setItem("cashman_bots_paginate_object",JSON.stringify(a))}},W={state:K,mutations:q,actions:Y,getters:U},P="/admin/pages";let X={pages:[],pages_paginate_object:null};const H={getPages:t=>t.pages||[],getPageById:t=>a=>t.pages.find(r=>r.id===a),getPagesPaginateObject:t=>t.pages_paginate_object||null},Z={async loadPages(t,a={dataObject:{botId:null,search:null},page:0,size:12}){let r=a.page||0,e=`${P}?page=${r}&size=12`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setPages",l.data),delete l.data,t.commit("setPagesPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async updatePage(t,a={pageForm:null}){let r=`${P}/page-update`;return s.makeAxiosFactory(r,"POST",a.pageForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicatePage(t,a={dataObject:{pageId:null}}){let r=`${P}/duplicate/${a.dataObject.pageId}`;return s.makeAxiosFactory(r,"POST").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removePage(t,a={dataObject:{pageId:null}}){let r=`${P}/${a.dataObject.pageId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async forceRemovePage(t,a={dataObject:{pageId:null}}){let r=`${P}/force/${a.dataObject.pageId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restorePage(t,a={dataObject:{pageId:null}}){let r=`${P}/restore/${a.dataObject.pageId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createPage(t,a={pageForm:null}){let r=`${P}/page`;return s.makeAxiosFactory(r,"POST",a.pageForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},Q={setPages(t,a){t.pages=a||[],localStorage.setItem("cashman_pages",JSON.stringify(a))},setPagesPaginateObject(t,a){t.pages_paginate_object=a||[],localStorage.setItem("cashman_pages_paginate_object",JSON.stringify(a))}},ee={state:X,mutations:Q,actions:Z,getters:H},_="/admin/dialog-groups";let te={dialog_groups:[],dialog_commands:[],dialog_groups_paginate_object:null,dialog_commands_paginate_object:null};const ae={getDialogGroups:t=>t.dialog_groups||[],getDialogCommands:t=>t.dialog_commands||[],getDialogGroupById:t=>a=>t.dialog_groups.find(r=>r.id===a),getDialogGroupsPaginateObject:t=>t.dialog_groups_paginate_object||null,getDialogCommandsPaginateObject:t=>t.dialog_commands_paginate_object||null},re={async loadDialogGroups(t,a={dataObject:{botId:null,search:null},page:0,size:12}){let r=a.page||0,e=`${_}?page=${r}&size=12`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setDialogGroups",l.data),delete l.data,t.commit("setDialogGroupsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async loadDialogCommands(t,a={dataObject:{botId:null,search:null},page:0,size:30}){let r=a.page||0,o=a.size||30,e=`${_}/commands?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setDialogCommands",l.data),delete l.data,t.commit("setDialogCommandsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async updateDialogGroup(t,a={dialogGroupForm:null}){let r=`${_}/update-group`;return s.makeAxiosFactory(r,"POST",a.dialogGroupForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async updateDialogCommand(t,a={dialogCommandForm:null}){let r=`${_}/update-dialog`;return s.makeAxiosFactory(r,"POST",a.dialogCommandForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeDialogGroup(t,a={dataObject:{dialogGroupId:null}}){let r=`${_}/remove-group/${a.dataObject.dialogGroupId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeDialogCommand(t,a={dataObject:{dialogCommandId:null}}){let r=`${_}/remove-dialog/${a.dataObject.dialogCommandId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createDialogGroup(t,a={dialogGroupForm:null}){let r=`${_}/add-group`;return s.makeAxiosFactory(r,"POST",a.dialogGroupForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async swapDialogCommand(t,a={swapForm:null}){let r=`${_}/swap-dialog`;return s.makeAxiosFactory(r,"POST",a.swapForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async unlinkDialogCommand(t,a={dataObject:null}){let r=`${_}/unlink-dialog`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async swapDialogGroup(t,a={swapForm:null}){let r=`${_}/swap-group`;return s.makeAxiosFactory(r,"POST",a.swapForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async stopDialogs(t){let a=`${_}/stop-dialogs`;return s.makeAxiosFactory(a,"POST").then(o=>Promise.resolve(o.data)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async createDialogCommand(t,a={dialogCommandForm:null}){let r=`${_}/add-dialog`;return s.makeAxiosFactory(r,"POST",a.dialogCommandForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateDialogCommand(t,a={dataObject:null}){let r=`${_}/duplicate-dialog`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async attachDialogCommandToSlug(t,a={dataObject:null}){let r=`${_}/attach-dialog-to-slug`;return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},oe={setDialogGroups(t,a){t.dialog_groups=a||[],localStorage.setItem("cashman_dialog_groups",JSON.stringify(a))},setDialogGroupsPaginateObject(t,a){t.dialog_groups_paginate_object=a||[],localStorage.setItem("cashman_dialog_groups_paginate_object",JSON.stringify(a))},setDialogCommands(t,a){t.dialog_commands=a||[],localStorage.setItem("cashman_dialog_commands",JSON.stringify(a))},setDialogCommandsPaginateObject(t,a){t.dialog_commands_paginate_object=a||[],localStorage.setItem("cashman_dialog_commands_paginate_object",JSON.stringify(a))}},se={state:te,mutations:oe,actions:re,getters:ae},p="/admin/slugs";let ne={slugs:[],global_slugs:[],slugs_paginate_object:null,global_slugs_paginate_object:null};const ie={getSlugs:t=>t.slugs||[],getGlobalSlugs:t=>t.global_slugs||[],getSlugById:t=>a=>t.slugs.find(r=>r.id===a),getSlugsPaginateObject:t=>t.slugs_paginate_object||null,getGlobalSlugsPaginateObject:t=>t.global_slugs_paginate_object||null},ce={async reloadGlobalScripts(t){let a=`${p}/reload-global-scripts`;return s.makeAxiosFactory(a,"POST").then(o=>Promise.resolve(o)).catch(o=>(t.commit("setErrors",o.response.data.errors||[]),Promise.reject(o)))},async loadAllSlugs(t,a={botId:null}){let r=`${p}/all-slugs/${a.botId}`,o="POST";return s.makeAxiosFactory(r,o).then(n=>{let c=n.data;return Promise.resolve(c)}).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async loadGlobalSlugs(t,a={dataObject:{search:null},page:0,size:12}){let r=a.page||0,e=`${p}/global-list?page=${r}&size=12`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setGlobalSlugs",l.data),delete l.data,t.commit("setGlobalSlugsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async loadSlugs(t,a={dataObject:{botId:null,search:null,needGlobal:!1,needDeleted:!1},page:0,size:12}){let r=a.page||0,o=a.size||12,e=`${p}?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setSlugs",l.data),delete l.data,t.commit("setSlugsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async updateSlug(t,a={slugForm:null}){let r=`${p}/slug-update`;return s.makeAxiosFactory(r,"POST",a.slugForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async relocateSlugActionData(t,a={slug_sender_id:null,slug_recipient_id:null,bot_id:null}){let r=`${p}/relocate-actions-data`;return s.makeAxiosFactory(r,"POST",a).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateSlug(t,a={dataObject:{slugId:null}}){let r=`${p}/duplicate/${a.dataObject.slugId}`;return s.makeAxiosFactory(r,"POST").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreSlug(t,a={dataObject:{slugId:null}}){let r=`${p}/restore/${a.dataObject.slugId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeSlug(t,a={dataObject:{slugId:null}}){let r=`${p}/${a.dataObject.slugId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async refreshSlugParams(t,a={dataObject:{slugId:null}}){let r=`${p}/reload-params/${a.dataObject.slugId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async createSlug(t,a={slugForm:null}){let r=`${p}/slug`;return s.makeAxiosFactory(r,"POST",a.slugForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},le={setSlugs(t,a){t.slugs=a||[],localStorage.setItem("cashman_slugs",JSON.stringify(a))},setSlugsPaginateObject(t,a){t.slugs_paginate_object=a||[],localStorage.setItem("cashman_slugs_paginate_object",JSON.stringify(a))},setGlobalSlugs(t,a){t.global_slugs=a||[],localStorage.setItem("cashman_global_slugs",JSON.stringify(a))},setGlobalSlugsPaginateObject(t,a){t.global_slugs_paginate_object=a||[],localStorage.setItem("cashman_global_slugs_paginate_object",JSON.stringify(a))}},me={state:ne,mutations:le,actions:ce,getters:ie},b="/admin/shop/products";let ue={products:[],product_categories:[],products_paginate_object:null};const de={getProducts:t=>t.products||[],getProductCategories:t=>t.product_categories||[],getProductById:t=>a=>t.products.find(r=>r.id===a),getProductsPaginateObject:t=>t.products_paginate_object||null},ge={async loadProduct(t,a={dataObject:{productId:null}}){let r=`${b}/${a.dataObject.productId}`,o="GET";return s.makeAxiosFactory(r,o).then(n=>Promise.resolve(n.data)).catch(n=>(t.commit("setErrors",n.response.data.errors||[]),Promise.reject(n)))},async loadProducts(t,a={dataObject:{bot_id:null},page:0,size:12}){let r=a.page||0,e=`${b}?page=${r}&size=12`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setProducts",l.data),delete l.data,t.commit("setProductsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async loadProductCategories(t,a={dataObject:{bot_id:null}}){a.page;let r=`${b}/categories`,o="POST",e=a.dataObject;return s.makeAxiosFactory(r,o,e).then(c=>{let m=c.data;return t.commit("setProductCategories",m.data),Promise.resolve()}).catch(c=>(t.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))},async loadRandomProducts(t,a={dataObject:{bot_id:null}}){let r=`${b}`,o="POST",e=a.dataObject;return s.makeAxiosFactory(r,o,e).then(c=>{let m=c.data;return t.commit("setProducts",m.data),delete m.data,t.commit("setProductsPaginateObject",m),Promise.resolve()}).catch(c=>(t.commit("setErrors",c.response.data.errors||[]),Promise.reject(c)))},async saveProduct(t,a={productForm:null}){let r=`${b}/save`;return s.makeAxiosFactory(r,"POST",a.productForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeProduct(t,a){let r=`${b}/remove/${a}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateProduct(t,a){let r=`${b}/duplicate/${a}`;return s.makeAxiosFactory(r,"POST").then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},_e={setProducts(t,a){t.products=a||[],localStorage.setItem("cashman_products",JSON.stringify(a))},setProductCategories(t,a){t.product_categories=a||[],localStorage.setItem("cashman_product_categories",JSON.stringify(a))},setProductsPaginateObject(t,a){t.products_paginate_object=a||[],localStorage.setItem("cashman_products_paginate_object",JSON.stringify(a))}},pe={state:ue,mutations:_e,actions:ge,getters:de},O="/admin/media";let Pe={media:[],media_paginate_object:null};const be={getMedia:t=>t.media||[],getMediaPaginateObject:t=>t.media_paginate_object||null},he={async showMediaPreview(t,a={dataObject:{mediaId:null}}){let r=`${O}/preview/${a.dataObject.mediaId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async loadMedia(t,a={dataObject:{bot_id:null,search:null,needPhoto:!1,needVideo:!1,needVideoNote:!1,needAudio:!1,needDocument:!1},page:0,size:12}){let r=a.page||0,o=a.size||12,e=`${O}?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setMedia",l.data),delete l.data,t.commit("setMediaPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async removeMedia(t,a={dataObject:{mediaId:null}}){let r=`${O}/remove/${a.dataObject.mediaId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},je={setMedia(t,a){t.media=a||[],localStorage.setItem("cashman_media",JSON.stringify(a))},setMediaPaginateObject(t,a){t.media_paginate_object=a||[],localStorage.setItem("cashman_media_paginate_object",JSON.stringify(a))}},Oe={state:Pe,mutations:je,actions:he,getters:be},h="/admin/appointments";let xe={appointments:[],appointment_events:[],appointment_schedules:[],appointments_paginate_object:null,appointments_events_paginate_object:null,appointments_schedules_paginate_object:null};const ye={getAppointments:t=>t.appointments||[],getAppointmentEvents:t=>t.appointment_events||[],getAppointmentSchedules:t=>t.appointment_schedules||[],getAppointmentsPaginateObject:t=>t.appointments_paginate_object||null,getAppointmentEventsPaginateObject:t=>t.appointments_events_paginate_object||null,getAppointmentSchedulesPaginateObject:t=>t.appointments_schedules_paginate_object||null},Ee={async loadAppointmentEvents(t,a={dataObject:{botId:null,search:null},page:0,size:12}){let r=a.page||0,o=a.size||12,e=`${h}/event-list?page=${r}&size=${o}`,n="POST",c=a.dataObject;return s.makeAxiosFactory(e,n,c).then(i=>{let l=i.data;return t.commit("setAppointmentEvents",l.data),delete l.data,t.commit("setAppointmentEventsPaginateObject",l),Promise.resolve()}).catch(i=>(t.commit("setErrors",i.response.data.errors||[]),Promise.reject(i)))},async addAppointmentEvent(t,a={appointmentEventForm:null}){let r=`${h}/add-event`;return s.makeAxiosFactory(r,"POST",a.appointmentEventForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async updateAppointmentEvent(t,a={appointmentEventForm:null}){let r=`${h}/update-event`;return s.makeAxiosFactory(r,"POST",a.appointmentEventForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async duplicateAppointmentEvent(t,a={dataObject:{appointmentEventId:null}}){let r=`${h}/duplicate-event/${a.dataObject.appointmentEventId}`;return s.makeAxiosFactory(r,"POST").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeAppointmentEvent(t,a={dataObject:{appointmentEventId:null}}){let r=`${h}/remove-event/${a.dataObject.appointmentEventId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async forceRemoveAppointment(t,a={dataObject:{appointmentEventId:null}}){let r=`${h}/force-remove-event/${a.dataObject.appointmentEventId}`;return s.makeAxiosFactory(r,"DELETE").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async restoreAppointmentEvent(t,a={dataObject:{appointmentEventId:null}}){let r=`${h}/restore-event/${a.dataObject.appointmentEventId}`;return s.makeAxiosFactory(r,"GET").then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},ve={setAppointments(t,a){t.appointments=a||[],localStorage.setItem("cashman_appointments",JSON.stringify(a))},setAppointmentEvents(t,a){t.appointment_events=a||[],localStorage.setItem("cashman_appointment_events",JSON.stringify(a))},setAppointmentSchedules(t,a){t.appointment_schedules=a||[],localStorage.setItem("cashman_appointment_schedules",JSON.stringify(a))},setAppointmentsPaginateObject(t,a){t.appointments_paginate_object=a||[],localStorage.setItem("cashman_appointments_paginate_object",JSON.stringify(a))},setAppointmentEventsPaginateObject(t,a){t.appointment_events_paginate_object=a||[],localStorage.setItem("cashman_appointment_events_paginate_object",JSON.stringify(a))},setAppointmentSchedulesPaginateObject(t,a){t.appointment_schedules_paginate_object=a||[],localStorage.setItem("cashman_appointment_schedules_paginate_object",JSON.stringify(a))}},ke={state:xe,mutations:ve,actions:Ee,getters:ye},Se=v({state:{current_company:null,current_bot:null,errors:[]},getters:{getErrors:t=>t.errors||[],getCurrentCompany:t=>{let a=localStorage.getItem("store_current_company")?JSON.parse(localStorage.getItem("store_current_company")):null;return t.current_company||a||null},getCurrentBot:t=>{let a=localStorage.getItem("store_current_bot")?JSON.parse(localStorage.getItem("store_current_bot")):null;return t.current_bot||a||null}},actions:{async sendToChannel(t,a={mailForm:null}){let r="/send-to-channel";return s.makeAxiosFactory(r,"POST",a.mailForm).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},async removeFile(t,a={file_path:null}){let r="/remove-file";return s.makeAxiosFactory(r,"POST",{...a}).then(e=>Promise.resolve(e.data)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))},updateCurrentCompany(t,a={company:null}){let r=localStorage.getItem("store_current_company")?JSON.parse(localStorage.getItem("store_current_company")):null;r=a.company||r||null,t.commit("setCurrentCompany",r)},resetCurrentCompany(t){t.commit("setCurrentCompany",null)},resetCurrentBot(t){t.commit("setCurrentBot",null)},updateCurrentBot(t,a={bot:null}){let r=localStorage.getItem("store_current_bot")?JSON.parse(localStorage.getItem("store_current_bot")):null;r=a.bot||r||null,t.commit("setCurrentBot",r)},async updateProductsFromVk(t,a={dataObject:{bot_domain:null}}){let r="/admin/vk-auth-link";return s.makeAxiosFactory(r,"POST",a.dataObject).then(e=>Promise.resolve(e)).catch(e=>(t.commit("setErrors",e.response.data.errors||[]),Promise.reject(e)))}},mutations:{setCurrentCompany(t,a){t.current_company=a||null,localStorage.setItem("store_current_company",JSON.stringify(a)),window.dispatchEvent(new CustomEvent("store_current_company-change-event"))},setCurrentBot(t,a){t.current_bot=a||null,localStorage.setItem("store_current_bot",JSON.stringify(a)),window.dispatchEvent(new CustomEvent("store_current_bot-change-event"))},setErrors(t,a){t.errors=a||[]}},modules:{templates:G,appointments:ke,companies:V,bots:W,pages:ee,dialogGroups:se,slugs:me,products:pe,media:Oe}}),$e=S();window.eventBus=$e;var y;const Ae=((y=window.document.getElementsByTagName("title")[0])==null?void 0:y.innerText)||"Laravel";k({title:t=>`${t} - ${Ae}`,resolve:t=>$(`./Pages/${t}.vue`,Object.assign({"./Pages/Auth/ConfirmPassword.vue":()=>d(()=>import("./ConfirmPassword-5f750aa6.js"),["assets/ConfirmPassword-5f750aa6.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-feebe8e6.js"]),"./Pages/Auth/ForgotPassword.vue":()=>d(()=>import("./ForgotPassword-ab7e1b56.js"),["assets/ForgotPassword-ab7e1b56.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-feebe8e6.js"]),"./Pages/Auth/Login.vue":()=>d(()=>import("./Login-8c63acfc.js"),["assets/Login-8c63acfc.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-feebe8e6.js"]),"./Pages/Auth/Register.vue":()=>d(()=>import("./Register-6cd881e8.js"),["assets/Register-6cd881e8.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-feebe8e6.js"]),"./Pages/Auth/ResetPassword.vue":()=>d(()=>import("./ResetPassword-7b894a7d.js"),["assets/ResetPassword-7b894a7d.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TextInput-feebe8e6.js"]),"./Pages/Auth/VerifyEmail.vue":()=>d(()=>import("./VerifyEmail-0ef90c5d.js"),["assets/VerifyEmail-0ef90c5d.js","assets/index.es-77235f88.js","assets/PrimaryButton-a56d9afc.js","assets/_plugin-vue_export-helper-c27b6911.js"]),"./Pages/BotPage.vue":()=>d(()=>import("./BotPage-e005e1e0.js"),["assets/BotPage-e005e1e0.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/BotSection-a41dd1ec.js","assets/Mail-e1f52f7d.js","assets/TelegramChannelHelper-587b5dac.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/v4-a960c1f4.js","assets/Mail-166a059d.css","assets/BotList-fbce1647.js","assets/GlobalSlugList-c8d86e2b.js","assets/Pagination-df5e6be2.js","assets/Pagination-4757d200.css","assets/BotList-f5649b59.css","assets/CompanyList-fc255b8d.js"]),"./Pages/BotVisitCardConstructorPage.vue":()=>d(()=>import("./BotVisitCardConstructorPage-3b10f210.js"),["assets/BotVisitCardConstructorPage-3b10f210.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/_plugin-vue_export-helper-c27b6911.js","assets/BotVisitCardConstructorPage-8a909fea.css"]),"./Pages/ChatWindow.vue":()=>d(()=>import("./ChatWindow-fe478d55.js"),["assets/ChatWindow-fe478d55.js","assets/index.es-77235f88.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/v4-a960c1f4.js","assets/ChatWindow-dadc2ae3.css"]),"./Pages/CompanyPage.vue":()=>d(()=>import("./CompanyPage-cac58a72.js"),["assets/CompanyPage-cac58a72.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/CompanyForm-fc03a719.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/CompanyForm-28f51984.css","assets/CompanyList-fc255b8d.js","assets/Pagination-df5e6be2.js","assets/Pagination-4757d200.css"]),"./Pages/Dashboard.vue":()=>d(()=>import("./Dashboard-189c5c80.js"),["assets/Dashboard-189c5c80.js","assets/CompanyList-fc255b8d.js","assets/index.es-77235f88.js","assets/Pagination-df5e6be2.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/BotList-fbce1647.js","assets/GlobalSlugList-c8d86e2b.js","assets/TelegramChannelHelper-587b5dac.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/Mail-e1f52f7d.js","assets/v4-a960c1f4.js","assets/Mail-166a059d.css","assets/BotList-f5649b59.css","assets/BotSection-a41dd1ec.js","assets/CompanyForm-fc03a719.js","assets/CompanyForm-28f51984.css","assets/Dashboard-db3e581c.css"]),"./Pages/MailPage.vue":()=>d(()=>import("./MailPage-19ec2e5a.js"),["assets/MailPage-19ec2e5a.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/CompanyList-fc255b8d.js","assets/Pagination-df5e6be2.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/Mail-e1f52f7d.js","assets/TelegramChannelHelper-587b5dac.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/v4-a960c1f4.js","assets/Mail-166a059d.css","assets/CompanyForm-28f51984.css"]),"./Pages/MainPage.vue":()=>d(()=>import("./MainPage-2ce7f12c.js"),["assets/MainPage-2ce7f12c.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/CompanyList-fc255b8d.js","assets/Pagination-df5e6be2.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/CompanyForm-28f51984.css"]),"./Pages/ManagerMainPage.vue":()=>d(()=>import("./ManagerMainPage-89d27b27.js"),["assets/ManagerMainPage-89d27b27.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/Mail-e1f52f7d.js","assets/TelegramChannelHelper-587b5dac.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/v4-a960c1f4.js","assets/Mail-166a059d.css","assets/BotList-fbce1647.js","assets/GlobalSlugList-c8d86e2b.js","assets/Pagination-df5e6be2.js","assets/Pagination-4757d200.css","assets/BotList-f5649b59.css","assets/CompanyList-fc255b8d.js"]),"./Pages/MediaPage.vue":()=>d(()=>import("./MediaPage-c770c0d9.js"),["assets/MediaPage-c770c0d9.js","assets/index.es-77235f88.js","assets/MainAdminLayout-635ec8b4.js","assets/MainAdminLayout-70ad6139.css"]),"./Pages/ScriptPage.vue":()=>d(()=>import("./ScriptPage-0fa85917.js"),["assets/ScriptPage-0fa85917.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/GlobalSlugList-c8d86e2b.js","assets/TelegramChannelHelper-587b5dac.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/TelegramChannelHelper-5ad24a6a.css","assets/Pagination-df5e6be2.js","assets/Pagination-4757d200.css"]),"./Pages/UserPage.vue":()=>d(()=>import("./UserPage-47d38905.js"),["assets/UserPage-47d38905.js","assets/MainAdminLayout-635ec8b4.js","assets/index.es-77235f88.js","assets/MainAdminLayout-70ad6139.css","assets/CompanyList-fc255b8d.js","assets/Pagination-df5e6be2.js","assets/_plugin-vue_export-helper-c27b6911.js","assets/Pagination-4757d200.css","assets/CompanyForm-28f51984.css"])})),setup({el:t,App:a,props:r,plugin:o}){const e=A({render:()=>f(a,r)});return e.config.globalProperties.$filters={timeAgo(n){return x(n).fromNow()},current(n){return x(n).format("YYYY-MM-DD")}},e.use(o).use(Se).use(F).use(I).use(T).use(C,Ziggy).use(D,{loading:"/images/cashman.jpg",error:"/images/error.png"}).mount(t),e},progress:{color:"#4B5563"}});
