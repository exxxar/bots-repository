<template>

    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center fw-bold my-3">Приветствуем!</h5>
                        <p class="text-center fst-italic">
                            У вас есть пароль от данной страницы? Отлично! Активируйте его прямо сейчас и наслаждайтесь эксклюзивными
                            преимуществами! </p>
                        <form v-on:submit.prevent="submit">

                            <div class="form-floating mb-3">
                                <input type="text"
                                       v-model="passwordForm.password"
                                       class="form-control" id="floatingInput" placeholder="name@example.com" required>
                                <label for="floatingInput"> <i class="input-icon fa-solid fa-terminal"></i> Ваш пароль</label>
                            </div>


                            <button
                                type="submit"
                                :disabled="spent_time_counter>0"
                                class="btn w-100 btn-primary mb-2 p-3">
                      <span
                          v-if="spent_time_counter<=0"
                          class="color-white">Активировать пароль</span>
                                <span
                                    v-else
                                    class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>


export default {
    name: "App",

    data() {
        return {
            spent_time_counter: 0,
            is_requested: false,
            passwordForm: {
                password: null,
                page_id:null,
            },

        };
    },

    mounted() {

        const urlParams = new URLSearchParams(window.location.search);
        this.passwordForm.page_id = JSON.parse(urlParams.get('page_id')) || null

        if (localStorage.getItem("cashman_password_activate_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_password_activate_counter"))
        }
    }
    ,
    methods: {
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_password_activate_counter", this.spent_time_counter)
                }, 1000
            )
        },
        submit() {
            this.startTimer();

            this.$store.dispatch("activatePassword", {
                passwordForm: this.passwordForm
            }).then(resp => {


                this.$notify({
                    title: 'Пароль страницы',
                    text: "Пароль страницы успешно активирован!",
                    type: "success"
                })

                this.passwordForm.password = null
            }).catch(() => {

                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка активации пароля!",
                    type: "error"
                })
            })

        }
    },
}
;
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 14px;
    font-weight: 900;
    margin: 0 !important;
}

</style>
