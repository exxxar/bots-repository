<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
import ProjectInfoCard from "@/ClientTg/Components/V1/Shop/Helpers/ProjectInfoCard.vue";
</script>
<template>
    <div v-if="botUser"><!---->
        <div class="card card-style p-3" v-if="!botUser.is_vip">
            <form
                v-on:submit.prevent="submit" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3" v-if="settings.need_profile_form_image">
                    <div class="img-avatar">
                        <img
                            v-if="settings.form_image"
                            v-lazy="settings.form_image"
                            class="img-avatar"/>

                        <img
                            v-else
                            v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                    </div>

                </div>
                <div class="col-12">
                    <h6 class="mb-3 text-center" v-if="settings.pre_name_text" v-html="settings.pre_name_text"></h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Петров Петр Семенович"
                               aria-label="vipForm-name"
                               v-model="vipForm.name"
                               aria-describedby="vipForm-name" required>
                    </div>
                </div>

                <div class="col-12" v-if="settings.need_phone">
                    <h6 class="mb-3 text-center" v-if="settings.pre_phone_text" v-html="settings.pre_phone_text"></h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="['+7(###)###-##-##']"
                               v-model="vipForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>

                    </div>
                </div>

                <div class="col-12" v-if="settings.need_email">
                    <h6 class="mb-3 text-center" v-if="settings.pre_email_text" v-html="settings.pre_email_text"></h6>
                    <div class="input-style input-style-2">
                        <input type="email" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="vipForm.email"
                               placeholder="example@test.com"
                               aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>

                    </div>
                </div>

                <div class="col-12" v-if="settings.need_sex">
                    <h6 class="mb-3 text-center" v-if="settings.pre_sex_text" v-html="settings.pre_sex_text"></h6>
                    <div class="row mb-0">
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight':vipForm.sex}"
                                @click="vipForm.sex = true"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Мужчина</span>
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight ':!vipForm.sex}"
                                @click="vipForm.sex = false"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s  shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Женщина</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" v-if="settings.need_birthday">
                    <h6 class="mb-3 text-center" v-if="settings.pre_birthday_text" v-html="settings.pre_birthday_text"></h6>
                    <div class="input-style input-style-2">
                        <input type="date" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="vipForm.birthday"
                               aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                    </div>
                </div>

                <div class="col-12" v-if="settings.need_city">
                    <h6 class="mb-3 text-center" v-if="settings.pre_city_text" v-html="settings.pre_city_text"></h6>
                    <div class="input-style input-style-2">
                        <input type="text"
                               v-model="vipForm.city"
                               class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Ваш город"
                               aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                    </div>
                </div>
                <!-- -->
                <div class="col-12"

                     v-for="(field, index) in vipForm.fields"
                >
                    <div v-if="settings[field.key]">
                        <h6 class="text-center">{{ field.description }}</h6>
                        <div class="input-style input-style-2" v-if="field.type===0||field.type===1">
                            <input :type="field.type===1?'number':'text'"
                                   v-model="vipForm.fields[index].value"
                                   class="form-control text-center font-14 p-3 rounded-s border-theme"
                                   :placeholder="field.title"
                                   :pattern="vipForm.fields[index].pattern||''"
                                   aria-label="vipForm-city" aria-describedby="vipForm-city"
                                   :required="vipForm.fields[index].requried"
                            >
                        </div>

                        <div class="row mb-0" v-if="field.type===2">
                            <div class="col-6 p-3">
                                <div
                                    v-bind:class="{'bg-highlight':vipForm.fields[index].value}"
                                    @click="vipForm.fields[index].value = true"
                                    class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-check font-28"></i>
                                    <span class="text-center text-uppercase my-2">Да</span>
                                </div>
                            </div>
                            <div class="col-6 p-3">
                                <div
                                    v-bind:class="{'bg-highlight ':!vipForm.fields[index].value}"
                                    @click="vipForm.fields[index].value = false"
                                    class="btn btn-border btn-m btn-full border-highlight rounded-s  shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-xmark font-28"></i>
                                    <span class="text-center text-uppercase my-2">Нет</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                        использования и дайте свое согласие на их принятие.</em></p>

                    <p>Перед отправкой данных нужно ознакомиться с <a
                        href="#">политикой конфиденциальности</a>.</p>

                    <div class="d-flex mb-3">
                        <div class="pt-1">
                            <h5 data-activate="toggle-id-1" class="font-500 font-13">
                                <span v-if="!vipForm.sex">С правилами ознакомилась</span>
                                <span v-if="vipForm.sex">С правилами ознакомлен</span>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch">
                                <input
                                    v-model="confirm"
                                    type="checkbox" class="ios-input" id="toggle-id-1">
                                <label class="custom-control-label" for="toggle-id-1"></label>
                            </div>
                        </div>
                    </div>


                    <button type="submit"
                            :disabled="!confirm||load"
                            class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Отправить анкету
                    </button>

                </div>
            </form>

        </div>

        <div class="card card-style p-3" v-if="botUser.is_vip">
            <p v-html="settings.text_after_submit"></p>

            <h6>Ваши данные:</h6>
            <p class="mb-0">Имя: {{ botUser.name || 'Не указано' }}</p>
            <p class="mb-0" v-if="settings.need_phone">Телефон: {{ botUser.phone || 'Не указано' }}</p>
            <p class="mb-0" v-if="settings.need_email">Email: {{ botUser.email || 'Не указано' }}</p>
            <p class="mb-0" v-if="settings.need_city">Город: {{ botUser.city || 'Не указано' }}</p>
            <p class="mb-0" v-if="settings.need_birthday">Дата рождения: {{ botUser.birthday || 'Не указано' }}</p>
            <p class="mb-0" v-if="settings.need_sex">Пол: {{ botUser.sex ? 'Мужской' : 'Женский' }}</p>
            <p class="mb-0" v-for="field in vipForm.fields">
                {{ field.title }}:

                <span v-if="field.config.type===2">
                            {{ field.value === false ? "Нет" : "Да" }}
                        </span>

                <span v-if="field.config.type===0||field.config.type===1">
                            {{ field.value }}
                        </span>
            </p>


            <ReturnToBot class="mb-2"/>
        </div>

    </div>


    <ProjectInfoCard/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            settings: {
                display_type: 0,
                need_birthday: true,
                need_age: true,
                need_city: true,
                need_sex: true,
                need_phone: true,
                need_email: true,
                need_profile_form_image: true,
                form_image: null,
                pre_birthday_text: null,
                pre_email_text: null,
                pre_age_text: null,
                pre_city_text: null,
                pre_sex_text: null,
                pre_name_text: null,
                pre_phone_text: null,
                text_after_submit: null,
            },
            load: false,
            confirm: false,
            step: 0,
            botUser: null,
            vipForm: {
                name: null,
                phone: null,
                email: null,
                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,
                fields: []

            }
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

            this.vipForm = {
                name: this.botUser.name || this.botUser.fio_from_telegram || null,
                phone: this.botUser.phone || null,
                email: this.botUser.email || null,
                birthday: this.botUser.birthday || null,
                city: this.botUser.city || null,
                country: this.botUser.country || null,
                address: this.botUser.address || null,
                sex: this.botUser.sex || true,
                fields: [],
            }

            this.loadCurrentBotFields();

        },
    },
    mounted() {

        /*   this.$store.dispatch("loadSelf").then(() => {
               this.botUser = this.getSelf

               this.vipForm = {
                   name: this.botUser.name || this.botUser.fio_from_telegram || null,
                   phone: this.botUser.phone || null,
                   email: this.botUser.email || null,
                   birthday: this.botUser.birthday || null,
                   city: this.botUser.city || null,
                   country: this.botUser.country || null,
                   address: this.botUser.address || null,
                   sex: this.botUser.sex || true,
               }
           })*/


        this.loadProfileFormData();
    },

    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },

        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        getUserFieldValue(id) {
            const result = {
                value: null
            }

            if (!this.botUser.fields)
                return result

            return this.botUser.fields.find(item => item.bot_custom_field_setting_id === id) || result
        },
        loadCurrentBotFields() {
            return this.$store.dispatch("loadCurrentBotFields")
                .then((response) => {

                    let fields = response.data || []

                    fields.forEach(item => {

                        if (item.is_active) {

                            let field = this.getUserFieldValue(item.id)
                            let value = field.value;
                            let config = field.config;

                            this.vipForm.fields.push({
                                id: item.id,
                                title: item.label,
                                description: item.description,
                                key: item.key,
                                value: item.type === 2 ? (value === "1") : value,
                                type: item.type,
                                pattern: item.pattern,
                                required: item.required,
                                config: config
                            })


                        }
                    })


                })
        },
        loadProfileFormData() {
            this.loading = true;
            this.$store.dispatch("loadProfileFormData").then((resp) => {
                this.loading = false

                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
                    })
                })

            }).catch(() => {
                this.loading = false
            })
        },

        submit() {
            this.loading = true;

            this.$store.dispatch("saveProfileFormData", {
                dataObject:this.vipForm
            }).then((resp) => {
                this.loading = false
                this.tg.close()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}
</style>
