<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3>Настройка AMO</h3>

            <form
                v-on:submit.prevent="submitAmo">

                <p class="mb-2"><a target="_blank" href="https://www.amocrm.ru/developers/content/oauth/step-by-step">Документация
                    для
                    разработчика </a> по шагам</p>

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="client_id">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>ID интеграции</div>
                                </template>
                            </Popper>
                            client_id
                        </div>

                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="client_id"
                           aria-label="client_id"
                           v-model="amoForm.client_id"
                           aria-describedby="client_id">
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="client_secret">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Секрет интеграции</div>
                                </template>
                            </Popper>
                            client_secret
                        </div>
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="client_secret"
                           aria-label="client_secret"
                           v-model="amoForm.client_secret"
                           aria-describedby="client_secret">
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="auth_code">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Полученный код авторизации</div>
                                </template>
                            </Popper>
                            auth_code
                        </div>
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </label>
                    <textarea class="form-control"
                              placeholder="auth_code"
                              aria-label="auth_code"
                              v-model="amoForm.auth_code"
                              aria-describedby="auth_code">
                                    </textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="subdomain2">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Ваш поддомен в системе AMO CRM</div>
                                </template>
                            </Popper>
                            subdomain
                        </div>
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="subdomain"
                           aria-label="subdomain"
                           v-model="amoForm.subdomain"
                           aria-describedby="subdomain">
                </div>


                <button type="submit"
                        class="btn btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                    <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку AMO
                </button>

            </form>

        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["data", "bot"],
    data() {
        return {
            hasConnect: false,
            amoForm: {
                client_id: null,
                client_secret: null,
                auth_code: null,
                redirect_uri: null,
                subdomain: null,
                bot_id: null,
            },
        }
    },

    mounted() {


        if (this.data)
            this.$nextTick(() => {
                this.amoForm.client_id = this.data.client_id || null
                this.amoForm.client_secret = this.data.client_secret || null
                this.amoForm.auth_code = this.data.auth_code || null
                this.amoForm.redirect_uri = this.data.redirect_uri || null
                this.amoForm.subdomain = this.data.subdomain || null
                this.amoForm.bot_id = this.data.bot_id || this.bot.id || null
            })


    },
    methods: {

        submitAmo() {
            /*    if (!this.hasConnect) {
                    return;
                }*/
            let data = new FormData();
            Object.keys(this.amoForm)
                .forEach(key => {
                    const item = this.amoForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("saveAmoCRM", {
                amoForm: data
            }).then((response) => {
                this.$botNotification.success("Работа с AMO", "Данные CRM успешно сохранены");
            }).catch(err => {

            })


        }
    }
}
</script>
