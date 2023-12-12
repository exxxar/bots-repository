<template>
    <h6>Настройка YClients</h6>

    <form
        v-on:submit.prevent="submitYClients">

        <div class="row">
            <div class="col-md-12">
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" for="login">
                        Логин
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text"
                           id="login"
                           class="form-control"
                           placeholder="Ваш логин"
                           aria-label="Ваш логин"
                           v-model="yClientsForm.login"
                           aria-describedby="Ваш логин" required>
                </div>

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" for="password">
                        Пароль
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text"
                           id="password"
                           class="form-control"
                           placeholder="Ваш пароль"
                           aria-label="Ваш пароль"
                           v-model="yClientsForm.password"
                           aria-describedby="Ваш пароль" required>
                </div>


                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" for="partner_token">
                        Партнерский тоукен
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text"
                           id="partner_token"
                           class="form-control"
                           placeholder="Ваш партнерский тоукен"
                           aria-label="Ваш партнерский тоукен"
                           v-model="yClientsForm.partner_token"
                           aria-describedby="Ваш партнерский тоукен" required>
                </div>

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" for="partner_token">
                        ID компании, в которую записывать клиентов
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="number"
                           id="throttle"
                           class="form-control"
                           placeholder="ID компании"
                           aria-label="ID компании"
                           v-model="yClientsForm.company"
                           aria-describedby="ID компании" required>
                </div>
            </div>

        </div>


        <button
            class="btn btn-success w-100 r mb-3  text-uppercase font-900 ">
            Сохранить
        </button>
    </form>


</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["data"],
    data() {
        return {
            load: false,
            bot: null,
            yClientsForm: {
                login: null,
                password: null,
                partner_token: null,
                company:null,
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
                this.yClientsForm.login = this.data.login || null
                this.yClientsForm.password = this.data.password || null
                this.yClientsForm.partner_token = this.data.partner_token || null
                this.yClientsForm.company = this.data.company || null
            })


    },
    methods: {
        submitYClients() {

            let data = new FormData();
            Object.keys(this.yClientsForm)
                .forEach(key => {
                    const item = this.yClientsForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            data.append("bot_id", this.bot.id)
            this.$store.dispatch("saveYClients", {
                yClientsForm: data
            }).then((response) => {

                this.$notify({
                    title: "Работа с YClients",
                    text: "Данные YClients успешно сохранены",
                    type: 'success'
                });
            }).catch(err => {
                this.$notify({
                    title: "Работа с YClients",
                    text: "Ошибка сохранения данных",
                    type: 'error'
                });
            })
        }
    }
}
</script>
