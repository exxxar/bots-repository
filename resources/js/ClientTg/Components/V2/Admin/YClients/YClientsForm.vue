<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3>Настройка YClients</h3>

            <form
                v-on:submit.prevent="submitYClients">

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" for="login">
                        Логин
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
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
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
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
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
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

                <button
                    class="btn btn-m btn-full w-100 rounded-s mb-3 rounded-0 text-uppercase font-900 shadow-s bg-green2-dark">
                    Сохранить
                </button>
            </form>

        </div>
    </div>


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
                this.$notify({
                    title:'Работа с YClients',
                    text: "Данные успешно сохранены",
                    type:'success'
                })
            }).catch(err => {
                this.$notify({
                    title:'Работа с YClients',
                    text: "Ошибка!",
                    type:'error'
                })
            })
        }
    }
}
</script>
