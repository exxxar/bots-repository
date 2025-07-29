<script setup>
import {getSpentTimeCounter} from "@/ClientTg/utils/commonMethods.js";
</script>
<template>
    <h6 class="opacity-75 mb-2 mt-2 d-flex justify-content-between" data-bs-container="body" data-bs-toggle="popover"
        data-bs-placement="top" data-bs-content="Введи промокод и нажми на кнопку рядом чтоб узнать % скидки!">
        <span>Промокод на скидку <i class="fa-regular fa-circle-question"></i></span>

        <span v-if="getSpentTimeCounter()>0">{{ spent_time }} сек.</span>
        <div class="spinner-border spinner-border-sm text-primary" v-if="is_requested" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

    </h6>

    <div class="alert-light border-primary alert mb-2">
        <span class="text-primary fw-bold">Внимание!</span> После активации промокода его нельзя использовать повторно!
    </div>


    <div class="form-floating mb-2">
        <input type="text"
               :disabled="spent_time>0"

               v-model="promocodeForm.code"
               class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Ваш промокод</label>
    </div>
<!--

    <p class="mb-2 text-center" v-if="discount>0">
        -{{ discount }}
        <span v-if="discount_in_percent">%</span>
        <span v-if="!discount_in_percent">руб</span>
    </p>
-->

    <button
        v-if="discount===0"
        type="button"
        @click="submit"
        :disabled="spent_time>0"
        class="btn btn-primary p-3 w-100 mb-2">
        <i class="fa-solid fa-tags"></i>
        <span v-if="spent_time > 0">   Осталось ждать {{ spent_time }} сек.</span>
        <span v-else> Активировать</span>
    </button>

</template>
<script>

import {startTimer, getSpentTimeCounter, checkTimer} from "@/ClientTg/utils/commonMethods.js";

export default {
    name: "App",

    data() {
        return {
            spent_time: 0,
            is_requested: false,
            discount: 0,
            discount_in_percent: false,
            activate_price: 0,
            promocodeForm: {
                code: null,
            },

        };
    },
    watch: {

        'promocodeForm.code': {
            handler: function (newValue) {
                this.discount = 0
            },
            deep: true
        },
    },
    mounted() {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

        this.is_requested = checkTimer()

        window.addEventListener("trigger-spent-timer", (event) => { // (1)
            this.spent_time = event.detail
        });

    }
    ,
    methods: {
        submit() {
            if (this.promocodeForm.code == null)
                return;

            startTimer(10);

            this.is_requested = true
            this.$store.dispatch("activateShopDiscountPromocode", {
                promocodeForm: this.promocodeForm
            }).then(resp => {
                this.is_requested = false

                this.discount = resp.discount || 0
                this.discount_in_percent = resp.discount_in_percent || false
                this.activate_price = resp.activate_price || 0
                this.$notify({
                    title: "Промокод",
                    text: "Промокод успешно активирован!",
                    type: "success"
                })

                this.$emit("callback", {
                    code: this.promocodeForm.code || null,
                    discount: this.discount || 0,
                    discount_in_percent: this.discount_in_percent || false,
                    activate_price: this.activate_price || 0
                })

            }).catch(() => {
                this.is_requested = false
                this.discount = 0
                this.activate_price = 0

                this.$emit("callback", {
                    code: this.promocodeForm.code || null,
                    discount: 0,
                    discount_in_percent: false,
                    activate_price: 0
                })

                this.$notify({
                    title: "Промокод",
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
