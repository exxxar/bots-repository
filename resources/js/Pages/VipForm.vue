<script setup>
defineProps({
    bot: {
        type: Object,
    },

    botUser: {
        type: Object
    },

});
</script>
<template>
    <div class="container pt-3 pb-3" v-if="!botUser.is_vip">
        <form v-on:submit.prevent="submit" class="row">

            <div class="col-12">

                <div class="input-group mb-3">
                    <span class="input-group-text" id="vipForm-name">Ф.И.О.</span>
                    <input type="text" class="form-control"
                           placeholder="Петров Петр Семенович"
                           aria-label="vipForm-name"
                           v-model="vipForm.name"
                           aria-describedby="vipForm-name" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="vipForm-email">Почта</span>
                    <input type="email" class="form-control"
                           placeholder="test@gmail.com"
                           v-model="vipForm.email"
                           aria-label="vipForm-email"
                           aria-describedby="vipForm-email" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="vipForm-phone">Телефон</span>
                    <input type="text" class="form-control"
                           v-mask="'+7(###)###-##-##'"
                           v-model="vipForm.phone"
                           placeholder="+7(000)000-00-00"
                           aria-label="vipForm-phone" aria-describedby="vipForm-phone">
                </div>

                <div class="input-group mb-3">
                    <div class="btn-group w-100" role="group" aria-label="vipForm-sex">
                        <input
                            type="radio"
                            v-model="vipForm.sex"
                            class="btn-check" name="sex-radio-btn" id="sex-radio-btn-1" autocomplete="off" checked>
                        <label class="btn btn-outline-success" for="sex-radio-btn-1">Мужской</label>

                        <input type="radio"
                               v-model="vipForm.sex"
                               class="btn-check" name="sex-radio-btn" id="sex-radio-btn-2" autocomplete="off">
                        <label class="btn btn-outline-success" for="sex-radio-btn-2">Женский</label>

                    </div>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Дата рождения</span>
                    <input type="date" class="form-control"
                           v-model="vipForm.birthday"
                           aria-label="vipForm-birthday" aria-describedby="vipForm-birthday">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="vipForm-city">Город</span>
                    <input type="text"
                           v-model="vipForm.city"
                           list="datalistCityOptions"
                           class="form-control" placeholder="Краснодар"
                           aria-label="vipForm-city" aria-describedby="vipForm-city">
                    <datalist id="datalistCityOptions">
                        <option value="Краснодар"/>
                        <option value="Ростов-на-Дону"/>
                        <option value="Таганрог"/>
                        <option value="Донецк"/>
                        <option value="Москва"/>
                    </datalist>
                </div>

                <div class="card w-100 mb-3 mt-3 border-success">
                    <div class="card-body">
                        <p>Перед отправкой данных ознакомьтесь с <a href="#">правилами данного сервиса</a> и с <a href="#">политикой конфиденциальности</a>.</p>
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   v-model="confirm"
                                   type="checkbox" role="switch" id="confirm">
                            <label class="form-check-label" for="confirm">С правилами ознакомлен</label>
                        </div>
                    </div>
                </div>
                <button type="submit"
                        :disabled="!confirm&&load"
                        class="btn btn-outline-success w-100">Отправить анкету</button>
            </div>
        </form>
    </div>
    <div class="container pt-3 pb-3" v-else>
        <div class="row">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-body">
                        Поздравляем! Вы являетесь нашим VIP-пользователеме! Вам доступны следующие возможности:
                        <ul>
                            <li>Накопление CashBack за покупки</li>
                            <li>Оплата товаров через CashBak</li>
                            <li>Реферальная программа</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            load:false,
            confirm:false,
            vipForm:{
                name: null,
                phone: null,
                email: null,
                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,

            }
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    methods:{
        submit(){
            this.loading = true;
            this.$store.dispatch("saveVip", {
                dataObject:{
                    bot_id: this.bot.id,
                    tg:this.tgUser,
                    form:this.vipForm
                }
            }).then((resp) => {
                this.loading = false
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
