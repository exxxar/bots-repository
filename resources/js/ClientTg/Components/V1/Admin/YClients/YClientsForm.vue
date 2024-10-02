<template>

    <h3>Настройка YClients</h3>

    <form
        v-on:submit.prevent="submitYClients">

        <div class="form-floating mb-2">

            <input type="text"
                   id="login"
                   class="form-control"
                   placeholder="Ваш логин"
                   aria-label="Ваш логин"
                   v-model="yClientsForm.login"
                   aria-describedby="Ваш логин" required>

            <label for="login">
                Логин

            </label>
        </div>

        <div class="form-floating mb-2">

            <input type="text"
                   id="password"
                   class="form-control"
                   placeholder="Ваш пароль"
                   aria-label="Ваш пароль"
                   v-model="yClientsForm.password"
                   aria-describedby="Ваш пароль" required>
            <label  for="password">
                Пароль
            </label>
        </div>


        <div class="form-floating mb-2">

            <input type="text"
                   id="partner_token"
                   class="form-control"
                   placeholder="Ваш партнерский тоукен"
                   aria-label="Ваш партнерский тоукен"
                   v-model="yClientsForm.partner_token"
                   aria-describedby="Ваш партнерский тоукен" required>
            <label for="partner_token">
                Партнерский тоукен
            </label>
        </div>


        <div class="form-floating mb-2">
            <input type="number"
                   id="throttle"
                   class="form-control"
                   placeholder="ID компании"
                   aria-label="ID компании"
                   v-model="yClientsForm.company"
                   aria-describedby="ID компании" required>
            <label for="partner_token">
                ID компании, в которую записывать клиентов
            </label>
        </div>

        <button
            class="btn btn-primary w-100 p-3">
            Сохранить
        </button>
    </form>


</template>
<script>


export default {
    props: ["data", "bot"],
    data() {
        return {
            load: false,
            yClientsForm: {
                login: null,
                password: null,
                partner_token: null,
                company: null,
            },
        }
    },

    mounted() {


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


            this.$store.dispatch("saveYClients", {
                yClientsForm: data
            }).then((response) => {
                this.$botNotification.success("Работа с YClients", "Данные успешно сохранены");
            }).catch(err => {
                this.$botNotification.warning("Работа с YClients", "Ошибочка");
            })
        }
    }
}
</script>
