
<template>

    <h6 class="opacity-75 mb-3 mt-2 d-flex justify-content-between" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Введи промокод и нажми на кнопку рядом чтоб узнать % скидки!"    >
        <span >Промокод на скидку <i class="fa-regular fa-circle-question"></i></span>

        <span v-if="spent_time_counter>0">{{spent_time_counter}} сек.</span>
        <div class="spinner-border spinner-border-sm text-primary" v-if="is_requested" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

    </h6>
        <div class="input-group mb-3">

            <div class="form-floating ">
                <input type="text"
                       :disabled="spent_time_counter>0"
                       @change="submit"
                       v-model="promocodeForm.code"
                       class="form-control border-light" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Ваш промокод

                </label>
            </div>

            <button
                v-if="discount===0"
                @click="submit"
                :disabled="spent_time_counter>0"
                class="btn btn-outline-light text-primary" style="min-width:170px;">
                <i class="fa-solid fa-tags"></i> Активировать
            </button>
            <span
                v-if="discount>0"
                style="min-width:170px;"
                class="input-group-text bg-transparent fw-bold text-primary" id="basic-addon1">-{{discount}} руб.</span>
        </div>

</template>
<script>


export default {
    name: "App",

    data() {
        return {
            spent_time_counter: 0,
            is_requested: false,
            discount:0,
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

            this.is_requested = true
            this.$store.dispatch("activateShopDiscountPromocode", {
                promocodeForm: this.promocodeForm
            }).then(resp => {
                this.is_requested = false

                this.discount = resp.discount
                this.$notify({
                    title: "Промокод",
                    text: "Промокод успешно активирован!",
                    type: "success"
                })

                this.$emit("callback",{
                    code:this.promocodeForm.code || null,
                    discount: this.discount || 0
                })

            }).catch(() => {
                this.is_requested = false
                this.discount = 0

                this.$emit("callback",{
                    code:this.promocodeForm.code || null,
                    discount: 0
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
