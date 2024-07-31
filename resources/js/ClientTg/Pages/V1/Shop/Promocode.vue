<script setup>

import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content">
            <p class="text-center font-weight-bold mb-0">Приветствуем!</p>
            <p class="text-center font-italic">
                У вас есть промокод? Отлично! Активируйте его прямо сейчас и наслаждайтесь эксклюзивными
                преимуществами! </p>
            <form v-on:submit.prevent="submit">
                <div
                    class="input-style input-style-2 has-icon">
                    <i class="input-icon fa-solid fa-terminal"></i>
                    <input class="form-control"
                           type="text"
                           v-model="promocodeForm.code"
                           placeholder="Ваш промокод" required>
                </div>
                <button
                    type="submit"
                    :disabled="spent_time_counter>0"
                    class="btn btn-full btn-sm rounded-l bg-highlight font-800 text-uppercase w-100 mb-2">
                      <span
                          v-if="spent_time_counter<=0"
                          class="color-white">Активировать промокод</span>
                    <span
                        v-else
                        class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
                </button>
            </form>


        </div>
    </div>

    <!--    <ReturnToBot class="my-2"></ReturnToBot>-->
</template>
<script>


export default {
    name: "App",

    data() {
        return {
            spent_time_counter: 0,
            is_requested: false,
            promocodeForm: {
                code: null,
            },

        };
    },

    mounted() {
        if (localStorage.getItem("cashman_promocode_activate_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_promocode_activate_counter"))
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
                    localStorage.setItem("cashman_promocode_activate_counter", this.spent_time_counter)
                }, 1000
            )
        },
        submit() {
            this.startTimer();

            this.$store.dispatch("activatePromocode", {
                promocodeForm: this.promocodeForm
            }).then(resp => {


                this.$notify({
                    title: 'Промокод',
                    text: "Промокод успешно активирован!",
                    type: "success"
                })

                this.promocodeForm.code = null
            }).catch(() => {


                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка активации промокода!",
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
