<template>
    <div
        class="container py-3"
        v-if="botUser"><!---->

        <div class="row">
            <div class="col-12" v-if="!botUser.is_vip">
                <form
                    v-on:submit.prevent="submit" class="row mb-0">
                    <div class="col-12 d-flex justify-content-center mb-3" v-if="settings.need_profile_form_image">
                        <div class="img-avatar">
                            <img
                                v-if="(settings.form_image||'').length>0"
                                v-lazy="settings.form_image"
                                class="img-avatar"/>

                            <img
                                v-else
                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                          <h6 class="text-center my-3" v-if="settings.pre_name_text" v-html="settings.pre_name_text"></h6>
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   placeholder="Петров Петр Семенович"
                                   aria-label="vipForm-name"
                                   v-model="vipForm.name"
                                   aria-describedby="vipForm-name" required>
                            <label for="vipForm-name">Ф.И.О.</label>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_phone">
                          <h6 class="text-center my-3" v-if="settings.pre_phone_text"
                            v-html="settings.pre_phone_text"></h6>
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   v-mask="['+7(###)###-##-##']"
                                   v-model="vipForm.phone"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>
                            <label for="vipForm-phone">Номер телефона</label>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_email">
                          <h6 class="text-center my-3" v-if="settings.pre_email_text"
                            v-html="settings.pre_email_text"></h6>
                        <div class="form-floating">
                            <input type="email" class="form-control text-center"
                                   v-model="vipForm.email"
                                   placeholder="example@test.com"
                                   aria-label="vipForm-email" aria-describedby="vipForm-email" required>
                            <label for="vipForm-email">Почта</label>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_sex">
                          <h6 class="text-center my-3" v-if="settings.pre_sex_text" v-html="settings.pre_sex_text"></h6>
                        <div class="row mb-0">
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-primary':vipForm.sex}"
                                    @click="vipForm.sex = true"
                                    class="btn w-100 btn-outline-primary p-2 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Мужчина</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-primary':!vipForm.sex}"
                                    @click="vipForm.sex = false"
                                    class="btn w-100 btn-outline-primary p-2 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Женщина</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_birthday">
                          <h6 class="text-center my-3" v-if="settings.pre_birthday_text"
                            v-html="settings.pre_birthday_text"></h6>
                        <div class="form-floating">
                            <input type="date" class="form-control text-center"
                                   v-model="vipForm.birthday"
                                   aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                            <label for="vipForm-birthday">Дата рождения</label>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_city">
                          <h6 class="text-center my-3" v-if="settings.pre_city_text" v-html="settings.pre_city_text"></h6>
                        <div class="form-floating">
                            <input type="text"
                                   v-model="vipForm.city"
                                   class="form-control text-center"
                                   placeholder="Ваш город"
                                   aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                            <label for="vipForm-city">Город проживания</label>
                        </div>
                    </div>
                    <!-- -->
                    <div class="col-12"
                         v-if="(vipForm.fields||[]).length>0"
                         v-for="(field, index) in vipForm.fields"
                    >
                        <div v-if="settings[field.key]">
                             <h6 class="text-center my-3">{{ field.description }}</h6>
                            <div class="form-floating" v-if="field.type===0||field.type===1">
                                <input :type="field.type===1?'number':'text'"
                                       v-model="vipForm.fields[index].value"
                                       class="form-control text-center"
                                       :placeholder="field.title"
                                       :pattern="vipForm.fields[index].pattern||''"
                                       aria-label="vipForm-city" aria-describedby="vipForm-city"
                                       :required="vipForm.fields[index].required||false"
                                >
                                <label :for="'vipForm-field'+index">{{ field.title || '-' }}</label>
                            </div>

                            <div class="row mb-0" v-if="field.type===2">
                                <div class="col-6 p-3">
                                    <div
                                        v-bind:class="{'bg-primary':vipForm.fields[index].value}"
                                        @click="vipForm.fields[index].value = true"
                                        class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
                                        <i class="fa-solid fa-check font-28"></i>
                                        <span class="text-center text-uppercase my-2">Да</span>
                                    </div>
                                </div>
                                <div class="col-6 p-3">
                                    <div
                                        v-bind:class="{'bg-primary':!vipForm.fields[index].value}"
                                        @click="vipForm.fields[index].value = false"
                                        class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
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
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       v-model="confirm"
                                       type="checkbox" role="switch" id="toggle-id-1">
                                <label class="form-check-label" for="toggle-id-1">
                                    <span v-if="!vipForm.sex">С правилами ознакомилась</span>
                                    <span v-if="vipForm.sex">С правилами ознакомлен</span>
                                </label>
                            </div>
                        </div>


                        <button type="submit"
                                :disabled="!confirm||load"
                                class="btn btn-primary p-3 w-100">
                            Отправить анкету
                        </button>

                    </div>
                </form>

            </div>

            <div class="col-12" v-if="botUser.is_vip">

                <p class="alert alert-light my-2" v-html="settings.text_after_submit"></p>

                <div class="alert alert-light">
                    <h6 class="text-primary text-center fw-bold">Ваши данные:</h6>
                    <p class="mb-2 d-flex justify-content-between">Имя: <span class="fw-bold text-primary">{{ botUser.name || 'Не указано' }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-if="settings.need_phone">Телефон: <span class="fw-bold text-primary">{{ botUser.phone || 'Не указано' }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-if="settings.need_email">Email: <span class="fw-bold text-primary">{{ botUser.email || 'Не указано' }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-if="settings.need_city">Город: <span class="fw-bold text-primary">{{ botUser.city || 'Не указано' }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-if="settings.need_birthday">Дата рождения: <span class="fw-bold text-primary">{{
                            botUser.birthday || 'Не указано'
                        }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-if="settings.need_sex">Пол: <span class="fw-bold text-primary">{{ botUser.sex ? 'Мужской' : 'Женский' }}</span></p>
                    <p class="mb-2 d-flex justify-content-between" v-for="field in vipForm.fields">
                        {{ field.title }}:

                        <span v-if="field.config.type===2">
                            {{ field.value === false ? "Нет" : "Да" }}
                        </span>

                        <span v-if="field.config.type===0||field.config.type===1">
                            {{ field.value }}
                        </span>
                    </p>
                </div>

            </div>
        </div>


    </div>


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
                dataObject: this.vipForm
            }).then((resp) => {
                this.loading = false

                this.$notify({
                    title: 'Отлично!',
                    text: "Вы успешно заполнили форму и стали наши VIP-пользователем!",
                    type: "success"
                })

                this.tg.close()
            }).catch(() => {
                this.loading = false

                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка заполнения формы!",
                    type: "error"
                })
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
