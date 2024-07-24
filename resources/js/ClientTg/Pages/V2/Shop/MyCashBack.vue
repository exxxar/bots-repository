<script setup>

import CashBackList from "@/ClientTg/Components/V2/CashBack/CashBackList.vue";
</script>
<template>

    <div class="container" v-if="self">
        <div class="row">
            <div class="col-12">
                <h6 class="opacity-75 my-3" >Ваш текущий баланс</h6>

                <ul class="list-group">
                <li
                    class="list-group-item d-flex justify-content-between p-3"
                    aria-current="true">
                    <span>Получено CashBack</span>
                    <span class="text-primary fw-bold">{{ self.cashBack.amount || 0 }} ₽</span>
                </li>
                </ul>
            </div>
            <div class="col-12">
                <template v-if="self.cashBack">
                    <h6 class="opacity-75 my-3" v-if="(self.cashBack.subs||[]).length>0">Специальные начисления</h6>

                    <ul class="list-group" v-if="(self.cashBack.subs||[]).length>0">
                        <li class="list-group-item d-flex justify-content-between p-3"
                            v-for="sub in self.cashBack.subs"
                            aria-current="true">
                            <span>{{ sub.title || '-' }}</span>
                            <span class="text-primary fw-bold">{{ sub.amount || 0 }} ₽</span>
                        </li>
                    </ul>
                </template>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="my-3"><i class="fa-solid fa-list-check mr-1 text-primary"></i> История операция</h5>
            </div>
            <div class="col-12">
              <CashBackList
                  v-if="getSelf"
                  :bot-user="getSelf"></CashBackList>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
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
        ...mapGetters(['getSelf', 'getCashBack',
            'getCashBackPaginateObject']),
        self() {
            return this.getSelf
        }
    },
    mounted() {
        if (this.self)
            this.loadCashBack()
    },
    methods: {

        nextCashBackPage(index) {
            this.loadCashBack(index)
        },
        loadCashBack(page = 0) {
            this.$store.dispatch("loadCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.self.telegram_chat_id
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
