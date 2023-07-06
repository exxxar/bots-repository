<template>
    <form
        v-on:submit.prevent="submitAmo"
        class="row">
        <div class="col-12">
            <p><a target="_blank" href="https://www.amocrm.ru/developers/content/oauth/step-by-step">Документация для разработчика </a> по шагам</p>
        </div>
        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="client_id">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>ID интеграции</div>
                    </template>
                </Popper>
                client_id
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="client_id"
                   aria-label="client_id"
                   v-model="amoForm.client_id"
                   aria-describedby="client_id">
        </div>
        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="client_secret">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Секрет интеграции</div>
                    </template>
                </Popper>
                client_secret
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="client_secret"
                   aria-label="client_secret"
                   v-model="amoForm.client_secret"
                   aria-describedby="client_secret">
        </div>
        <div class="col-md-12 mb-2">
            <label class="form-label" id="auth_code">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Полученный код авторизации</div>
                    </template>
                </Popper>
                auth_code
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <textarea class="form-control"
                      placeholder="auth_code"
                      aria-label="auth_code"
                      v-model="amoForm.auth_code"
                      aria-describedby="auth_code">
                                    </textarea>
        </div>
        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="redirect_uri">

                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Redirect URI указанный в настройках интеграции. Должен четко совпадать с тем, что указан в настройках</div>
                    </template>
                </Popper>
                redirect_uri
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="redirect_uri"
                   aria-label="redirect_uri"
                   v-model="amoForm.redirect_uri"
                   aria-describedby="redirect_uri">
        </div>
        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="subdomain2">

                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Ваш поддомен в системе AMO CRM</div>
                    </template>
                </Popper>
                subdomain
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="subdomain"
                   aria-label="subdomain"
                   v-model="amoForm.subdomain"
                   aria-describedby="subdomain">
        </div>
        <div class="col-md-12 col-12" v-if="!hasConnect">
            <button  class="btn btn-outline-info p-3 w-100" ><i class="fa-solid fa-plug mr-1"></i> Проверить подключение</button>
        </div>
        <div class="col-md-12 col-12" v-if="hasConnect">
            <button class="btn btn-outline-primary p-3 w-100" ><i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку AMO</button>
        </div>
    </form>
</template>
<script>
export default {
    props:["data","bot"],
    data(){
        return {
            hasConnect:false,
            amoForm:{
                client_id:null,
                client_secret:null,
                auth_code:null,
                redirect_uri:null,
                subdomain:null,
                bot_id:null,
            },
        }
    },
    mounted() {
        if (this.data)
            this.$nextTick(()=>{
                this.amoForm.client_id = this.data.client_id || null
                this.amoForm.client_secret = this.data.client_secret || null
                this.amoForm.auth_code = this.data.auth_code || null
                this.amoForm.redirect_uri = this.data.redirect_uri || null
                this.amoForm.subdomain = this.data.subdomain || null
                this.amoForm.bot_id = this.data.bot_id || this.bot.id || null
            })
    },
    methods:{
        submitAmo(){
            if (!this.hasConnect){

                return;
            }
        }
    }
}
</script>
