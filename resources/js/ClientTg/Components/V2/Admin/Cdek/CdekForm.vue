<script setup>
import SelectOffice from "@/ClientTg/Components/V2/Admin/Cdek/SelectOffice.vue";
import SelectBoxSize from "@/ClientTg/Components/V2/Admin/Cdek/SelectBoxSize.vue";
</script>
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
            <div class="form-floating">
                <select
                    v-model="cdekForm.config.tariff_code"
                    class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option :value="item.code" v-for="item in tariffs">{{ item.title || '-' }}</option>
                </select>
                <label for="floatingSelect">Тариф перевозки товаров по умолчанию</label>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="dropdown">
                <button class="btn btn-outline-light text-primary dropdown-toggle w-100 p-3" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    Выбрать доп. сервисы
                    <span class="badge bg-primary" v-if="cdekForm.config.services.length>0">{{cdekForm.config.services.length}}</span>
                </button>
                <ul class="dropdown-menu w-100">
                    <li
                        v-for="(item, index) in services"><a
                        @click="toggleService(index)"
                        v-bind:class="{'bg-primary':this.cdekForm.config.services.indexOf(item.code)!==-1}"
                        class="dropdown-item" href="javascript:void(0)">{{ item.title || '-' }}</a></li>
                </ul>
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

        <div class="col-md-12" v-if="load">
            <h6>Настройка офиса отправления</h6>
            <SelectOffice
                :config="cdekForm.config"
                v-on:callback="selectOffice"/>
        </div>

        <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
             style="border-radius:10px 10px 0px 0px;">
            <button type="submit" class="btn btn-primary p-3 w-100">
                <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку
            </button>

        </nav>

    </form>

</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            load: false,

            tariffs: [
                {
                    code: 136,
                    title: 'Посылка склад-склад (до 50 кг)'
                },
                {
                    code: 234,
                    title: 'Экономичная посылка склад-склад (до 50 кг)'
                },
                {
                    code: 368,
                    title: 'Посылка склад-постамат (до 30 кг)'
                },
                {
                    code: 378,
                    title: 'Экономичная посылка склад-постамат (до 50 кг)'
                },
                {
                    code: 2536,
                    title: 'Один офис (ИМ, до 30 кг)'
                }
            ],

            services: [
                {
                    code: 'INSURANCE',
                    title: 'Страхование',
                },
                {
                    code: 'PART_DELIV',
                    title: 'Частичная доставка',
                },
                {
                    code: 'DANGER_CARGO',
                    title: 'Опасный груз',
                },
                {
                    code: 'SMS',
                    title: 'СМС-уведомление(+10р)',
                },
                {
                    code: 'ADULT_GOODS',
                    title: 'Товары 18+',
                },
            ],
            cdekForm: {
                account: null,
                secure_password: null,
                is_active: true,
                bot_id: null,
                config: {
                    tariff_code: null,
                    region: null,
                    city: null,
                    office: null,
                    services: [],
                }
            },
        }
    },

    mounted() {
        if (this.bot.cdek)
            this.$nextTick(() => {
                this.load = false
                this.cdekForm.account = this.bot.cdek.account || null
                this.cdekForm.secure_password = this.bot.cdek.secure_password || null
                this.cdekForm.is_active = this.bot.cdek.is_active || true
                this.cdekForm.bot_id = this.bot.cdek.bot_id || this.bot.id || null

                if (this.bot.cdek?.config) {
                    this.cdekForm.config.tariff_code = this.bot.cdek.config.tariff_code || null
                    this.cdekForm.config.region = this.bot.cdek.config.region || null
                    this.cdekForm.config.city = this.bot.cdek.config.city || null
                    this.cdekForm.config.office = this.bot.cdek.config.office || null
                }
                this.load = true
            })


    },
    methods: {
        selectOffice(event) {

            this.cdekForm.config.region = event.region
            this.cdekForm.config.city = event.city
            this.cdekForm.config.office = event.office

            //this.loadCdekOffices(direction)
        },
        toggleService(index) {
            let service = this.services[index]

            let selectedServiceIndex = this.cdekForm.config.services.findIndex(item => item === service.code)

            if (selectedServiceIndex === -1)
                this.cdekForm.config.services.push(service.code)
            else
                this.cdekForm.config.services.splice(selectedServiceIndex, 1)
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
                    title: "Работа с CDEK",
                    text: "Данные успешно сохранены",
                    type: "success",
                });
            }).catch(err => {
                this.$notify({
                    title: "Работа с CDEK",
                    text: "Ошибка работы",
                    type: "error",
                });
            })


        }
    }
}
</script>
