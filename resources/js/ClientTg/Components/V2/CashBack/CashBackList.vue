<script setup>

import Pagination from '@/ClientTg/Components/V1/Pagination.vue'

import CashBackItem from "@/ClientTg/Components/V2/CashBack/CashBackItem.vue";
</script>
<template>
    <ul class="list-group" v-if="cashback.length>0">
        <CashBackItem :item="item" v-for="item in cashback"></CashBackItem>
    </ul>

    <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
        <div class="d-flex justify-content-center flex-column align-items-center">
            <i class="fa-brands fa-bitcoin mb-3" style="font-size:36px;"></i>

            <p>Операция с CashBack-ом еще нет:(</p>
        </div>
    </div>
    <Pagination

        v-on:pagination_page="nextCashBackPage"
        v-if="cashback_paginate_object"
        :pagination="cashback_paginate_object"/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:["botUser"],
    data() {
        return {
            cashback: [],
            cashback_paginate_object: null,
        }
    },
    watch:{
        'self': {
            handler: function (newValue) {
                this.loadCashBack()
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters([ 'getCashBack',
            'getCashBackPaginateObject']),

    },
    mounted() {

            this.loadCashBack()
    },
    methods: {

        nextCashBackPage(index) {
            this.loadCashBack(index)
        },
        loadCashBack(page = 0) {
            this.$store.dispatch("loadCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id
                },
                page: page
            }).then(resp => {

                this.cashback = this.getCashBack
                this.cashback_paginate_object = this.getCashBackPaginateObject


            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
