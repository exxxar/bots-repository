<script setup>
import {cashbackLimit} from "@/ClientTg/utils/commonMethods.js";
</script>
<template>

    <div class="list-group my-3" v-if="deliveryForm">

        <a href="javascript:void(0)"
           v-bind:class="{'active':deliveryForm.payment_type === 4}"
           @click="deliveryForm.payment_type = 4"
           v-if="settings.can_use_sbp"
           class="list-group-item list-group-item-action p-3 d-flex align-items-center"><i
            class="fa-solid fa-file-invoice mr-2"></i>
            <span class="d-inline-flex justify-content-between w-100 px-2">
                Оплата по СБП
                <img
                    style="width:45px;"
                    v-lazy="'/images/СБП_логотип.svg'" alt="">
                </span>
        </a>

        <a href="javascript:void(0)"
           @click="deliveryForm.payment_type = 0"
           v-if="settings.can_use_card&&settings.payment_token!=null"
           v-bind:class="{'active':deliveryForm.payment_type === 0}"
           class="list-group-item list-group-item-action p-3" aria-current="true">
            <i class="fa-solid fa-earth-americas mr-2"></i>
            <span class="px-2">Онлайн через бота</span>
        </a>
        <a href="javascript:void(0)"
           v-bind:class="{'active':deliveryForm.payment_type === 1}"
           v-if="deliveryForm.pick_up_type==0&&settings.can_use_cash"
           @click="deliveryForm.payment_type = 1"

           class="list-group-item list-group-item-action p-3"><i
            class="fa-regular fa-credit-card mr-2"></i>

            <span class="px-2">Картой</span>
        </a>
        <a href="javascript:void(0)"
           v-if="settings.can_use_cash"
           v-bind:class="{'active':deliveryForm.payment_type === 2}"
           @click="deliveryForm.payment_type = 2"
           class="list-group-item list-group-item-action p-3"><i
            class="fa-solid fa-file-invoice mr-2"></i>
            <span class="px-2">Переводом</span>
        </a>
        <a href="javascript:void(0)"
           v-bind:class="{'active':deliveryForm.payment_type === 3}"
           @click="deliveryForm.payment_type = 3"
           v-if="settings.can_use_cash"
           class="list-group-item list-group-item-action p-3"><i
            class="fa-regular fa-money-bill-1 mr-2"></i>
            <span class="px-2">Наличными</span>
        </a>
    </div>


    <template v-if="(settings.need_bonuses_section||false)&&cashbackLimit()>0">
        <h6 class="opacity-75">Бонусы <small>(нажми для использования)</small></h6>

        <div class="card my-3"
             v-bind:class="{'text-bg-primary':deliveryForm?.use_cashback}"
             @click="deliveryForm.use_cashback=!deliveryForm?.use_cashback">
            <div
                class="card-body">
                <p class="d-flex justify-content-between mb-0">
                    <span> Списать баллы</span>
                    <strong>{{ cashbackLimit() }}₽</strong>
                </p>
            </div>
        </div>
    </template>

</template>
<script>
import {mapGetters} from "vuex";
import {cashbackLimit} from "@/ClientTg/utils/commonMethods.js";

export default {
    props: ["modelValue"],
    data() {
        return {
            deliveryForm: null,
        }
    },
    watch: {
        'deliveryForm': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.deliveryForm)
            },
            deep: true
        },
        'modelValue': {
            handler: function (newValue) {
                this.deliveryForm = newValue
            },
            deep: true
        },
    },
    mounted() {
        this.deliveryForm = this.modelValue

        if (this.settings.can_use_sbp){
            this.deliveryForm.payment_type = 4
        }
    },
    computed: {
        ...mapGetters(['cartProducts', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),

        settings() {
            return this.bot.settings
        },
        bot() {
            return window.currentBot
        },
    }
}
</script>
