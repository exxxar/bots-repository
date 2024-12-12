<template>

    <h3>Настройка CDEK</h3>

    <form
        v-on:submit.prevent="submitCdek"
        class="row py-3">
        <div class="col-md-6 col-12 mb-2">

            <div class="form-floating">
                <input type="text" class="form-control"
                       placeholder="account"
                       aria-label="account"
                       v-model="cdekForm.account"
                       required
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
                       required
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
    props: [ "bot"],
    data() {
        return {
            load: false,


            cdekForm: {
                account: null,
                secure_password: null,
                is_active: true,
                bot_id: null,
            },
        }
    },

    mounted() {
        if (this.bot.cdek)
            this.$nextTick(() => {
                this.cdekForm.account = this.bot.cdek.account || null
                this.cdekForm.secure_password = this.bot.cdek.secure_password || null
                this.cdekForm.is_active = this.bot.cdek.is_active || true
                this.cdekForm.bot_id = this.bot.cdek.bot_id || this.bot.id || null
            })

        this.loadCdekCities()
        this.loadCdekRegions()
        this.loadCdekOffices()
    },
    methods: {
        loadCdekRegions() {
            this.$store.dispatch("loadCdekRegions").then((resp) => {
                console.log("regions", resp)
            })
        },
        loadCdekOffices() {
            this.$store.dispatch("loadCdekOffices",{
                page:0,
                size:100,
                city_code: 6,
            }).then((resp) => {
                console.log("offices", resp)
            })
        },
        loadCdekCities() {
            this.$store.dispatch("loadCdekCities").then((resp) => {
                console.log("cities", resp)
            })
        },
        submitCdek() {

            let data = new FormData();
            Object.keys(this.cdekForm)
                .forEach(key => {
                    const item = this.cdekForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("storeCdek", {
                cdekForm: data
            }).then((response) => {
                this.$notify({
                    title:"Работа с CDEK",
                    text:"Данные успешно сохранены",
                    type:"success",
                });
            }).catch(err => {
                this.$notify({
                    title:"Работа с CDEK",
                    text:"Ошибка работы",
                    type:"error",
                });
            })


        }
    }
}
</script>
