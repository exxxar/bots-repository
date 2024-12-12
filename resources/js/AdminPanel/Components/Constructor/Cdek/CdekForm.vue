<template>


    <form
        v-on:submit.prevent="submitCdek"
        class="row py-3">
        <div class="col-md-6 col-12 mb-2">

            <div class="form-floating">
                <input type="text" class="form-control"
                       placeholder="account"
                       aria-label="account"
                       v-model="cdekForm.account"
                       aria-describedby="account">
                <label class="form-label" id="account">
                    Аккаунт
                </label>
            </div>

        </div>
        <div class="col-md-6 col-12 mb-2">
            <div class="form-floating">
            <input type="text" class="form-control"
                   placeholder="secure_password"
                   aria-label="secure_password"
                   v-model="cdekForm.secure_password"
                   aria-describedby="secure_password">
            <label class="form-label" id="secure_password">
                Пароль приложения
            </label>
            </div>
        </div>
        <div class="col-md-6 mb-2">

            <div class="form-check form-switch">
                <input class="form-check-input"
                       v-model="cdekForm.is_active"
                       type="checkbox" role="switch" id="is_active">
                <label class="form-check-label" for="is_active">Статус
                    <span v-if="cdekForm.is_active" class="fw-bold text-primary">вкл</span>
                    <span v-else class="fw-bold text-primary">выкл</span>
                </label>
            </div>

        </div>

        <div class="col-md-12 col-12">
            <button type="submit" class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку
            </button>
        </div>
    </form>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["data"],
    data() {
        return {
            bot: null,
            cdekForm: {
                account: null,
                secure_password: null,
                is_active: true,
                bot_id: null,
            },
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.bot = this.getCurrentBot

        if (this.data)
            this.$nextTick(() => {
                this.cdekForm.account = this.data.account || null
                this.cdekForm.secure_password = this.data.secure_password || null
                this.cdekForm.is_active = this.data.is_active || true
                this.cdekForm.bot_id = this.data.bot_id || this.bot.id || null
            })
        else {
            this.cdekForm.bot_id = this.bot.id || null
        }


    },
    methods: {
        submitCdek() {
            /*    if (!this.hasConnect) {
                    return;
                }*/
            let data = new FormData();
            Object.keys(this.cdekForm)
                .forEach(key => {
                    const item = this.cdekForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("saveCdek", {
                cdekForm: data
            }).then((response) => {
                this.$notify("Данные успешно сохранены");
            }).catch(err => {

            })


        }
    }
}
</script>
